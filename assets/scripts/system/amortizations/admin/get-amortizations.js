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
      denyButton: 'btn btn-light action-btn',
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

  // PAY BALANCE DUE
  const $payBalanceDueRemainingBalanceInput = $(
    '#payBalanceDueRemainingBalance'
  )
  const $payBalanceDueMonthlyAmountInput = $('#payBalanceDueMonthlyAmount')
  const $payBalanceDueTotalPaidInput = $('#payBalanceDueTotalPaid')
  const $payBalanceDueAmountInput = $('#payBalanceDueAmount')
  const $payBalanceDueFinalTotalAmountInput = $(
    '#payBalanceDueFinalTotalAmount'
  )
  const $payBalanceDuePaymentMethodSelect = $('#payBalanceDuePaymentMethod')
  const $payBalanceDueNotesInput = $('#payBalanceDueNotes')
  const $payBalanceDuePaymentDateInput = $('#payBalanceDuePaymentDate')
  const $payBalanceDueCurrentCreditBalanceText = $(
    '#payBalanceDueCurrentCreditBalance'
  )
  const $payBalanceDueCreditBalanceCheckbox = $(
    '#payBalanceDueCreditBalanceCheck'
  )
  const $payBalanceDueUseCreditBalanceInput = $(
    '#payBalanceDueUseCreditBalance'
  )

  let usedCreditBalance = 0
  let originalAmountPayment = 0
  let finalAmountPayment = 0
  let currentAmortizationId = null
  let memberCreditBalance = 0
  let memberCurrentBalance = 0
  let selectedMemberId = null

  $amortizationsTable.on('click', '.pay-balance-btn', async function () {
    $('#payBalanceDueModalLabel').text($(this).data('title'))

    currentAmortizationId = $(this).data('id')
    memberCreditBalance = $(this).data('credit-balance')
    memberCurrentBalance = $(this).data('current-balance')
    selectedMemberId = $(this).data('member-id')

    const remainingBalance = $(this).data('remaining-balance')
    const totalPaid = $(this).data('total-paid')
    const monthlyAmount = $(this).data('monthly-amount')

    $payBalanceDueRemainingBalanceInput.val(
      parseFloat(remainingBalance).toFixed(2)
    )
    $payBalanceDueMonthlyAmountInput.val(parseFloat(monthlyAmount).toFixed(2))
    $payBalanceDueTotalPaidInput.val(parseFloat(totalPaid).toFixed(2))

    $payBalanceDueUseCreditBalanceInput.val(Math.round(memberCreditBalance))
    $payBalanceDueCurrentCreditBalanceText.text(
      parseFloat(memberCreditBalance).toFixed(2)
    )

    usedCreditBalance = 0
    originalAmountPayment = Math.round(monthlyAmount)
    finalAmountPayment = usedCreditBalance + originalAmountPayment
    //sethe main payment amount initially to the monthly amount
    $payBalanceDueAmountInput.val(originalAmountPayment)
    $payBalanceDueFinalTotalAmountInput.val(finalAmountPayment)

    $payBalanceDuePaymentDateInput.val(new Date().toISOString().split('T')[0])

    $payBalanceDueCreditBalanceCheckbox.prop('checked', false)
    $('#payBalanceDueUseCreditBalanceField').addClass('hidden')
    $payBalanceDueAmountInput.prop('readonly', false)

    openModal('#payBalanceDueModal')
  })

  $payBalanceDueCreditBalanceCheckbox.on('change', async function () {
    if (memberCreditBalance <= 0 && $(this).is(':checked')) {
      toastr.warning('You have no credit balance.')
      $(this).prop('checked', false)
      $('#payBalanceDueUseCreditBalanceField').addClass('hidden')
      return
    }

    const isChecked = $(this).is(':checked')
    const remainingBalanceDue =
      parseFloat($payBalanceDueRemainingBalanceInput.val()) || 0
    const monthlyAmount = parseFloat($payBalanceDueMonthlyAmountInput.val())

    if (isChecked) {
      $('#payBalanceDueUseCreditBalanceField').removeClass('hidden')
      $payBalanceDueUseCreditBalanceInput.focus()

      // Check if credit covers the current payment amount
      if (memberCreditBalance >= remainingBalanceDue) {
        usedCreditBalance = remainingBalanceDue
        originalAmountPayment = 0
        toastr.info('Your credit balance covers your remaining balance due.')
      }
      // Check if credit covers the monthly amount
      else if (memberCreditBalance >= monthlyAmount) {
        usedCreditBalance = monthlyAmount
        originalAmountPayment = 0
        toastr.info('Your credit balance covers your monthly balance due.')
      } else {
        usedCreditBalance = Math.round(memberCreditBalance)
        originalAmountPayment = Math.round(monthlyAmount - usedCreditBalance)
        // toastr.info(
        //   `Using ₱${parseFloat(memberCreditBalance).toFixed(2)} credit.`
        // )
      }
    } else {
      $('#payBalanceDueUseCreditBalanceField').addClass('hidden')
      $payBalanceDueAmountInput.focus()
      usedCreditBalance = 0
      originalAmountPayment = Math.round(monthlyAmount)
    }
    finalAmountPayment = usedCreditBalance + originalAmountPayment

    $payBalanceDueUseCreditBalanceInput.val(usedCreditBalance)
    $payBalanceDueAmountInput.val(originalAmountPayment)
    $payBalanceDueFinalTotalAmountInput.val(finalAmountPayment)

    //await displayAmortizationsByStatus()
  })

  const debouncedUseCreditBalanceHandler = debounce(function () {
    const inputValue = $(this).val()
    let numericValue = inputValue.replace(/\D/g, '')
    numericValue = numericValue.replace(/^0+/, '')

    const valueAsInt = numericValue === '' ? 0 : parseInt(numericValue)
    const maxUsableCredit = parseFloat(memberCreditBalance)
    const monthlyAmount =
      parseFloat($payBalanceDueMonthlyAmountInput.val()) || 0
    const remainingBalanceDue =
      parseFloat($payBalanceDueRemainingBalanceInput.val()) || 0

    let creditToUse = valueAsInt

    // check if request credit is mas malaki sa current credit niya
    if (creditToUse > maxUsableCredit) {
      creditToUse = Math.round(maxUsableCredit)
      toastr.warning(`Maximum usable credit is ₱${maxUsableCredit.toFixed(2)}`)
    }
    //check if credit input mas malaki sa remaining balance
    if (creditToUse > remainingBalanceDue) {
      creditToUse = Math.round(remainingBalanceDue)
      toastr.info('Credit applied covers the remaining balance.')
    }

    $(this).val(creditToUse > 0 ? creditToUse : '')
    usedCreditBalance = creditToUse

    //ang original payment is the monthly amount minus the credit used, but not less than 0
    //ensure the original payment doesn't exceed the remaining balance minus credit used
    let calculatedOriginalPayment = Math.round(
      monthlyAmount - usedCreditBalance
    )
    if (calculatedOriginalPayment < 0) {
      calculatedOriginalPayment = 0
    }

    //kapag credit covers the whole remaining balance, original payment is 0
    if (usedCreditBalance >= remainingBalanceDue) {
      calculatedOriginalPayment = 0
    } else {
      // TODO: Ensure the total payment doesn't exceed the remaining balance
      const maxOriginalPayment = Math.round(
        remainingBalanceDue - usedCreditBalance
      )
      if (calculatedOriginalPayment > maxOriginalPayment) {
        calculatedOriginalPayment = maxOriginalPayment
      }
    }

    originalAmountPayment = calculatedOriginalPayment
    finalAmountPayment = usedCreditBalance + originalAmountPayment

    $payBalanceDueAmountInput.val(originalAmountPayment)
    $payBalanceDueFinalTotalAmountInput.val(finalAmountPayment)
  }, 300)

  $payBalanceDueUseCreditBalanceInput.on(
    'input',
    debouncedUseCreditBalanceHandler
  )

  const debouncedToPayAmountHandler = debounce(function () {
    const inputValue = $(this).val()
    let numericValue = inputValue.replace(/\D/g, '')
    numericValue = numericValue.replace(/^0+/, '')

    const isCreditChecked = $payBalanceDueCreditBalanceCheckbox.is(':checked')
    const remainingBalanceDue =
      parseFloat($payBalanceDueRemainingBalanceInput.val()) || 0
    const monthlyAmount =
      parseFloat($payBalanceDueMonthlyAmountInput.val()) || 0

    const currentUsedCredit = isCreditChecked
      ? parseFloat($payBalanceDueUseCreditBalanceInput.val()) || 0
      : 0

    let valueAsInt = numericValue === '' ? 0 : parseInt(numericValue)

    const minAmount = Math.round(monthlyAmount)
    //apply minimum only if kapag not empty and less than minAmount
    // Exception: If remaining balance is less than monthly amount, allow paying less than monthly
    if (
      numericValue !== '' &&
      valueAsInt < minAmount &&
      remainingBalanceDue >= minAmount
    ) {
      valueAsInt = minAmount
      numericValue = minAmount.toString()
      toastr.info(`Minimum payment amount is ₱${minAmount.toFixed(2)}`)
    }

    if (isCreditChecked) {
      //if using credit, the original payment cannot make the total exceed the remaining balance
      const maxAllowedOriginalPayment = Math.round(
        remainingBalanceDue - currentUsedCredit
      )

      if (valueAsInt > maxAllowedOriginalPayment) {
        valueAsInt = maxAllowedOriginalPayment
        numericValue = valueAsInt.toString()
        toastr.warning(
          `Total payment cannot exceed remaining balance when using credit.`
        )
      }
    }

    //update the input field with the validated/adjusted value
    $(this).val(valueAsInt > 0 ? numericValue : '')

    originalAmountPayment = valueAsInt
    usedCreditBalance = currentUsedCredit
    finalAmountPayment = originalAmountPayment + usedCreditBalance

    $payBalanceDueFinalTotalAmountInput.val(finalAmountPayment)

    // TODO-Note: Removed the previous credit re-evaluation logic from here as it's handled by the other handler.
  }, 300)

  $payBalanceDueAmountInput.on('input', debouncedToPayAmountHandler)

  $('#payFullBalanceBtn').on('click', function () {
    const remainingBalance = parseFloat(
      $payBalanceDueRemainingBalanceInput.val()
    )

    $payBalanceDueAmountInput.val(Math.round(remainingBalance)).focus()
    $payBalanceDueAmountInput.trigger('input')
  })

  async function processPayment(createCredit) {
    try {
      $('#payBalanceDueSubmitBtn').text('Processing...').prop('disabled', true)

      const currentOriginalPayment =
        parseFloat($payBalanceDueAmountInput.val()) || 0
      const currentCreditUsed = $payBalanceDueCreditBalanceCheckbox.is(
        ':checked'
      )
        ? parseFloat($payBalanceDueUseCreditBalanceInput.val()) || 0
        : 0

      if (
        originalAmountPayment !== currentOriginalPayment ||
        usedCreditBalance !== currentCreditUsed
      ) {
        console.warn(
          'Something went wrong: Mismatch between input values and global payment variables. Using global variables.'
        )
      }

      const paymentData = {
        member_id: selectedMemberId,
        amortization_id: currentAmortizationId,
        used_credit_balance: usedCreditBalance,
        original_amount_payment: originalAmountPayment,
        final_amount_payment: finalAmountPayment,
        payment_method: $payBalanceDuePaymentMethodSelect.val(),
        payment_date: $payBalanceDuePaymentDateInput.val(),
        notes: $payBalanceDueNotesInput.val(),
        is_use_credit: $payBalanceDueCreditBalanceCheckbox.is(':checked'),
        is_create_credit: createCredit
      }

      // console.log('Processing Payment Data:', paymentData)
      // return

      const response = await processAmortizationPayment(paymentData)
      if (response.success) {
        toastr.success(response.message)
        swalBase.close()
        closeModal('#payBalanceDueModal')
        await displayAmortizationsByStatus()
        // memberCreditBalance = response.newCreditBalance || memberCreditBalance;
      } else {
        toastr.error(response.message || 'Payment processing failed.')
        swalBase.close()
      }
      return response
    } catch (error) {
      console.error('Error processing payment:', error)
      toastr.error('An unexpected error occurred during payment processing.')
      swalBase.close()
      return { success: false, message: 'Client-side error during payment.' }
    } finally {
      $('#payBalanceDueSubmitBtn').text('Submit').prop('disabled', false)
    }
  }

  // Payment submission handler
  $('#payBalanceDueSubmitBtn').on('click', async function () {
    const finalPayment = finalAmountPayment
    const remainingBalance = parseFloat(
      $payBalanceDueRemainingBalanceInput.val()
    )

    if (isNaN(finalPayment) || finalPayment <= 0) {
      // Check originalAmountPayment specifically if final is 0 due to only credit being used
      if (
        originalAmountPayment <= 0 &&
        usedCreditBalance > 0 &&
        finalPayment === usedCreditBalance
      ) {
        // Allow processing if only credit is used and covers the amount
      } else {
        toastr.warning(
          'Please ensure a valid payment amount is entered or credit is applied.'
        )
        return
      }
    }

    if (isEmpty($payBalanceDueNotesInput.val())) {
      toastr.warning('Please enter notes for this payment.')
      return
    }

    // Check if the final calculated payment exceeds remaining balance
    if (finalPayment > remainingBalance) {
      let overpaymentAmount = finalPayment - remainingBalance

      swalBase.fire({
        title: 'Excess Payment',
        html: `<small>Your total payment (Payment: ₱${originalAmountPayment.toFixed(
          2
        )} + Credit Used: ₱${usedCreditBalance.toFixed(
          2
        )}) of <strong>₱${finalPayment.toFixed(
          2
        )}</strong> exceeds the remaining balance <strong>₱${remainingBalance.toFixed(
          2
        )}</strong> by ₱${overpaymentAmount.toFixed(
          2
        )}.</small><br><br><small>How would you like to proceed?</small>`,
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: 'Cancel Payment',
        showDenyButton: true,
        denyButtonText: 'Pay Exact Balance',
        confirmButtonText: 'Create Credit for Future Loans',
        showLoaderOnConfirm: true,
        showLoaderOnDeny: true,
        allowEscapeKey: false,
        allowOutsideClick: false,
        preConfirm: function () {
          return processPayment(true)
            .then((result) => {
              if (!result || !result.success) {
                swalBase.showValidationMessage(
                  `Payment failed: ${result.message || 'Unknown error'}`
                )
                return false
              }
              return result
            })
            .catch((err) => {
              swalBase.showValidationMessage(`Request failed: ${err}`)
              return false
            })
        },
        preDeny: function () {
          originalAmountPayment = Math.max(
            0,
            Math.round(remainingBalance - usedCreditBalance)
          )
          finalAmountPayment = originalAmountPayment + usedCreditBalance

          $payBalanceDueAmountInput.val(originalAmountPayment)
          $payBalanceDueFinalTotalAmountInput.val(finalAmountPayment)

          return processPayment(false)
            .then((result) => {
              if (!result || !result.success) {
                swalBase.showValidationMessage(
                  `Payment failed: ${result.message || 'Unknown error'}`
                )
                return false
              }
              return result
            })
            .catch((err) => {
              swalBase.showValidationMessage(`Request failed: ${err}`)
              return false
            })
        },
        allowOutsideClick: () => !swalBase.isLoading()
      })
    } else {
      // Normal payment (or exact payment)
      swalBase.fire({
        title: 'Process Payment',
        html: `<small>Confirm payment of <strong>₱${finalPayment.toFixed(
          2
        )}</strong>?<br>(Payment: ₱${originalAmountPayment.toFixed(
          2
        )}, Credit Used: ₱${usedCreditBalance.toFixed(2)})</small>`,
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        showConfirmButton: true,
        confirmButtonText: 'Confirm Payment',
        showLoaderOnConfirm: true,
        allowEscapeKey: false,
        allowOutsideClick: false,
        preConfirm: function () {
          return processPayment(false)
            .then((result) => {
              if (!result || !result.success) {
                swalBase.showValidationMessage(
                  `Payment failed: ${result.message || 'Unknown error'}`
                )
                return false
              }
              return result
            })
            .catch((err) => {
              swalBase.showValidationMessage(`Request failed: ${err}`)
              return false
            })
        },
        allowOutsideClick: () => !swalBase.isLoading()
      })
    }
  })

  $('#payBalanceDueCloseModalBtn').on('click', function () {
    closeModal('#payBalanceDueModal')
  })

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

  const $createInvoiceMemberSelect = $('#createInvoiceMember')
  const $amortizationTypeSelect = $('#requestAmortizationType')
  const $amortizationTermMonthsInput = $('#requestAmortizationTermMonths')
  const $amortizationAmountInput = $('#requestAmortizationAmount')
  const $amortizationTotalRepaymentInput = $(
    '#requestAmortizationTotalRepayment'
  )
  const $amortizationMonthlyPaymentInput = $(
    '#requestAmortizationMonthlyPayment'
  )
  const $amortizationStartDateInput = $('#requestAmortizationStartDate')
  const $amortizationEndDateInput = $('#requestAmortizationEndDate')

  const displayMembersSelection = async function () {
    try {
      const members = await fetchMembersByCriteria(`${LOAN}`, `${ACTIVE}`)
      if (members.success) {
        console.log(members.data)

        $createInvoiceMemberSelect.empty()
        $createInvoiceMemberSelect.append(
          '<option value="">Select a member...</option>'
        )

        members.data.forEach((member) => {
          $createInvoiceMemberSelect.append(
            `<option value="${member.member_id}">${member.full_name} (${member.member_uid})</option>`
          )
        })

        $createInvoiceMemberSelect.select2({
          placeholder: 'Select a member...',
          allowClear: true,
          width: '100%',
          dropdownCssClass: 'custom-select2-dropdown',
          dropdownParent: $('#requestAmortizationModal')
          //theme: 'bootstrap-5'
        })
      }
    } catch (error) {
      console.log(error)
    }
  }

  const displayAmortizationTypesSelection = async function () {
    try {
      const amortizationTypes = await fetchAmortizationTypes()
      if (amortizationTypes.success) {
        $amortizationTypeSelect.empty()
        $amortizationTypeSelect.append('<option value="" selected></option>')
        amortizationTypes.data.forEach((amortizationType) => {
          $amortizationTypeSelect.append(
            `<option value="${amortizationType.type_id}">${amortizationType.type_name}</option>`
          )
        })

        $('#requestAmortizationSubmitBtn').removeClass('hidden')
        $('#requestAmortizationUpdateBtn').addClass('hidden')
        $amortizationTypeSelect.prop('disabled', false)

        $amortizationTypeSelect.on('change', async function () {
          const amortizationTypeId = $(this).val()
          const amortizationTypeName = $(this).find(':selected').text()

          if (isEmpty(amortizationTypeId)) {
            resetFormRequestAmortization()
            return
          } else {
            const amortizationType = await fetchAmortizationType(
              amortizationTypeId
            )
            if (amortizationType.success) {
              // console.log(amortizationType.data)
              $('.more-about').removeClass('hidden')
              $('#requestAmortizationTypeUI').val(amortizationTypeName)
              $('#selectedRequestAmortizationTypeName').text(
                amortizationTypeName
              )
              $('#selectedRequestAmortizationDescription').text(
                amortizationType.data.description
              )
              $('#selectedRequestAmortizationInterestRate').text(
                amortizationType.data.interest_rate
              )
              $('#selectedRequestAmortizationTermMonths').text(
                amortizationType.data.term_months
              )
              $('#selectedRequestAmortizationMinimumAmount').text(
                amortizationType.data.minimum_amount
              )
              $('#selectedRequestAmortizationMaximumAmount').text(
                amortizationType.data.maximum_amount
              )

              $amortizationTermMonthsInput.val(
                amortizationType.data.term_months
              )
              $amortizationAmountInput
                .val(parseInt(amortizationType.data.minimum_amount))
                .prop('readonly', false)

              //date to day
              $amortizationStartDateInput
                .val(new Date().toISOString().slice(0, 10))
                .prop('readonly', false)

              // $amortizationAmountInput.val('').prop('readonly', true)

              amortizationSummaryCalculation()
            }
          }
        })
      }
    } catch (error) {
      console.log(error)
    }
  }

  function resetFormRequestAmortization() {
    $createInvoiceMemberSelect.val('').trigger('change')
    $('.more-about').addClass('hidden')
    $('#requestAmortizationTypeUI').val('')
    $amortizationTypeSelect.val('')
    $('#termMonthsCheck').prop('checked', false)
    $amortizationTermMonthsInput.val('').prop('readonly', true)
    $amortizationAmountInput.val('').prop('readonly', true)
    $amortizationTotalRepaymentInput.val('')
    $amortizationMonthlyPaymentInput.val('')
    $amortizationStartDateInput.val('').prop('readonly', true)
    $amortizationEndDateInput.val('')
  }

  $('#loanApplicationBtn').click(async function () {
    await Promise.all([
      displayMembersSelection(),
      displayAmortizationTypesSelection()
    ])
    openModal('#requestAmortizationModal')
  })

  $('#termMonthsCheck').on('change', function () {
    if ($(this).is(':checked')) {
      $amortizationTermMonthsInput.prop('readonly', false)
      $amortizationTermMonthsInput.focus()
    } else {
      const selectedTypeId = $amortizationTypeSelect.val()
      if (selectedTypeId) {
        //const selectedOption = $amortizationTypeSelect.find(':selected')
        const termMonths = $('#selectedRequestAmortizationTermMonths').text()
        $amortizationTermMonthsInput.val(termMonths)
      }

      $amortizationTermMonthsInput.prop('readonly', true)
    }
  })

  const debouncedTermMonthsHandler = debounce(function () {
    let value = $(this).val().replace(/\D/g, '')
    value = value.replace(/^0+/, '')
    //if (value === '') value = '0'

    const minTermMonths = 3
    const maxTermMonths = parseInt(
      $('#selectedRequestAmortizationTermMonths').text()
    )

    if (parseInt(value) < minTermMonths) value = minTermMonths.toString()
    if (parseInt(value) > maxTermMonths) value = maxTermMonths.toString()

    $(this).val(value)
    amortizationSummaryCalculation()
  }, 500)

  $amortizationTermMonthsInput.on('input', debouncedTermMonthsHandler)

  const debouncedRequestAmountHandler = debounce(function () {
    const inputValue = $(this).val()
    let numericValue = inputValue.replace(/\D/g, '')
    numericValue = numericValue.replace(/^0+/, '')
    //if (numericValue === '') numericValue = '0'

    const minAmount = parseInt(
      $('#selectedRequestAmortizationMinimumAmount').text()
    )
    const maxAmount = parseInt(
      $('#selectedRequestAmortizationMaximumAmount').text()
    )
    if (parseInt(numericValue) < minAmount) numericValue = minAmount.toString()
    if (parseInt(numericValue) > maxAmount) numericValue = maxAmount.toString()

    $(this).val(numericValue)

    amortizationSummaryCalculation()
  }, 500)

  $amortizationAmountInput.on('input', debouncedRequestAmountHandler)

  const amortizationSummaryCalculation = function () {
    const termMonths = parseInt($amortizationTermMonthsInput.val())
    const principal = parseFloat($amortizationAmountInput.val())
    const startDate = $amortizationStartDateInput.val()
    const interestRate = parseFloat(
      $('#selectedRequestAmortizationInterestRate').text()
    )

    if (
      isEmpty(principal) ||
      isEmpty(termMonths) ||
      isNaN(principal) ||
      isNaN(termMonths)
    )
      return

    //total interest for the loan period
    const totalInterest = principal * ((interestRate / 100) * (termMonths / 12))
    // $total_interest = $principal * (($interest_rate / 100) * ($term_months / 12))

    //total amount to be repaid (this will be the initial remaining_balance)
    const totalRepayment = principal + totalInterest
    // $total_repayment = $principal + $total_interest

    const monthlyPayment = totalRepayment / termMonths
    // $monthly_amount = $total_repayment / $term_months

    //calculate end date (add termMonths to startDate)
    let endDate = ''
    if (startDate && !isNaN(termMonths)) {
      const dateObj = new Date(startDate)
      dateObj.setMonth(dateObj.getMonth() + termMonths)
      endDate = dateObj.toISOString().slice(0, 10) //YYYY-MM-DD
      $amortizationEndDateInput.val(endDate)
    }
    //$end_date = date('Y-m-d', strtotime($start_date . " +{$term_months} months"));

    // console.log('totalInterest', totalInterest)
    // console.log('totalRepayment', totalRepayment)
    // console.log('monthlyPayment', monthlyPayment)
    // console.log('endDate', endDate)
    $amortizationTotalRepaymentInput.val(totalRepayment.toFixed(2))
    $amortizationMonthlyPaymentInput.val(monthlyPayment.toFixed(2))
  }

  $amortizationStartDateInput.on('change', amortizationSummaryCalculation)

  $('#requestAmortizationCloseModalBtn').click(function () {
    closeModal('#requestAmortizationModal')
    resetFormRequestAmortization()
  })

  $('#requestAmortizationSubmitBtn').on('click', async function () {
    const memberId = $createInvoiceMemberSelect.val()
    const amortizationTypeId = $amortizationTypeSelect.val()
    const principalAmount = $amortizationAmountInput.val()
    const monthlyAmount = $amortizationMonthlyPaymentInput.val()
    const remainingBalance = $amortizationTotalRepaymentInput.val()
    const termMonths = $amortizationTermMonthsInput.val()
    const startDate = $amortizationStartDateInput.val()
    const endDate = $amortizationEndDateInput.val()

    if (
      isEmpty(memberId) ||
      isEmpty(amortizationTypeId) ||
      isEmpty(principalAmount) ||
      isEmpty(monthlyAmount) ||
      isEmpty(remainingBalance) ||
      isEmpty(startDate) ||
      isEmpty(endDate)
    ) {
      toastr.warning('Please fill out all required fields.')
      return
    }

    const data = {
      member_id: memberId,
      type_id: amortizationTypeId,
      principal_amount: principalAmount,
      monthly_amount: monthlyAmount,
      remaining_balance: remainingBalance,
      start_date: startDate,
      end_date: endDate,
      term_months: termMonths,
      purpose: 'internal'
    }
    // console.table(data)
    // return
    try {
      $(this).text('Processing...').prop('disabled', true)
      const response = await createMemberAmortization(data)
      if (response.success) {
        toastr.success(response.message)
        await displayAmortizationsByStatus()
        closeModal('#requestAmortizationModal')
        resetFormRequestAmortization()
      }
    } catch (error) {
      console.error('Error submitting request amortization:', error)
    } finally {
      $(this).text('Submit').prop('disabled', false)
    }
  })

  $('#manageLoanTypesBtn').click(async function () {
    addURLParams('tab', 'approved')
    addURLParams('manage', 'types')
    // addURLParams('for', 'types')
    // setHashFragment('types')
    await handlePageContent()
  })

  $('#backToAmortizationListBtn2').click(async function () {
    removeURLParams('tab')
    removeURLParams('manage')
    // removeURLParams('for')
    // removeHashFragment()
    await handlePageContent()
  })

  const $loanTypesTable = $('#loanTypesTable')
  window.displayAmortizationTypes = async function () {
    try {
      const amortizationTypes = await fetchAmortizationTypes()
      if (amortizationTypes.success) {
        DataTableAmortizationTypes($loanTypesTable, amortizationTypes.data)
      }
    } catch (error) {
      console.log(error)
    }
  }

  const $tableContainerElements = $('.tableContainerContent')
  const $formContainerElements = $('.formContainerContent')
  const $modeBreadCrumb = $('#modeBreadCrumb')

  async function handlePageContent() {
    const tab = getURLParams('tab')
    const mode = getURLParams('mode')
    const forAccount = getURLParams('for')
    const hashFragment = getHashFragment()

    const manage = getURLParams('manage')
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
    } else if (tab === 'approved' && manage === 'types') {
      $('.amortizationsContent').addClass('hidden')
      $('.typesContent').removeClass('hidden')
      $('#amortizationPanelTitle').text('Amortization (Loan) Types Management:')
    } else {
      $('.amortizationContent').removeClass('hidden')
      $('.paymentsLogsContent').addClass('hidden')
      $('.paymentsHistoryContent').addClass('hidden')
      $('.amortilizationTitle').text('List of amortizations approved:')

      $('.amortizationsContent').removeClass('hidden')
      $('.typesContent').addClass('hidden')
      $('#amortizationPanelTitle').text('Amortizations (Loan) Management:')
      // $tableContainerElements.removeClass('hidden')
      // $formContainerElements.addClass('hidden')
      // $modeBreadCrumb.text('/')

      removeURLParams('tab')
      removeURLParams('mode')
      removeURLParams('for')
      removeHashFragment()
    }
  }

  await handlePageContent()
})
