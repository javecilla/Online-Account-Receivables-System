$(document).ready(async function () {
  if (!localStorage.getItem('filterMemberTypes')) {
    localStorage.setItem('filterMemberTypes', MEMBERSHIP_TYPES.join(','))
  }

  if (!localStorage.getItem('filterMemberStatus')) {
    localStorage.setItem('filterMemberStatus', MEMBERSHIP_STATUS.join(','))
  }

  const filterMemberTypes =
    localStorage.getItem('filterMemberTypes') || MEMBERSHIP_TYPES.join(',')
  const filterMemberStatus =
    localStorage.getItem('filterMemberStatus') || MEMBERSHIP_STATUS.join(',')

  let tempFilterMemberTypes = filterMemberTypes
  let tempFilterMemberStatus = filterMemberStatus

  $('#filterMemberTypeSelected').val(tempFilterMemberTypes)
  $('#filterMemberStatusSelected').val(tempFilterMemberStatus)

  const swalBase = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-light action-btn',
      cancelButton: 'btn btn-light action-btn',
      showLoaderOnConfirm: 'swal2-loader'
    },
    buttonsStyling: false
  })

  $('.checkbox-input-type').each(function () {
    const typeValue = $(this).data('type')

    if (typeValue !== undefined && typeValue !== null) {
      const targetType = typeValue.toString()

      if (filterMemberTypes.includes(targetType)) {
        $(this).prop('checked', true)
      }

      $(this).on('change', function () {
        tempFilterMemberTypes = $('.checkbox-input-type:checked')
          .map(function () {
            const checkedType = $(this).data('type')
            return checkedType !== undefined && checkedType !== null
              ? checkedType.toString()
              : null
          })
          .get()
          .filter((type) => type !== null)

        $('#filterMemberTypeSelected').val(tempFilterMemberTypes.join(','))
      })
    }
  })

  $('.checkbox-input-status').each(function () {
    const statusValue = $(this).data('status')

    if (statusValue !== undefined && statusValue !== null) {
      const targetStatus = statusValue.toString()

      if (filterMemberStatus.includes(targetStatus)) {
        $(this).prop('checked', true)
      }

      $(this).on('change', function () {
        tempFilterMemberStatus = $('.checkbox-input-status:checked')
          .map(function () {
            const checkedStatus = $(this).data('status')
            return checkedStatus !== undefined && checkedStatus !== null
              ? checkedStatus.toString()
              : null
          })
          .get()
          .filter((status) => status !== null)

        $('#filterMemberStatusSelected').val(tempFilterMemberStatus.join(','))
      })
    }
  })

  $('#filterMembersBtn').click(function () {
    openModal('#memberFilterModal')
  })

  $('#memberFilterSubmitBtn').click(async function () {
    const selectedMemberTypes = $('#filterMemberTypeSelected').val()
    const selectedMemberStatus = $('#filterMemberStatusSelected').val()

    if (isEmpty(selectedMemberTypes) || isEmpty(selectedMemberStatus)) {
      toastr.warning(
        'Please select at least one member type and status to filter.'
      )
      return
    }

    localStorage.setItem('filterMemberTypes', selectedMemberTypes)
    localStorage.setItem('filterMemberStatus', selectedMemberStatus)

    $(this).text('Filtering...').prop('disabled', true)
    await displayMembers()
    closeModal('#memberFilterModal')
    $(this).text('Save and Close').prop('disabled', false)
  })

  $('#refreshMembersTableBtn').click(async function () {
    $(this).text('Loading...').prop('disabled', true)
    LoadingManager.show($('.main-content'))

    await Promise.all([displayMembers(), displayMembersTransactionLogs()])

    LoadingManager.hide($('.main-content'))
    $(this)
      .html('<i class="fas fa-sync-alt me-2"></i>Refresh')
      .prop('disabled', false)
  })

  //TODO: MEMBERS TABLE
  const $membersTable = $('#membersTable')
  window.displayMembers = async function () {
    try {
      const members = await fetchMembersByCriteria(
        $('#filterMemberTypeSelected').val(),
        $('#filterMemberStatusSelected').val()
      ) //fetchMembers()
      //console.log('members', members)
      if (members.success) {
        // const metaData = members.data.meta_data
        // console.log(metaData)
        DataTableMembers($membersTable, members.data) //members.data.items
      }
    } catch (error) {
      console.error('Error fetching members:', error)
    }
  }

  //EVENT LISTENERS
  $membersTable.on('click', '.view-btn', async function () {
    const memberId = $(this).data('id')
    const membershipType = $(this).data('mt')
    if (membershipType === 'Loan') {
      $('#savingsGoalCard').addClass('hidden')
      $('#activeLoansCard').removeClass('hidden')
      //$('#upcomingPaymentsCard').removeClass('hidden')
    } else {
      $('#savingsGoalCard').removeClass('hidden')
      $('#activeLoansCard').addClass('hidden')
      // $('#upcomingPaymentsCard').addClass('hidden')
    }

    addURLParams('mode', 'view')
    addURLParams('member_id', memberId)
    addURLParams('mt', membershipType)
    setHashFragment('internal')

    await handlePageContent()
  })

  const $currentStatus = $('#updateStatusCurrentStatus')
  const $newStatus = $('#updateStatusNewStatus')
  const $newStatusUI = $('#updateStatusNewStatusUI')
  const $sendEmailCheckbox = $('#sendEmailCheck')
  const $statusMailContainer = $('#statusMailNotifyContainer')
  const $email = $('#updateStatusEmail')
  const $title = $('#updateStatusTitle')
  const $message = $('#updateStatusMessage')
  const $memberName = $('#updateStatusActiveMemberName')

  $membersTable.on('click', '.update-status-btn', function () {
    const memberId = $(this).data('id')
    const memberEmail = $(this).data('email')
    const memberStatus = $(this).data('status')
    const fullName = $(this).data('full-name')

    $currentStatus.val(
      memberStatus.charAt(0).toUpperCase() + memberStatus.slice(1)
    )
    $email
      .val(memberEmail)
      .attr('data-member-name', fullName)
      .attr('data-member-id', memberId)

    $memberName.text(fullName)
    openModal('#updateStatusModal')
  })

  $newStatus.on('change', function () {
    //const selectedStatus = $(this).val()
    const selectedStatusLabel = $(this).find('option:selected').text()
    $('#updateStatusNewStatusUI').val(selectedStatusLabel)
  })

  $sendEmailCheckbox.on('change', function () {
    const isChecked = $(this).is(':checked')
    if (isChecked) {
      $statusMailContainer.removeClass('hidden')
      $title.focus()
    } else {
      $statusMailContainer.addClass('hidden')
      $title.val('')
      $message.val('')
    }
  })

  const resetUpdateStatusModalForm = function () {
    $newStatus.val('')
    $newStatusUI.val('')
    $sendEmailCheckbox.prop('checked', false)
    $statusMailContainer.addClass('hidden')
  }

  $('#updateStatusSubmitBtn').click(async function (e) {
    e.preventDefault()

    const newStatus = $newStatus.val()
    const isSendingEmail = $sendEmailCheckbox.is(':checked')
    const email = $email.val()
    const title = $title.val()
    const message = $message.val()
    const memberName = $memberName.text()
    const memberId = $email.data('member-id')

    if (isEmpty(newStatus)) {
      toastr.warning('Please select a new status.')
      return
    }

    // if (isSendingEmail) {
    //   if (isEmpty(title) || isEmpty(message)) {
    //     toastr.warning('Please fill in the title and message fields.')
    //     return
    //   }
    // }

    if (newStatus === $currentStatus.val()) {
      toastr.warning('New status must be different from the current status.')
      return
    }

    $(this).text('Updating...').prop('disabled', true)
    try {
      const updated = await updateMembershipStatus({
        member_id: memberId,
        membership_status: newStatus,
        is_sending_email: isSendingEmail,
        email: email,
        title: title,
        message: message,
        member_name: memberName
      })
      console.log(updated)
      if (updated.success) {
        toastr.success(updated.message)
        await displayMembers()
        closeModal('#updateStatusModal')
        resetUpdateStatusModalForm()
      }
    } catch (error) {
      console.error(error)
    } finally {
      $(this).text('Update').prop('disabled', false)
    }
  })

  $('#updateStatusCloseBtn').click(function () {
    closeModal('#updateStatusModal')
    resetUpdateStatusModalForm()
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
  const $memberApprovedAmortizationsTable = $('#memberAmortilizationTable')
  window.displayMemberAmortizations = async function (memberId) {
    try {
      const amortizations = await fetchMemberApprovedAmortizations(memberId)
      if (amortizations.success) {
        DataTableMemberApprovedAmortizations(
          $memberApprovedAmortizationsTable,
          amortizations.data
        )
      }
    } catch (error) {
      console.error('Error fetching member amortizations:', error)
    }

    $memberApprovedAmortizationsTable.on(
      'click',
      '.payment-history-btn',
      async function () {
        const amortizationId = $(this).data('id')
        const amortizationType = $(this).data('at-name')

        $('.memberAmortizationsListAction').addClass('hidden')
        $('.memberAmortilizationPaymentsAction').removeClass('hidden')
        $('.memberAmortizationRequestAction').addClass('hidden')
        $('.memberAmortilizationTitle').text(
          `List of amortization payments on ${amortizationType}:`
        )

        $('#memberAmortizationsListContent').addClass('hidden')
        $('#memberAmortilizationPaymentsContent').removeClass('hidden')
        $('#memberAmortizationsRequestContent').addClass('hidden')

        const $memberAmortizationPaymentsTable = $('#paymentsTable')
        try {
          LoadingManager.show($('.main-content'))
          const payments = await fetchMemberAmortizationPayments(amortizationId)
          if (payments.success) {
            //console.log(payments.data)
            DataTableMemberAmortizationPayments(
              $memberAmortizationPaymentsTable,
              payments.data
            )
          }
          LoadingManager.hide($('.main-content'))

          $memberAmortizationPaymentsTable.on(
            'click',
            '.notes-btn',
            function () {
              const amortizationId = $(this).data('id')
              const notes = $(this).data('notes')
              //toastr.info(`Amortization ID: ${amortizationId}`)
              swalBase.fire({
                title: 'Notes:',
                html: `<small>${notes}</small>`
                //icon: 'info'
              })
            }
          )
        } catch (error) {
          console.error('Error fetching member amortization payments:', error)
        }
      }
    )
  }

  const $memberRequestAmortizationsTable = $('#memberAmortizationsRequestTable')
  window.displayMemberRequestAmortizations = async function (memberId) {
    try {
      const amortizations = await fetchMemberRequestAmortizations(memberId)
      if (amortizations.success) {
        //console.log(amortizations.data)
        DataTableMemberRequestAmortizations(
          $memberRequestAmortizationsTable,
          amortizations.data
        )
      }
    } catch (error) {
      console.error('Error fetching member request amortizations:', error)
    }
  }

  $('#viewLoanRequestBtn').click(async function () {
    // addURLParams('tab', 'amortizations')
    // addURLParams('for', 'loan_request')
    $('.memberAmortizationsListAction').addClass('hidden')
    $('.memberAmortilizationPaymentsAction').addClass('hidden')
    $('.memberAmortizationRequestAction').removeClass('hidden')
    $('.memberAmortilizationTitle').text('List of amortization requests:')

    $('#memberAmortizationsListContent').addClass('hidden')
    $('#memberAmortilizationPaymentsContent').addClass('hidden')
    $('#memberAmortizationsRequestContent').removeClass('hidden')
  })

  $('.backtoMemberAmortizationsListContentBtn').click(function () {
    $('.memberAmortizationsListAction').removeClass('hidden')
    $('.memberAmortilizationPaymentsAction').addClass('hidden')
    $('.memberAmortizationRequestAction').addClass('hidden')
    $('.memberAmortilizationTitle').text('List of approved amortizations:')

    $('#memberAmortizationsListContent').removeClass('hidden')
    $('#memberAmortilizationPaymentsContent').addClass('hidden')
    $('#memberAmortizationsRequestContent').addClass('hidden')
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
      const membershipType = getURLParams('mt')
      if (membershipType === 'Loan') {
        $('#savingsGoalCard').addClass('hidden')
        $('#activeLoansCard').removeClass('hidden')
        //$('#upcomingPaymentsCard').removeClass('hidden')
      } else {
        $('#savingsGoalCard').removeClass('hidden')
        $('#activeLoansCard').addClass('hidden')
        // $('#upcomingPaymentsCard').addClass('hidden')
      }

      const promisesToAwait = [
        fetchMember(memberId),
        fetchMemberAccountBalanceMetrics(memberId),
        fetchMemberAccountStatusMetrics(memberId)
      ]

      if (membershipType === 'Loan') {
        promisesToAwait.push(fetchMemberUpcomingPayments(memberId))
        promisesToAwait.push(fetchMemberActiveLoansMetrics(memberId))
      } else {
        promisesToAwait.push(fetchMemberSavingsGoalMetrics(memberId))
      }

      const results = await Promise.all(promisesToAwait)

      const member = results[0].data
      const accountBalance = results[1].data
      let accountStatus = results[2].data // Changed const to let
      // const upcomingPayments = results[3].data // Original index was 3

      let savings = null
      let loans = null
      let upcomingPayments = null

      const $upcomingPaymentsList = $('#upcomingPaymentsList')
      const $noUpcomingPaymentsText = $('#noUpcomingPaymentsText')
      if (membershipType === 'Loan') {
        upcomingPayments = results[3].data // Correct index for upcomingPayments
        //upcoming payments
        //console.log('Upcoming Payments:', upcomingPayments)

        if (upcomingPayments.length > 0) {
          $noUpcomingPaymentsText.addClass('hidden')
          $upcomingPaymentsList.empty().removeClass('hidden')

          upcomingPayments.forEach((payment) => {
            const dueDate = moment(payment.next_due_date_estimate).format(
              'DD MMM YYYY'
            )
            const listItem = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <small class="d-block text-muted">${payment.loan_type}</small>
                <strong>₱${payment.monthly_amount}</strong>
              </div>
              <span class="badge bg-light text-dark">${dueDate}</span>
            </li>
          `
            $upcomingPaymentsList.append(listItem)
          })
        } else {
          $noUpcomingPaymentsText.removeClass('hidden')
          $upcomingPaymentsList.addClass('hidden').empty()
        }

        loans = results[4].data // Correct index for loans
        //console.log('Active Loans:', loans)
        // TODO: Populate loan-specific dashboard elements
        $('#totalActiveLoansCount').text(loans.total_active_loans_count)
        $('#totalLoanAmountValue').text(`₱${loans.total_loan_principal}`)
        $('#overdueAmountValue').text(`₱${loans.total_overdue_amount}`)
        $('#overdueLoansCount').text(loans.overdue_loans_count)
      } else {
        $noUpcomingPaymentsText.removeClass('hidden')
        $upcomingPaymentsList.empty().addClass('hidden')

        savings = results[3].data
        //console.log('Savings Goal:', savings)
        // TODO: Populate savings-specific dashboard elements
        const progressPercentage = Math.min(
          parseFloat(savings.savings_progress_percentage),
          100
        )
        $('#savingsProgressPercentage').text(
          `${progressPercentage.toFixed(2)}%`
        )
        $('#savingsProgressBar').css('width', `${progressPercentage}%`)
        $('#savingsProgressBar').attr('aria-valuenow', progressPercentage)
        //$('#savingsTargetLabel').text()
        $('#savingsTargetValue').text(
          formatCurrency(savings.savings_target_balance)
        )
        $('#savingsCurrentValue').text(
          formatCurrency(savings.savings_current_balance)
        )
      }

      //console.log('Member:', member)
      $('#fullName').text(member.full_name)
      $('#memberId').text(member.member_uid)
      $('#memberStatus').text(
        member.membership_status.charAt(0).toUpperCase() +
          member.membership_status.slice(1)
      )
      $('#memberType').text(member.membership_type)
      $('#memberEmail').text(member.email)
      $('#memberContact').text(member.contact_number)
      $('#memberAddress').text(member.full_address)
      $('#memberDateOpened').text(
        `${moment(member.opened_date).format('DD MMM YYYY')}`
      )
      $('#memberDateClosed').text(
        `${
          member.closed_date !== null
            ? moment(member.closed_date).format('DD MMM YYYY')
            : 'No data available'
        }`
      )

      //balance
      //console.log('Account Balance:', accountBalance)
      $('#totalCurrentBalanceValue').text(
        `₱${accountBalance.total_current_balance}`
      )
      $('#creditBalanceValue').text(`₱${accountBalance.credit_balance}`)
      $('#totalWithdrawalsValue').text(
        `₱${accountBalance.total_withdrawals_last_30d}`
      )

      //account health
      //console.log('Account Status:', accountStatus)
      $('#membershipStatusValue').text(
        accountStatus.membership_status.charAt(0).toUpperCase() +
          accountStatus.membership_status.slice(1)
      )
      $('#memberSinceDate').text(
        `${moment(accountStatus.member_since_date).format('DD MMM YYYY')}`
      )
      $('#loanPaymentStatusValue').text(accountStatus.loan_payment_status)
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

    if (mode === 'view' || hashFragment === 'internal') {
      $tableContainerElements.addClass('hidden')
      $formContainerElements.removeClass('hidden')
      $formMemberContent.removeClass('hidden')
      $('#tabContainerContent').removeClass('hidden')
      $modeBreadCrumb.text('/ ' + mode.charAt(0).toUpperCase() + mode.slice(1))
      $allTransactionsLogsContainerContent.addClass('hidden')
      try {
        const activeMemberId = parseInt(getURLParams('member_id'))
        LoadingManager.show($('.main-content'))
        // await displayMemberDetails(activeMemberId)
        // await displayMemberAmortizations(activeMemberId)
        // await displayMemberTransactionsHistory(activeMemberId)
        await Promise.all([
          displayMemberDetails(activeMemberId),
          displayMemberAmortizations(activeMemberId),
          displayMemberRequestAmortizations(activeMemberId),
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
