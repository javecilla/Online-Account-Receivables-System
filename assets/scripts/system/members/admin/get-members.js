$(document).ready(async function () {
  const swalBase = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-light action-btn',
      cancelButton: 'btn btn-light action-btn',
      showLoaderOnConfirm: 'swal2-loader'
    },
    buttonsStyling: false
  })

  //TODO: MEMBERS TABLE
  const $membersTable = $('#membersTable')
  window.displayMembers = async function () {
    try {
      const members = await fetchMembers()
      //console.log('members', members)
      if (members.success) {
        // const metaData = members.data.meta_data
        // console.log(metaData)
        DataTableMembers($membersTable, members.data.items)
      }
    } catch (error) {
      console.error('Error fetching members:', error)
    }

    //EVENT LISTENERS
    $membersTable.on('click', '.view-btn', async function () {
      const member_id = $(this).data('id')
      addURLParams('mode', 'view')
      addURLParams('member_id', member_id)
      setHashFragment('internal')

      await handlePageContent()
    })

    $membersTable.on('click', '.delete-btn', async function () {
      const memberId = $(this).data('id')
      //toastr.info(`Delete account ${memberId} - Feature coming soon`)
      swalBase.fire({
        title: 'Delete Confirmation',
        html: '<small>Are you sure you want to delete this member record? This action is cannot be undone.</small>',
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
                  const res = await deleteMember(memberId)
                  if (res.success) {
                    toastr.success(res.message)
                    await dislayMembers()
                  }
                } catch (error) {
                  console.error('Error deleting account:', error)
                }
                swalBase.close()
              }, 1500) //end set timeout
            }) // end promise
          } // end else for checking response
        }, // end preconfirm
        allowOutsideClick: function () {
          !swalBase.isLoading()
        }
      }) //end swal
    })
  }

  //TODO: MEMBERS TRANSACTION LOGS TABLE
  const $membersTransactionLogsTable = $('#membersTransactionLogsTable')
  window.displayMembersTransactionLogs = async function () {
    try {
      const transactionLogs = await fetchMembersTransactionsLogs()
      if (transactionLogs.success) {
        // console.log(transactionLogs.data)
        DataTableMembersTransactionLogs(
          $membersTransactionLogsTable,
          transactionLogs.data
        )
      }
    } catch (error) {
      console.error('Error fetching logs:', error)
    }

    $membersTransactionLogsTable.on('click', '.notes-btn', function () {
      const transactionId = $(this).data('id')
      const notes = $(this).data('notes')
      //toastr.info(`Transaction ID: ${transactionId}`)
      swalBase.fire({
        title: 'Notes:',
        html: `<small>${notes}</small>`
        //icon: 'info'
      })
    })
  }

  //TODO: MEMBER AMORTIZATIONS > PAYMENTS HISTORY TABLE
  const $memberAmortizationTable = $('#memberAmortilizationTable')
  window.displayMemberAmortizations = async function (memberId) {
    try {
      const amortizations = await fetchMemberAmortizations(memberId)
      if (amortizations.success) {
        DataTableMemberAmortizations(
          $memberAmortizationTable,
          amortizations.data
        )
      }
    } catch (error) {
      console.error('Error fetching member amortizations:', error)
    }

    const $paymentsTable = $('#paymentsTable')
    $memberAmortizationTable.on(
      'click',
      '.viewAmortizationPaymentsBtn',
      async function () {
        const amortizationId = $(this).data('id')
        const amortizationTypeName = $(this).data('at-name')
        //toastr.success(`Amortization ID: ${amortizationId}`)
        $('#memberAmortizationsListContent').addClass('hidden')
        $('#memberAmortilizationPaymentsContent').removeClass('hidden')
        $('.memberAmortilizationPaymentsAction').removeClass('hidden')
        $('.memberAmortilizationTitle').text(
          `List of all payments records of member in ${amortizationTypeName}:`
        )
        try {
          LoadingManager.show($('.main-content'))
          const payments = await fetchMemberAmortizationPayments(amortizationId)
          if (payments.success) {
            DataTableMemberAmortizationPayments($paymentsTable, payments.data)
          }
          LoadingManager.hide($('.main-content'))

          //registered listener for payments
          $paymentsTable.on('click', '.notes-btn', function () {
            const amortizationId = $(this).data('id')
            const notes = $(this).data('notes')
            //toastr.info(`Amortization ID: ${amortizationId}`)
            swalBase.fire({
              title: 'Notes:',
              html: `<small>${notes}</small>`
              //icon: 'info'
            })
          })

          //TODO: Add invoice button
          //$paymentsTable.on('click', '.invoice-btn', function () {})
        } catch (error) {
          console.error('Error fetching member amortization payments:', error)
        }
      }
    )
  }

  $('#backtoMemberAmortizationsListContentBtn').click(function () {
    $('#memberAmortizationsListContent').removeClass('hidden')
    $('#memberAmortilizationPaymentsContent').addClass('hidden')
    $('.memberAmortilizationPaymentsAction').addClass('hidden')
    $('.memberAmortilizationTitle').text('')
  })

  //TODO: MEMBER TRANSACTION HISTORY TABLE
  const $memberTransactionHistoryTable = $('#memberTransactionsTable')
  window.displayMemberTransactionsHistory = async function (memberId) {
    try {
      const transactions = await fetchMemberTransactionsHistory(memberId)
      if (transactions.success) {
        DataTableMemberTransactionsHistory(
          $memberTransactionHistoryTable,
          transactions.data
        )
      }
    } catch (error) {
      console.error('Error fetching member transactions:', error)
    }

    $memberTransactionHistoryTable.on('click', '.notes-btn', function () {
      const transactionId = $(this).data('id')
      const notes = $(this).data('notes')
      //toastr.info(`Transaction ID: ${transactionId}`)
      swalBase.fire({
        title: 'Notes:',
        html: `<small>${notes}</small>`
        //icon: 'info'
      })
    })
  }

  //TODO: MEMBER DETAILS FORM
  window.displayMemberDetails = async function (memberId) {
    try {
      const member = await fetchMember(memberId)
      if (member.success) {
        const data = member.data
        //console.log(data)
        $('#memberCurrentBalance')
          .val(`₱ ${data.current_balance}`)
          .prop('readonly', true)
        $('#memberUID').val(data.member_uid).prop('readonly', true)
        $('#membershipTypeUI').val(data.membership_type)
        $membershipType.val(data.membership_id)
        $memberFirstName.val(data.first_name)
        $memberMiddleName.val(
          isNull(data.middle_name) || isEmpty(data.middle_name)
            ? data.mode === 'view'
              ? 'N/A'
              : ''
            : data.middle_name
        )
        $memberLastName.val(data.last_name)
        $memberContactNumber.val(data.contact_number.substring(3))
        $memberHouseAddress.val(data.house_address)
        $memberBarangay.val(data.barangay)
        $memberMunicipality.val(data.municipality)
        $memberProvince.val(data.province)
        $memberRegion.val(data.region)
        $('#memberCreatedAt')
          .val(formatReadableDateTime(data.member_created_at))
          .prop('readonly', true)
        $('#memberUpdatedAt')
          .val(formatReadableDateTime(data.member_updated_at))
          .prop('readonly', true)

        $('.memberAdditionalInformation').removeClass('hidden')
        $membershipType.prop('disabled', true)
        $memberMiddleName.prop('readonly', true)
        $memberFirstName.prop('readonly', true)
        $memberLastName.prop('readonly', true)
        $memberContactNumber.prop('readonly', true)
        $memberHouseAddress.prop('readonly', true)
        $memberBarangay.prop('readonly', true)
        $memberMunicipality.prop('readonly', true)
        $memberProvince.prop('readonly', true)
        $memberRegion.prop('readonly', true)
        $('#viewMoreAccountInfoBtn').on('click', function () {
          window.open(
            `accounts?mode=view&for=member&account_id=${data.account_id}&member_id=${data.member_id}#internal`,
            '_blank'
          )
        })
      }
    } catch (error) {
      console.error('Error displaying member details:', error)
    }
  }

  const $tableContainerElements = $('.tableContainerContent')
  const $formContainerElements = $('.formContainerContent')
  const $allTransactionsLogsContainerContent = $(
    '#allTransactionsLogsContainerContent'
  )
  const $formMemberContent = $('.formMemberContent')
  const $modeBreadCrumb = $('#modeBreadCrumb')

  $('#viewMembersTransactionsLogsBtn').on('click', async function () {
    addURLParams('mode', 'show')
    setHashFragment('transactions-logs')
    await handlePageContent()
  })

  async function handlePageContent() {
    const mode = getURLParams('mode')
    const hashFragment = getHashFragment()
    // console.log(mode)
    // console.log(hashFragment)

    if (mode === 'view' || (mode === 'update' && hashFragment === 'internal')) {
      $tableContainerElements.addClass('hidden')
      $formContainerElements.removeClass('hidden')
      $formMemberContent.removeClass('hidden')
      $('#tabContainerContent').removeClass('hidden')
      $modeBreadCrumb.text('/ ' + mode.charAt(0).toUpperCase() + mode.slice(1))
      $allTransactionsLogsContainerContent.addClass('hidden')
      try {
        const activeMemberId = getURLParams('member_id')
        LoadingManager.show($('.main-content'))
        // await displayMemberDetails(activeMemberId)
        // await displayMemberAmortizations(activeMemberId)
        // await displayMemberTransactionsHistory(activeMemberId)
        await Promise.all([
          displayMemberDetails(activeMemberId),
          displayMemberAmortizations(activeMemberId),
          displayMemberTransactionsHistory(activeMemberId)
        ])
      } catch (error) {
        console.error('An error occurred.')
      } finally {
        LoadingManager.hide($('.main-content'))
      }
    } else if (mode === 'show' && hashFragment === 'transactions-logs') {
      $tableContainerElements.addClass('hidden')
      $formContainerElements.removeClass('hidden')
      $formMemberContent.addClass('hidden')
      $allTransactionsLogsContainerContent.removeClass('hidden')
      $modeBreadCrumb.text('/ ' + mode.charAt(0).toUpperCase() + mode.slice(1))
    } else {
      $tableContainerElements.removeClass('hidden')
      $formContainerElements.addClass('hidden')
      $formMemberContent.addClass('hidden')
      $('#tabContainerContent').addClass('hidden')
      $modeBreadCrumb.text('/')
      $allTransactionsLogsContainerContent.addClass('hidden')
    }
  }

  $('#backToTableContainerBtn').on('click', async function () {
    removeHashFragment()
    removeAllParams()
    removeAllParams()
    await handlePageContent()
  })

  await handlePageContent()
})
