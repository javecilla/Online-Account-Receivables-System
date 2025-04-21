$(document).ready(async function () {
  if (!localStorage.getItem('filterAmortStatus')) {
    localStorage.setItem('filterAmortStatus', AMORTIZATION_STATUS.join(','))
  }

  if (!localStorage.getItem('filterAmortLoanTypes')) {
    localStorage.setItem('filterAmortLoanTypes', LOAN_TYPE_NAMES.join(','))
  }

  const filterAmortStatus =
    localStorage.getItem('filterAmortStatus') || AMORTIZATION_STATUS.join(',')
  const filterAmortLoanTypes =
    localStorage.getItem('filterAmortLoanTypes') || LOAN_TYPE_NAMES.join(',')

  let tempFilterAmortStatus = filterAmortStatus
  let tempFilterAmortLoanTypes = filterAmortLoanTypes

  $('#filterAmortizationStatusSelected').val(tempFilterAmortStatus)
  $('#filterAmortizationTypeSelected').val(tempFilterAmortLoanTypes)

  $('.checkbox-input-status').each(function () {
    const statusValue = $(this).data('status')

    if (statusValue !== undefined && statusValue !== null) {
      const targetStatus = statusValue.toString()

      if (filterAmortStatus.includes(targetStatus)) {
        $(this).prop('checked', true)
      }

      $(this).on('change', function () {
        tempFilterAmortStatus = $('.checkbox-input-status:checked')
          .map(function () {
            const checkedStatus = $(this).data('status')
            return checkedStatus !== undefined && checkedStatus !== null
              ? checkedStatus.toString()
              : null
          })
          .get()
          .filter((status) => status !== null)

        $('#filterAmortizationStatusSelected').val(
          tempFilterAmortStatus.join(',')
        )
      })
    }
  })

  $('.checkbox-input-type').each(function () {
    const typeValue = $(this).data('type')

    if (typeValue !== undefined && typeValue !== null) {
      const targetType = typeValue.toString()

      if (filterAmortLoanTypes.includes(targetType)) {
        $(this).prop('checked', true)
      }

      $(this).on('change', function () {
        tempFilterAmortLoanTypes = $('.checkbox-input-type:checked')
          .map(function () {
            const checkedType = $(this).data('type')
            return checkedType !== undefined && checkedType !== null
              ? checkedType.toString()
              : null
          })
          .get()
          .filter((type) => type !== null)

        $('#filterAmortizationTypeSelected').val(
          tempFilterAmortLoanTypes.join(',')
        )
      })
    }
  })

  $('#filterAmortizationTableBtn').click(function () {
    //toastr.info('#amortizationFilterModal')
    openModal('#amortizationFilterModal')
  })

  $('#amortizationFilterSubmitBtn').click(async function () {
    const selectedStatus = $('#filterAmortizationStatusSelected').val()
    const selectedLoanTypes = $('#filterAmortizationTypeSelected').val()

    if (isEmpty(selectedStatus) || isEmpty(selectedLoanTypes)) {
      toastr.warning(
        'Please select at least one status and loan types. It cannot be empty.'
      )
      return
    }

    localStorage.setItem('filterAmortStatus', selectedStatus)
    localStorage.setItem('filterAmortLoanTypes', selectedLoanTypes)

    $(this).text('Filtering...').prop('disabled', true)
    await displayAmortizationsByStatus()
    closeModal('#amortizationFilterModal')
    $(this).text('Save and Close').prop('disabled', false)
  })

  const swalBase = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-light action-btn',
      cancelButton: 'btn btn-light action-btn',
      showLoaderOnConfirm: 'swal2-loader'
    },
    buttonsStyling: false
  })

  //TODO: AMORTIZATION PAYMENTS TABLE
  const $paymentsLogsTable = $('#paymentsLogsTable')
  window.displayAmortizationPayments = async function () {
    try {
      const payments = await fetchAmortizationPayments()
      if (payments.success) {
        DataTableAmortizationPayments($paymentsLogsTable, payments.data)
      }
    } catch (error) {
      console.error('Error displaying amortization payments:', error)
    }

    //registered listener for payments
    $paymentsLogsTable.on('click', '.notes-btn', function () {
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
    //$paymentsLogsTable.on('click', '.invoice-btn', function () {})
  }

  //TODO: AMORTIZATIONS BY STATUS TABLE
  const $amortizationsTable = $('#amortizationsTable')
  window.displayAmortizationsByStatus = async function () {
    try {
      const amortization = await fetchAmortizationsByCriteria(
        $('#filterAmortizationStatusSelected').val(),
        $('#filterAmortizationTypeSelected').val()
      ) //fetchAmortizationsByStatus()
      if (amortization.success) {
        //console.log(amortization.data)
        DataTableAmortizationsByStatus($amortizationsTable, amortization.data)
      }
    } catch (error) {
      console.error('Error fetching amortizations by status:', error)
    }
  }

  $amortizationsTable.on('click', '.view-details-btn', async function () {
    const amortizationId = $(this).data('amortization-id')
    //console.log(`Amortization ID: ${amortizationId}`)
    try {
      const amort = await fetchAmortization(amortizationId)
      if (amort.success) {
        //console.log(amort.data)
        const amortization = amort.data
        $('#amortizationDetailsMemberName').val(amortization.full_name)
        $('#amortizationDetailsType').val(amortization.type_name)
        $('#amortizationDetailsStatus').val(amortization.status)
        $('#amortizationDetailsInterestRate').val(amortization.interest_rate)
        $('#amortizationDetailsPrincipalAmount').val(
          amortization.principal_amount
        )
        $('#amortizationDetailsMonthlyAmount').val(amortization.monthly_amount)
        $('#amortizationDetailsRemainingBalance').val(
          amortization.remaining_balance
        )
        $('#amortizationDetailsStartDate').val(
          moment(amortization.start_date).format('DD MMM YYYY')
        )
        $('#amortizationDetailsEndDate').val(
          moment(amortization.end_date).format('DD MMM YYYY')
        )
        openModal('#amortizationDetailsModal')
        // displayAmortizationDetails(amortization.data)
      }
    } catch (error) {
      console.error('Error fetching amortization details:', error)
    }
  })

  // $('#amortizationDetailsModalBtn').click(function () {
  //   closeModal('#amortizationDetailsModal')
  // })

  const $updateStatusCurrentStatus = $('#updateStatusCurrentStatus')
  const $updateStatusNewStatus = $('#updateStatusNewStatus')
  const $updateStatusEmail = $('#updateStatusEmail')
  const $updateStatusTitle = $('#updateStatusTitle')
  const $updateStatusMessage = $('#updateStatusMessage')

  $amortizationsTable.on('click', '.update-status-btn', async function () {
    const amortizationId = $(this).data('amortization-id')
    const amortizationStatus = $(this).data('amortization-status')
    const memberId = $(this).data('member-id')
    const email = $(this).data('email')
    const fullName = $(this).data('full-name')
    // toastr.info(`amortizationStatus: ${amortizationStatus}`)

    $updateStatusCurrentStatus.val(
      amortizationStatus.charAt(0).toUpperCase() + amortizationStatus.slice(1)
    )
    $updateStatusEmail
      .val(email)
      .attr('data-member-name', fullName)
      .attr('data-member-id', memberId)
      .attr('data-amortization-id', amortizationId)

    openModal('#updateStatusModal')
  })

  $updateStatusNewStatus.on('change', function () {
    const selectedStatus = $(this).val()
    const selectedStatusLabel = $(this).find('option:selected').text()
    $('#updateStatusNewStatusUI').val(selectedStatusLabel)

    if (selectedStatus === 'defaulted') {
      $('#defaultedMailNotifyContainer').removeClass('hidden')
      $updateStatusTitle.focus()
    } else {
      $('#defaultedMailNotifyContainer').addClass('hidden')
    }
  })

  $('#updateStatusSubmitBtn').click(async function () {
    const accountId = $('meta[name="account-id"]').attr('content')
    //const currentStatus = $updateStatusCurrentStatus.val().toLowerCase()
    const newStatus = $updateStatusNewStatus.val()
    const email = $updateStatusEmail.val()
    const memberId = $updateStatusEmail.attr('data-member-id')
    const memberName = $updateStatusEmail.attr('data-member-name')
    const amortizationId = $updateStatusEmail.attr('data-amortization-id')
    const title = $updateStatusTitle.val()
    const message = $updateStatusMessage.val()

    if (isEmpty(newStatus)) {
      toastr.warning('New status cannot be empty.')
      return
    }

    const data = {
      amortization_id: amortizationId,
      status: newStatus,
      //current_status: currentStatus,
      account_id: accountId,
      email: email,
      member_id: memberId,
      member_name: memberName,
      title: title,
      message: message
    }
    //console.log(data)
    $(this).text('Updating...').prop('disabled', true)
    try {
      const response = await updateAmortizationStatus(data)
      if (response.success) {
        toastr.success(response.message)
        await displayAmortizationsByStatus()
        closeModal('#updateStatusModal')

        $updateStatusTitle.val('')
        $updateStatusMessage.val('')
        $('#updateStatusNewStatusUI').val('')
        $updateStatusNewStatus.val('')
        $('#defaultedMailNotifyContainer').addClass('hidden')
      }
    } catch (error) {
      console.error('Error updating status:', error)
    } finally {
      $(this).text('Update').prop('disabled', false)
    }
  })

  $amortizationsTable.on(
    'click',
    '.view-payments-history-btn',
    async function () {
      const memberId = $(this).data('member-id')
      const amortizationId = $(this).data('amortization-id')
      const amortizationTypeName = $(this).data('amortization-type-name')
      //window.open(`members?mode=view&member_id=${memberId}#internal`, '_blank')
      addURLParams('tab', 'approved')
      addURLParams('mode', 'view')
      addURLParams('for', 'payments')
      addURLParams('member_id', memberId)
      addURLParams('amortization_id', amortizationId)
      addURLParams('amortization_type_name', amortizationTypeName)
      setHashFragment('history')

      await handlePageContent()
    }
  )

  const $paymentsHistoryTable = $('#paymentsHistoryTable')
  window.displayMemberAmortizationPaymentsTable = async function (
    amortizationId
  ) {
    try {
      LoadingManager.show($('.main-content'))
      const payments = await fetchMemberAmortizationPayments(amortizationId)
      if (payments.success) {
        //console.log(payments.data)
        DataTableMemberAmortizationPayments(
          $paymentsHistoryTable,
          payments.data
        )
      }
      LoadingManager.hide($('.main-content'))

      $paymentsHistoryTable.on('click', '.notes-btn', function () {
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
      //$paymentsHistoryTable.on('click', '.invoice-btn', function () {})
    } catch (error) {
      console.error('Error fetching member amortization payments:', error)
    }
  }

  const $notifyMemberName = $('#notifyMemberName')
  const $notifyEmail = $('#notifyEmail')

  const $notifyTypeUI = $('#notifyTypeUI')
  const $notifyType = $('#notifyType')
  const $notifyTitle = $('#notifyTitle')
  const $notifyMessage = $('#notifyMessage')

  $amortizationsTable.on('click', '.send-reminder-btn', function () {
    const amortization = $(this).data('amortization-id')
    const memberId = $(this).data('member-id')
    const email = $(this).data('email')
    const fullName = $(this).data('full-name')
    // console.log(
    //   `amortization: ${amortization} | memberId: ${memberId} | email: ${email} | fullName: ${fullName}`
    // )
    $notifyMemberName
      .val(fullName)
      .attr('data-member-id', memberId)
      .attr('data-amortization-id', amortization)
    $notifyEmail.val(email)
    openModal('#notifyMemberModal')
  })

  function clearNotifyFields() {
    $notifyType.val('')
    $notifyTypeUI.val('')
    $notifyTitle.val('')
    $notifyMessage.val('')
  }

  $notifyType.on('change', function () {
    //const selectedType = $(this).val()
    const selectedName = $(this).find('option:selected').text()
    $notifyTypeUI.val(selectedName)
  })

  $('#notifyMemberSubmitBtn').click(async function () {
    const accountId = $('meta[name="account-id"]').attr('content')
    const title = $notifyTitle.val()
    const message = $notifyMessage.val()
    const type = $notifyType.val()

    const memberId = $notifyMemberName.attr('data-member-id')
    const memberName = $notifyMemberName.val()
    const memberEmail = $notifyEmail.val()
    const amortizationId = $notifyMemberName.attr('data-amortization-id')

    if (isEmpty(type) || isEmpty(title) || isEmpty(message)) {
      toastr.warning('Please fill in all required fields')
      return
    }

    const data = {
      account_id: accountId,
      title: title,
      message: message,
      type: type,
      member_id: memberId,
      name: memberName,
      email: memberEmail,
      amortization_id: amortizationId
    }
    //console.table(data)
    try {
      $(this).text('Sending...').prop('disabled', true)
      const response = await createNotification(data)
      if (response.success) {
        toastr.success(response.message)
        closeModal('#notifyMemberModal')
        clearNotifyFields()
      }
    } catch (error) {
      console.error('Error sending notification:', error)
    } finally {
      $(this).text('Send').prop('disabled', false)
    }
  })

  $('#notifyMemberCloseModalBtn').click(function () {
    closeModal('#notifyMemberModal')
    clearNotifyFields()
  })

  $amortizationsTable.on('click', '.mark-overdue-btn', async function () {
    const $button = $(this) // Store button reference
    const amortizationId = $button.data('id')
    const isPotentiallyOverdue = $button.data('is-overdue') // Read the data attribute

    // Frontend Check: Prevent action if frontend logic says it's not overdue
    if (isPotentiallyOverdue !== true) {
      // Check explicitly for true
      swalBase.fire({
        icon: 'warning',
        title: 'Cannot Mark Overdue',
        html: '<small>This loan does not appear to be overdue based on the calculated next due date. Please verify the payment status.</small>',
        confirmButtonText: 'Okay'
      })
      return
    }
  })

  //TODO: AMORTIZATIONS BY APPROVAL TABLE
  const $loanRequestsTable = $('#loanRequestsTable ')
  window.displayAmortizationsByApproval = async function () {
    try {
      const amortization = await fetchAmortizationsByApproval()
      if (amortization.success) {
        DataTableAmortizationsByApproval($loanRequestsTable, amortization.data)
      }
    } catch (error) {
      console.error('Error fetching amortizations by approval:', error)
    }
  }

  function handleApprovalButtonClick(buttonClass, successMessage) {
    $loanRequestsTable.on('click', buttonClass, async function () {
      const amortizationId = $(this).data('id')
      const approval = $(this).data('approval')
      // toastr.info(`amortizationId: ${amortizationId} | ${approval}`);
      $(this).prop('disabled', true)
      try {
        const response = await updateAmortizationApproval(
          amortizationId,
          approval
        )
        if (response.success) {
          toastr.success(successMessage)
          await displayAmortizationsByApproval()
          if (buttonClass === '.approved-btn') {
            await displayAmortizationsByStatus()
          }
        }
      } catch (error) {
        console.error('Error updating approval status:', error)
      }
      $(this).prop('disabled', false)
    })
  }

  handleApprovalButtonClick(
    '.approved-btn',
    'Loan request has been approved successfully'
  )
  handleApprovalButtonClick(
    '.reject-btn',
    'Loan request has been rejected successfully'
  )
  handleApprovalButtonClick(
    '.revert-btn',
    'Loan request has been reverted successfully'
  )

  $loanRequestsTable.on('click', '.delete-btn', async function () {
    const amortizationId = $(this).data('id')
    swalBase.fire({
      title: 'Delete Confirmation',
      html: '<small>Are you sure you want to delete this loan request record? This action is cannot be undone.</small>',
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
              // toastr.success('delete testing: ' + amortizationId)
              try {
                const res = await deleteAmortization(amortizationId)
                if (res.success) {
                  toastr.success(res.message)
                  await displayAmortizationsByApproval()
                }
              } catch (error) {
                console.error('Error deleting amortization:', error)
              }
              swalBase.close()
            }, 1500)
          })
        }
      },
      allowOutsideClick: function () {
        !submitForm.isLoading()
      }
    })
  })

  const $tableContainerElements = $('.tableContainerContent')
  const $formContainerElements = $('.formContainerContent')
  const $modeBreadCrumb = $('#modeBreadCrumb')

  async function handlePageContent() {
    const tab = getURLParams('tab')
    const mode = getURLParams('mode')
    const forAccount = getURLParams('for')
    const hashFragment = getHashFragment()
    if (
      tab === 'approved' &&
      mode === 'view' &&
      forAccount === 'payments' &&
      hashFragment === 'logs'
    ) {
      $('.amortizationContent').addClass('hidden')
      $('.paymentsLogsContent').removeClass('hidden')
      $('.paymentsHistoryContent').addClass('hidden')
      $('.amortilizationTitle').text(
        'List of payments logs for all amortizations:'
      )
      // $tableContainerElements.addClass('hidden')
      // $formContainerElements.removeClass('hidden')
      // $modeBreadCrumb.text('/ View')
    } else if (
      tab === 'approved' &&
      mode === 'view' &&
      forAccount === 'payments' &&
      hashFragment === 'history'
    ) {
      $('.amortizationContent').addClass('hidden')
      $('.paymentsLogsContent').addClass('hidden')
      $('.paymentsHistoryContent').removeClass('hidden')
      $('.amortilizationTitle').text(
        `List of payments history on ${getURLParams('amortization_type_name')}:`
      )

      await displayMemberAmortizationPaymentsTable(
        getURLParams('amortization_id')
      )
    } else {
      $('.amortizationContent').removeClass('hidden')
      $('.paymentsLogsContent').addClass('hidden')
      $('.paymentsHistoryContent').addClass('hidden')
      $('.amortilizationTitle').text('List of amortizations approved:')
      // $tableContainerElements.removeClass('hidden')
      // $formContainerElements.addClass('hidden')
      // $modeBreadCrumb.text('/')

      removeURLParams('tab')
      removeURLParams('mode')
      removeURLParams('for')
      removeHashFragment()
    }
  }

  $('#viewPaymentsLogsBtn').click(async function () {
    addURLParams('tab', 'approved')
    addURLParams('mode', 'view')
    addURLParams('for', 'payments')
    setHashFragment('logs')

    await handlePageContent()
  })

  $('.backToAmortizationListBtn').click(async function () {
    removeURLParams('tab')
    removeURLParams('mode')
    removeURLParams('for')
    if ($(this).data('for') === 'history') {
      removeURLParams('member_id')
      removeURLParams('amortization_id')
      removeURLParams('amortization_type_name')
    }
    removeHashFragment()
    await handlePageContent()
  })

  $('#refreshAmortizationTableBtn').click(async function () {
    $(this).text('Loading...').prop('disabled', true)
    LoadingManager.show($('.main-content'))

    await displayAmortizationsByStatus()
    await displayAmortizationsByApproval()
    await displayAmortizationPayments()

    LoadingManager.hide($('.main-content'))
    $(this)
      .html('<i class="fas fa-sync-alt me-2"></i>Refresh')
      .prop('disabled', false)
  })

  await handlePageContent()
})
