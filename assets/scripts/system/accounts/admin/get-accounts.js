$(document).ready(async function () {
  const $tableContainerElements = $('.tableContainerContent')
  const $formContainerElements = $('.formContainerContent')
  const $formMemberContent = $('.formMemberContent')
  const $formEmployeeContent = $('.formEmployeeContent')
  const $modeBreadCrumb = $('#modeBreadCrumb')
  const $forAccountBreadCrumb = $('#forAccountBreadCrumb')
  const $accountsTable = $('#accountsTable')

  let dataTable = $(accountsTable).DataTable({
    responsive: true,
    processing: true,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'Loading accounts records...',
      zeroRecords: 'No matching accounts found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Accounts',
      infoEmpty: 'Showing 0 to 0 of 0 Accounts',
      infoFiltered: '(filtered from _MAX_ total Accounts)',
      search: 'Search Account:',
      lengthMenu: 'Show _MENU_ Accounts',
      paginate: {
        first: '<i class="fas fa-angle-double-left"></i>',
        previous: '<i class="fas fa-angle-left"></i>',
        next: '<i class="fas fa-angle-right"></i>',
        last: '<i class="fas fa-angle-double-right"></i>'
      }
    },
    columns: [
      { data: 'account_uid', title: 'Account ID' },
      { data: 'role_name', title: 'Role' },
      { data: 'email', title: 'Email' },
      // { data: 'username', title: 'Username' },
      {
        data: 'email_verified_at',
        title: 'Verified',
        render: function (data) {
          const verifiedClass =
            data !== null ? 'status-verified-yes' : 'status-verified-no'
          const icon = data !== null ? 'fa-shield-alt' : 'fa-circle-exclamation'
          const statusText = data !== null ? 'Verified' : 'Not Verified'
          return `<span class="status-badge ${verifiedClass}"><i class="fas ${icon}"></i>&nbsp;${statusText}</span>`
        }
      },
      {
        data: 'account_status',
        title: 'Status',
        render: function (data) {
          const statusClass =
            data === 'active' ? 'status-active' : 'status-inactive'
          const icon = data === 'active' ? 'fa-check-circle' : 'fa-times-circle'
          return `<span class="status-badge ${statusClass}"><i class="fas ${icon}"></i>&nbsp;${
            data.charAt(0).toUpperCase() + data.slice(1)
          }</span>`
        }
      },
      {
        data: 'created_at',
        title: 'Joined Date',
        render: function (data) {
          return moment(data).format('DD MMM YYYY, h:mm A')
        }
      },
      {
        data: null,
        title: 'Actions',
        orderable: false,
        render: function (data) {
          const dataFor = data.role_name === 'Member' ? 'member' : 'employee'
          return `
            <div class="d-flex">
              <button class="btn btn-sm action-btn view-btn" data-id="${data.account_id}" data-for="${dataFor}">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm action-btn edit-btn" data-id="${data.account_id}" data-for="${dataFor}">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm action-btn delete-btn" data-id="${data.account_id}" data-for="${dataFor}">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          `
        }
      }
    ],
    order: [[5, 'desc']] // Sort by created_at by default
  })

  // Make these functions available globally for page-loading.js
  window.dislayAccounts = async function () {
    try {
      $accountsTable.addClass('loading')
      const accounts = await fetchAccounts()
      if (accounts.success) {
        dataTable.clear()
        dataTable.rows.add(accounts.data.items)
        dataTable.draw()
        const metaData = accounts.data.meta_data
      } else {
        console.error('Error fetching accounts:', response.message)
        toastr.error('Failed to load accounts data')
      }
    } catch (error) {
      console.error('Error fetching accounts:', error)
      //toastr.error('An error occurred while fetching accounts data')
    } finally {
      $accountsTable.removeClass('loading')
    }
  }

  window.displayAccountRoles = async function () {
    try {
      const roles = await fetchAccountRoles()
      //console.log(roles)
      if (roles.success) {
        const $accountRoleSelect = $('#accountRole')
        $accountRoleSelect.empty()
        roles.data.forEach((role) => {
          if (getURLParams('for') === 'member') {
            if (role.role_name === 'Member') {
              $accountRoleSelect.append(
                `<option value="${role.role_id}" selected>${role.role_name}</option>`
              )
              // $accountRoleSelect.prop('disabled', true)
              return
            }
          }

          // if (getURLParams('for') === 'employee') {
          //   if (role.role_name === 'Member') {
          //     $accountRoleSelect.append(
          //       `<option value="${role.role_id}" disabled>${role.role_name}</option>`
          //     )
          //     return
          //   }
          // }

          //if failed to selected the member, force
          // console.log(roleSelectedTemp && roleSelectedTemp === 'Member')
          // console.log(roleSelectedTemp)
          // if (roleSelectedTemp && roleSelectedTemp === 'Member') {
          //   $accountRoleSelect.append(
          //     `<option value="${role.role_id}" selected>${role.role_name}</option>`
          //   )
          //   return
          // }

          $accountRoleSelect.append(
            `<option value="${role.role_id}">${role.role_name}</option>`
          )
        })

        //event listers for account role select
        $accountRoleSelect.on('change', async function () {
          //remove error messages
          $(this).removeClass('is-invalid')
          $('#accountRoleError').text('')
          //const selectedRoleId = $(this).val()
          const selectedRoleName = $(this).find(':selected').text()
          $('#accountRoleUI').val(selectedRoleName)
          if (selectedRoleName === 'Member') {
            addURLParams('for', 'member')
          } else if (
            selectedRoleName === 'Administrator' ||
            selectedRoleName === 'Accountant'
          ) {
            addURLParams('for', 'employee')
          } else {
            //default
            addURLParams('for', 'member')
          }

          await handlePageContent()
          //reset always lahat ng fields
          //resetAccountForm()
          resetMemberForm()
          resetEmployeeForm()
        })
      }
    } catch (error) {
      console.error('Error fetching account roles:', error)
      toastr.error('Failed to load account roles')
    }
  }

  window.displayMembershipTypes = async function () {
    try {
      const membershipTypes = await fetchMembershipTypes()
      //console.log(membershipTypes)
      if (membershipTypes.success) {
        const $membershipTypesSelect = $('#membershipType')
        $membershipTypesSelect.empty()
        $('#membershipTypeUI').val('')
        $membershipTypesSelect.append('<option value="" selected></option>')
        membershipTypes.data.forEach((type) => {
          $membershipTypesSelect.append(
            `<option value="${type.type_id}">${type.type_name}</option>`
          )
        })

        $membershipTypesSelect.on('change', function () {
          //remove error messages
          $('#membershipTypeUI').removeClass('is-invalid')
          $('#membershipTypeError').text('')
          //appen in the ui select name membership
          const selectedMembershipTypeId = $(this).val()
          const selectedMembershipTypeName = $(this).find(':selected').text()
          $('#membershipTypeUI').val(selectedMembershipTypeName)
        })
      }
    } catch (error) {
      console.error('Error fetching membership types:', error)
      toastr.error('Failed to load membership types')
    }
  }

  // Event listeners for action buttons using jQuery delegation
  $accountsTable.on('click', '.view-btn', async function () {
    const accountId = $(this).data('id')
    const dataFor = $(this).data('for')
    addURLParams('mode', 'view')
    addURLParams('for', dataFor)
    addURLParams('account_id', accountId)
    setHashFragment('internal')

    await handlePageContent()
  })

  $accountsTable.on('click', '.edit-btn', async function () {
    const accountId = $(this).data('id')
    const dataFor = $(this).data('for')
    addURLParams('mode', 'update')
    addURLParams('for', dataFor)
    addURLParams('account_id', accountId)
    setHashFragment('internal')

    await handlePageContent()
  })

  $accountsTable.on('click', '.delete-btn', async function () {
    const accountId = $(this).data('id')
    const dataFor = $(this).data('for')
    //toastr.info(`Delete account ${accountId} - Feature coming soon`)
    const swalDelete = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-light action-btn',
        cancelButton: 'btn btn-light action-btn',
        showLoaderOnConfirm: 'swal2-loader'
      },
      buttonsStyling: false
    })

    swalDelete.fire({
      title: 'Delete Confirmation',
      html: `<small>Deleting this record will affect the <strong>${dataFor}</strong> account associated. This action is cannot be undone.</small>`,
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: 'Cancel',
      showConfirmButton: true,
      confirmButtonText: 'Confirm Delete',
      showLoaderOnConfirm: true,
      allowEscapeKey: false,
      allowOutsideClick: false,
      preConfirm: function (response) {
        if (!response) {
          return false
        } else {
          return new Promise(function (resolve) {
            setTimeout(async function () {
              //toastr.success('delete testing')
              try {
                const res = await deleteAccount(accountId)
                if (res.success) {
                  toastr.success(res.message)
                  await dislayAccounts()
                } else {
                  console.error('Error deleting account:', response.message)
                  toastr.error('Failed to load accounts data')
                }
              } catch (error) {
                console.error('Error deleting account:', error)
                //toastr.error('An error occurred while deting account')
              }
              swalDelete.close()
            }, 1500) //end set timeout
          }) // end promise
        } // end else for checking response
      }, // end preconfirm
      allowOutsideClick: function () {
        !submitForm.isLoading()
      }
    }) //end swal
  })

  // Add account button event listener
  $('#addAccountBtn').on('click', async function () {
    $('#createActionContainer').removeClass('hidden')
    $('#resetBtn').show()
    $('#createAccountBtn').html(
      '<i class="fas fa-save me-2"></i> <span id="accountModeTextBtn">Create</span> Account'
    )
    $('#viewActionContainer').addClass('hidden')
    addURLParams('mode', 'create')
    addURLParams('for', 'member')
    setHashFragment('internal')
    const $accountRoleSelect = $('#accountRole')
    $accountRoleSelect.find('option').each(function () {
      if ($(this).text() === 'Member') {
        $(this).prop('selected', true)
      }
    })
    $('#accountRoleUI').val('Member')
    resetAccountForm()
    resetMemberForm()
    resetEmployeeForm()

    await handlePageContent()
  })

  async function handlePageContent() {
    const mode = getURLParams('mode')
    const forAccount = getURLParams('for')
    const hashFragment = getHashFragment()
    if (mode === 'create' && hashFragment === 'internal') {
      $tableContainerElements.addClass('hidden')
      $formContainerElements.removeClass('hidden')
      $modeBreadCrumb.text('/ Create')
      if (forAccount === 'member') {
        $forAccountBreadCrumb.text('/ Member Account')
        $formMemberContent.removeClass('hidden')
        $formEmployeeContent.addClass('hidden')
      } else if (forAccount === 'employee') {
        $forAccountBreadCrumb.text('/ Employee Account')
        $formMemberContent.addClass('hidden')
        $formEmployeeContent.removeClass('hidden')
      }
    } else if (
      mode === 'view' ||
      (mode === 'update' && hashFragment === 'internal')
    ) {
      $tableContainerElements.addClass('hidden')
      $formContainerElements.removeClass('hidden')
      $modeBreadCrumb.text('/ ' + mode.charAt(0).toUpperCase() + mode.slice(1))
      try {
        const activeAccountId = getURLParams('account_id')
        let response = null
        if (forAccount === 'member') {
          $forAccountBreadCrumb.text('/ Member Account')
          $formMemberContent.removeClass('hidden')
          $formEmployeeContent.addClass('hidden')
          response = await fetchMemberByAccount(activeAccountId)
          if (response.success) {
            const member = response.data
            member.for = forAccount
            member.mode = mode
            await displayAccountInformation(member)
          }
        } else if (forAccount === 'employee') {
          $forAccountBreadCrumb.text('/ Employee Account')
          $formMemberContent.addClass('hidden')
          $formEmployeeContent.removeClass('hidden')
          response = await fetchEmployeeByAccount(activeAccountId)
          if (response.success) {
            const employee = response.data
            employee.for = forAccount
            employee.mode = mode
            await displayAccountInformation(employee)
          }
        }
      } catch (error) {
        console.error('Error fetching account:', error)
        //toastr.error('An error occurred while fetching account')
      }
    } else {
      $tableContainerElements.removeClass('hidden')
      $formContainerElements.addClass('hidden')

      $modeBreadCrumb.text('/')
      $forAccountBreadCrumb.text('')
    }
  }

  async function displayAccountInformation(account) {
    const $mainContent = $('.main-content')
    LoadingManager.show($mainContent)
    //console.log(account)
    try {
      // await displayAccountRoles()
      // await displayMembershipTypes()
      await Promise.all([displayAccountRoles(), displayMembershipTypes()])
      console.log('account', account)
      // ACCOUNT INFO
      $('#accountRoleUI').val(account.role_name)
      $accountRole.val(account.role_id)
      //console.log($accountRole.val())
      $email.val(account.email)
      $username.val(account.username)

      if (account.for === 'member') {
        addURLParams('member_id', account.member_id)
        //display data on fields related to members
        $('#membershipTypeUI').val(account.membership_type)
        $membershipType.val(account.membership_id)
        $memberFirstName.val(account.first_name)
        $memberMiddleName.val(
          isNull(account.middle_name) || isEmpty(account.middle_name)
            ? account.mode === 'view'
              ? 'N/A'
              : ''
            : account.middle_name
        )
        $memberLastName.val(account.last_name)
        $memberContactNumber.val(account.contact_number.substring(3))
        $memberHouseAddress.val(account.house_address)
        $memberBarangay.val(account.barangay)
        $memberMunicipality.val(account.municipality)
        $memberProvince.val(account.province)
        $memberRegion.val(account.region)
      } else {
        addURLParams('employee_id', account.employee_id)
        //dispkay data on fields realted to employees
        $employeeFirstName.val(account.first_name)
        $employeeMiddleName.val(
          isNull(account.middle_name) || isEmpty(account.middle_name)
            ? account.mode === 'view'
              ? 'N/A'
              : ''
            : account.middle_name
        )
        $employeeLastName.val(account.last_name)
        $employeeContactNumber.val(account.contact_number.substring(3))
        $employeeSalary.val(account.salary)
        $employeeRata.val(account.rata)
      }

      if (account.mode === 'view') {
        //ACCOUNT INFO
        $accountRole.prop('disabled', true)
        $email.prop('readonly', true)
        $username.prop('readonly', true)
        $password
          .attr(
            'placeholder',
            'For security purposes password is not displayed'
          )
          .prop('readonly', true)
        //hide confirm password
        $('#confirmPasswordContainerRow').hide()
        $('#accountAdditionalInformation').removeClass('hidden')
        $('#accountStatus').val(account.account_status).prop('readonly', true)
        $('#accountVerified')
          .val(
            isNull(account.email_verified_at) ? 'not yet verified' : 'verfied'
          )
          .prop('readonly', true)
        $('#accountCreatedAt')
          .val(formatReadableDateTime(account.account_created_at))
          .prop('readonly', true)
        $('#accountUpdatedAt')
          .val(formatReadableDateTime(account.account_updated_at))
          .prop('readonly', true)
        //view more member info
        $('#viewMoreMemberInfoBtn').on('click', function () {
          window.open(
            `members?mode=view&member_id=${account.member_id}#internal`,
            '_blank'
          )
        })

        if (account.for === 'member') {
          $membershipType.prop('disabled', true)
          $memberFirstName.prop('readonly', true)
          $memberMiddleName.prop('readonly', true)
          $memberLastName.prop('readonly', true)
          $memberContactNumber.prop('readonly', true)
          $memberHouseAddress.prop('readonly', true)
          $memberBarangay.prop('readonly', true)
          $memberMunicipality.prop('readonly', true)
          $memberProvince.prop('readonly', true)
          $memberRegion.prop('readonly', true)

          //display view buttons and hide create and update btn
          $('#createActionContainer').addClass('hidden')
          $('#viewActionContainer').removeClass('hidden')
          $('#accountRoleTextBtn').text(account.for)
        } else {
          $employeeFirstName.prop('readonly', true)
          $employeeMiddleName.prop('readonly', true)
          $employeeLastName.prop('readonly', true)
          $employeeContactNumber.prop('readonly', true)
          $employeeSalary.prop('readonly', true)
          $employeeRata.prop('readonly', true)

          //hide lahat ukinam
          $('#createActionContainer').addClass('hidden')
          $('#viewActionContainer').addClass('hidden')
        }
      } else {
        $accountRole.prop('disabled', false)
        $email.prop('readonly', false)
        $username.prop('readonly', false)
        $password
          .attr('placeholder', 'Enter new password')
          .prop('readonly', false)
        //show confirm password
        $('#confirmPasswordContainerRow').show()
        $('#accountAdditionalInformation').addClass('hidden')

        if (account.for === 'member') {
          $membershipType.prop('disabled', false)
          $memberFirstName.prop('readonly', false)
          $memberMiddleName.prop('readonly', false)
          $memberLastName.prop('readonly', false)
          $memberContactNumber.prop('readonly', false)
          $memberHouseAddress.prop('readonly', false)
          $memberBarangay.prop('readonly', false)
          $memberMunicipality.prop('readonly', false)
          $memberProvince.prop('readonly', false)
          $memberRegion.prop('readonly', false)
        } else {
          $employeeFirstName.prop('readonly', false)
          $employeeMiddleName.prop('readonly', false)
          $employeeLastName.prop('readonly', false)
          $employeeContactNumber.prop('readonly', false)
          $employeeSalary.prop('readonly', false)
          $employeeRata.prop('readonly', false)
        }

        $('#createActionContainer').removeClass('hidden')
        if (account.mode === 'update') {
          $('#resetBtn').hide()
          $('#accountModeTextBtn').text('Update')
        } else {
          $('#resetBtn').show()
          $('#accountModeTextBtn').text('Create')
        }
        $('#viewActionContainer').addClass('hidden')
      }
    } catch (error) {
      console.error('Error initializing page:', error)
      //toastr.error('An error occurred while loading the page')
    } finally {
      // Hide loading state after all content is loaded
      LoadingManager.hide($mainContent)
    }
  }

  $('#backToTableContainerBtn').on('click', async function () {
    removeHashFragment()
    removeAllParams()
    removeAllParams()
    removeAllParams()
    await handlePageContent()
  })

  await handlePageContent()
})
