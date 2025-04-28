$(document).ready(async function () {
  if (!localStorage.getItem('filterAccountRoles')) {
    localStorage.setItem('filterAccountRoles', ACCOUNT_ROLES.join(','))
  }

  if (!localStorage.getItem('filterAccountStatus')) {
    localStorage.setItem('filterAccountStatus', ACCOUNT_STATUS.join(','))
  }

  if (!localStorage.getItem('filterAccountVerification')) {
    localStorage.setItem(
      'filterAccountVerification',
      ACCOUNT_VERIFICATION.join(',')
    )
  }

  const filterAccountRole =
    localStorage.getItem('filterAccountRoles') || ACCOUNT_ROLES.join(',')
  const filterAccountStatus =
    localStorage.getItem('filterAccountStatus') || ACCOUNT_STATUS.join(',')
  const filterAccountVerification =
    localStorage.getItem('filterAccountVerification') ||
    ACCOUNT_VERIFICATION.join(',')

  let tempFilterAccountRole = filterAccountRole
  let tempFilterAccountStatus = filterAccountStatus
  let tempFilterAccountVerification = filterAccountVerification

  $('#filterAccountRoleSelected').val(tempFilterAccountRole)
  $('#filterAccountStatusSelected').val(tempFilterAccountStatus)
  $('#filterAccountVerificationSelected').val(tempFilterAccountVerification)

  $('#filterAccountList').click(async function () {
    openModal('#accountFilterModal')
  })

  $('#accountFilterSubmitBtn').click(async function () {
    const selectedRoles = $('#filterAccountRoleSelected').val()
    const selectedStatus = $('#filterAccountStatusSelected').val()
    const selectedVerification = $('#filterAccountVerificationSelected').val()

    if (
      isEmpty(selectedRoles) ||
      isEmpty(selectedStatus) ||
      isEmpty(selectedVerification)
    ) {
      toastr.warning(
        'Please select at least one role, status and verification. It cannot be empty.'
      )
      return
    }

    localStorage.setItem('filterAccountRoles', selectedRoles)
    localStorage.setItem('filterAccountStatus', selectedStatus)
    localStorage.setItem('filterAccountVerification', selectedVerification)

    $(this).text('Filtering...').prop('disabled', true)
    await displayAccountsList()
    closeModal('#accountFilterModal')
    $(this).text('Save and Close').prop('disabled', false)
  })

  $('#refreshAccountList').click(async function () {
    LoadingManager.show($('.main-content'))
    $(this).text('Refreshing...').prop('disabled', true)
    await displayAccountsList()
    $(this)
      .html('<i class="fas fa-sync-alt me-2"></i>Refresh')
      .prop('disabled', false)
    LoadingManager.hide($('.main-content'))
  })

  $('.checkbox-input-role').each(function () {
    const roleValue = $(this).data('role')

    if (roleValue !== undefined && roleValue !== null) {
      const targetRole = roleValue.toString()

      if (filterAccountRole.includes(targetRole)) {
        $(this).prop('checked', true)
      }

      $(this).on('change', function () {
        tempFilterAccountRole = $('.checkbox-input-role:checked')
          .map(function () {
            const checkedRole = $(this).data('role')
            return checkedRole !== undefined && checkedRole !== null
              ? checkedRole.toString()
              : null
          })
          .get()
          .filter((role) => role !== null)

        $('#filterAccountRoleSelected').val(tempFilterAccountRole.join(','))
      })
    }
  })

  $('.checkbox-input-status').each(function () {
    const statusValue = $(this).data('status')

    if (statusValue !== undefined && statusValue !== null) {
      const targetRole = statusValue.toString()

      if (filterAccountStatus.includes(targetRole)) {
        $(this).prop('checked', true)
      }

      $(this).on('change', function () {
        tempFilterAccountStatus = $('.checkbox-input-status:checked')
          .map(function () {
            const checkedStatus = $(this).data('status')
            return checkedStatus !== undefined && checkedStatus !== null
              ? checkedStatus.toString()
              : null
          })
          .get()
          .filter((status) => status !== null)

        $('#filterAccountStatusSelected').val(tempFilterAccountStatus.join(','))
      })
    }
  })

  $('.checkbox-input-verify').each(function () {
    const verifyValue = $(this).data('verify')

    if (verifyValue !== undefined && verifyValue !== null) {
      const targetVerify = verifyValue.toString()

      if (filterAccountVerification.includes(targetVerify)) {
        $(this).prop('checked', true)
      }

      $(this).on('change', function () {
        tempFilterAccountVerification = $('.checkbox-input-verify:checked')
          .map(function () {
            const checkedVerify = $(this).data('verify')
            return checkedVerify !== undefined && checkedVerify !== null
              ? checkedVerify.toString()
              : null
          })
          .get()
          .filter((verify) => verify !== null)

        $('#filterAccountVerificationSelected').val(
          tempFilterAccountVerification.join(',')
        )
      })
    }
  })

  const $tableContainerElements = $('.tableContainerContent')
  const $formContainerElements = $('.formContainerContent')
  const $formMemberContent = $('.formMemberContent')
  const $formEmployeeContent = $('.formEmployeeContent')
  const $modeBreadCrumb = $('#modeBreadCrumb')
  const $forAccountBreadCrumb = $('#forAccountBreadCrumb')
  const $accountsTable = $('#accountsTable')

  window.displayAccountsList = async function () {
    try {
      const accounts = await fetchAccountsByCriteria(
        $('#filterAccountRoleSelected').val(),
        $('#filterAccountStatusSelected').val(),
        $('#filterAccountVerificationSelected').val()
      )
      //console.log(accounts)
      if (accounts.success) {
        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable($accountsTable)) {
          $accountsTable.DataTable().destroy()
        }
        // Clear the table body before populating new data
        $accountsTable.find('tbody').empty()
        DataTableAccounts($accountsTable, accounts.data)
      }
    } catch (error) {
      console.error('Error fetching accounts:', error)
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

  const $updateStatusCurrentStatus = $('#updateStatusCurrentStatus')
  const $updateStatusNewStatus = $('#updateStatusNewStatus')
  const $updateStatusNewStatusUI = $('#updateStatusNewStatusUI')
  const $sendEmailCheckbox = $('#sendEmailCheck')
  const $statusMailContainer = $('#statusMailNotifyContainer')
  const $updateStatusEmail = $('#updateStatusEmail')
  const $updateStatusTitle = $('#updateStatusTitle')
  const $updateStatusMessage = $('#updateStatusMessage')

  $accountsTable.on('click', '.update-status-btn', function () {
    const currentStatus = $(this).data('status')
    const email = $(this).data('email')
    const accountId = $(this).data('id')
    //console.log(currentStatus, email, accountId)
    $('#updateStatusModal').data('accountId', accountId)

    //const dataFor = $(this).data('for')
    $updateStatusCurrentStatus.val(
      currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1)
    )
    $updateStatusEmail.val(email)
    openModal('#updateStatusModal')
  })

  $updateStatusNewStatus.on('change', function () {
    //const selectedStatus = $(this).val()
    const selectedStatusLabel = $(this).find('option:selected').text()
    $updateStatusNewStatusUI.val(selectedStatusLabel)
  })

  $sendEmailCheckbox.on('change', function () {
    const isChecked = $(this).is(':checked')
    if (isChecked) {
      $statusMailContainer.removeClass('hidden')
      $updateStatusTitle.focus()
    } else {
      $statusMailContainer.addClass('hidden')
      $updateStatusTitle.val('')
      $updateStatusMessage.val('')
    }
  })

  const resetUpdateStatusModalForm = function () {
    $updateStatusNewStatus.val('')
    $updateStatusNewStatusUI.val('')
    $updateStatusTitle.val('')
    $updateStatusMessage.val('')
    $updateStatusEmail.val('')
    $('#updateStatusModal').removeData('accountId')
    $sendEmailCheckbox.prop('checked', false)
    $statusMailContainer.addClass('hidden')
  }

  $('#updateStatusCloseBtn').click(function () {
    closeModal('#updateStatusModal')
    resetUpdateStatusModalForm()
  })

  $('#updateStatusSubmitBtn').click(async function () {
    const currentStatus = $updateStatusCurrentStatus.val().toLowerCase()
    const newStatus = $updateStatusNewStatus.val()
    const isSendingEmail = $sendEmailCheckbox.is(':checked')
    const email = $updateStatusEmail.val()
    // Retrieve accountId from the modal's data
    const accountId = $('#updateStatusModal').data('accountId')
    const title = $updateStatusTitle.val()
    const message = $updateStatusMessage.val()

    // console.table({
    //   account_id: accountId,
    //   status: newStatus,
    //   email: email,
    //   title: title,
    //   message: message,
    //   is_sending_email: isSendingEmail
    // })
    // toastr.warning('This feature is currently under development.')
    // return

    if (isEmpty(newStatus)) {
      toastr.warning('Please select a new status.')
      return
    }

    if (newStatus === currentStatus) {
      toastr.warning('The new status cannot be the same as the current status.')
      return
    }

    $(this).text('Updating...').prop('disabled', true)
    try {
      const updated = await updateAccountStatus({
        account_id: accountId,
        status: newStatus,
        email: email,
        title: title,
        message: message,
        is_sending_email: isSendingEmail
      })
      if (updated.success) {
        toastr.success(updated.message)
        await displayAccountsList()
        closeModal('#updateStatusModal')
        resetUpdateStatusModalForm()
      }
    } catch (error) {
      console.error(error)
    } finally {
      $(this).text('Update').prop('disabled', false)
    }
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
                  await displayAccountsList()
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
