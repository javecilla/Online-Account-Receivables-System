const $memberAmortilizationTitle = $('.memberAmortilizationTitle')

const $memberApprovedAmortizationsListAction = $(
  '.memberApprovedAmortizationsListAction'
)
const $memberRequestAmortizationsListAction = $(
  '.memberRequestAmortizationsListAction'
)
const $memberAmortizationPaymentsListAction = $(
  '.memberAmortizationPaymentsListAction'
)

const $memberApprovedAmortizationsListContent = $(
  '#memberApprovedAmortizationsListContent'
)
const $memberRequestAmortizationsListContent = $(
  '#memberRequestAmortizationsListContent'
)
const $memberAmortizationPaymentsListContent = $(
  '#memberAmortizationPaymentsListContent'
)

const $memberApprovedAmortizationsTable = $('#memberApprovedAmortizationsTable')
const $memberRequestAmortizationsTable = $('#memberRequestAmortizationsTable')
const $memberAmortizationPaymentsTable = $('#memberAmortizationPaymentsTable')

$(document).ready(async function () {
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

  //TODO: MEMBER APPROVED AMORTIZATIONS > PAYMENTS HISTORY TABLE
  window.displayMemberApprovedAmortizations = async function () {
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
      '.pay-balance-btn',
      async function () {
        $('#payBalanceDueModalLabel').text($(this).data('title'))

        currentAmortizationId = $(this).data('id')
        const remainingBalance = $(this).data('remaining-balance')
        const totalPaid = $(this).data('total-paid')
        const monthlyAmount = $(this).data('monthly-amount')

        $payBalanceDueRemainingBalanceInput.val(
          parseFloat(remainingBalance).toFixed(2)
        )
        $payBalanceDueMonthlyAmountInput.val(
          parseFloat(monthlyAmount).toFixed(2)
        )
        $payBalanceDueTotalPaidInput.val(parseFloat(totalPaid).toFixed(2))

        // Initialize credit input based on available credit
        $payBalanceDueUseCreditBalanceInput.val(Math.round(memberCreditBalance))
        $payBalanceDueCurrentCreditBalanceText.text(
          parseFloat(memberCreditBalance).toFixed(2)
        )

        // --- Initial Amount Setting ---
        usedCreditBalance = 0
        originalAmountPayment = Math.round(monthlyAmount)
        finalAmountPayment = usedCreditBalance + originalAmountPayment
        // Set the main payment amount initially to the monthly amount
        $payBalanceDueAmountInput.val(originalAmountPayment)
        $payBalanceDueFinalTotalAmountInput.val(finalAmountPayment)

        $payBalanceDuePaymentDateInput.val(
          new Date().toISOString().split('T')[0]
        )

        // --- Reset credit state ---
        $payBalanceDueCreditBalanceCheckbox.prop('checked', false) // Start unchecked
        $('#payBalanceDueUseCreditBalanceField').addClass('hidden') // Hide credit input field
        $payBalanceDueAmountInput.prop('readonly', false) // Ensure amount input is editable
        // --- End Reset credit state ---

        openModal('#payBalanceDueModal')
      }
    )

    $memberApprovedAmortizationsTable.on(
      'click',
      '.payment-history-btn',
      async function () {
        const amortizationId = $(this).data('id')
        const amortizationTypeName = $(this).data('at-name')

        addURLParams('tab', 'amortizations')
        addURLParams('amortization_id', amortizationId)
        addURLParams('amortization_type', amortizationTypeName)
        setHashFragment('payment-history')

        // await displayMemberAmortizationPaymentsTable(amortizationId)
        await handlePageContent()
      }
    )
  }

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

    await displayMemberDetails()
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

    // Original payment is the monthly amount minus the credit used, but not less than 0
    // Also, ensure the original payment doesn't exceed the remaining balance minus credit used
    let calculatedOriginalPayment = Math.round(
      monthlyAmount - usedCreditBalance
    )
    if (calculatedOriginalPayment < 0) {
      calculatedOriginalPayment = 0
    }

    // If credit covers the whole remaining balance, original payment is 0
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

    // Update the payment amount and final total fields
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
    // Get current used credit only if checkbox is checked
    const currentUsedCredit = isCreditChecked
      ? parseFloat($payBalanceDueUseCreditBalanceInput.val()) || 0
      : 0

    let valueAsInt = numericValue === '' ? 0 : parseInt(numericValue)

    const minAmount = Math.round(monthlyAmount)
    //apply minimum only if not empty and less than minAmount
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
    // Removed 'amount' parameter
    try {
      $('#payBalanceDueSubmitBtn').text('Processing...').prop('disabled', true)

      // Ensure global variables are up-to-date before creating paymentData
      // (They should be, due to debounced handlers, but good practice)
      const currentOriginalPayment =
        parseFloat($payBalanceDueAmountInput.val()) || 0
      const currentCreditUsed = $payBalanceDueCreditBalanceCheckbox.is(
        ':checked'
      )
        ? parseFloat($payBalanceDueUseCreditBalanceInput.val()) || 0
        : 0
      // Use the validated global variables if they match, otherwise log a warning
      // This is a safety check, ideally the global vars are the source of truth
      if (
        originalAmountPayment !== currentOriginalPayment ||
        usedCreditBalance !== currentCreditUsed
      ) {
        console.warn(
          'Mismatch between input values and global payment variables. Using global variables.'
        )
        // Optionally force update global vars here if needed, but debouncers should handle it.
      }

      const paymentData = {
        member_id: memberId,
        amortization_id: currentAmortizationId,
        // Use the global variables directly
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
      // return // Keep this commented out for actual execution

      const response = await processAmortizationPayment(paymentData)
      if (response.success) {
        toastr.success(response.message)
        swalBase.close()
        closeModal('#payBalanceDueModal')
        // Refresh data
        await displayMemberApprovedAmortizations()
        // Optionally update memberCreditBalance if the API returns the new balance
        // memberCreditBalance = response.newCreditBalance || memberCreditBalance;
      } else {
        // Handle API error response (e.g., show specific error message)
        toastr.error(response.message || 'Payment processing failed.')
        swalBase.close() // Close swal even on failure unless you want it open
      }
      return response // Return the response object
    } catch (error) {
      console.error('Error processing payment:', error)
      toastr.error('An unexpected error occurred during payment processing.')
      swalBase.close() // Ensure swal closes on unexpected errors
      // Do not re-throw here unless necessary, let the function complete
      return { success: false, message: 'Client-side error during payment.' } // Return a failure object
    } finally {
      $('#payBalanceDueSubmitBtn').text('Submit').prop('disabled', false)
    }
  }

  // Payment submission handler
  $('#payBalanceDueSubmitBtn').on('click', async function () {
    // Use the final calculated total payment from the global variable
    const finalPayment = finalAmountPayment
    const remainingBalance = parseFloat(
      $payBalanceDueRemainingBalanceInput.val()
    )

    // Validate based on the final calculated amount
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
        // Use global variables for display
        html: `<small>Your total payment (Payment: ₱${originalAmountPayment.toFixed(
          2 // Use global originalAmountPayment
        )} + Credit Used: ₱${usedCreditBalance.toFixed(
          // Use global usedCreditBalance
          2
        )}) of <strong>₱${finalPayment.toFixed(
          // Use global finalAmountPayment
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
          // No need for response check here
          // Call processPayment directly, it uses global vars
          // Pass true to create credit
          return processPayment(true)
            .then((result) => {
              if (!result || !result.success) {
                // Prevent Swal from closing automatically on failure
                swalBase.showValidationMessage(
                  `Payment failed: ${result.message || 'Unknown error'}`
                )
                return false // Keep Swal open
              }
              return result // Allow Swal to close on success
            })
            .catch((err) => {
              swalBase.showValidationMessage(`Request failed: ${err}`)
              return false // Keep Swal open on catch
            })
        },
        preDeny: function () {
          // Adjust global variables to pay exact amount before calling processPayment
          originalAmountPayment = Math.max(
            0,
            Math.round(remainingBalance - usedCreditBalance)
          )
          finalAmountPayment = originalAmountPayment + usedCreditBalance

          // Update inputs to reflect the change (optional but good UX)
          $payBalanceDueAmountInput.val(originalAmountPayment)
          $payBalanceDueFinalTotalAmountInput.val(finalAmountPayment)

          // Call processPayment with adjusted globals
          // Pass false to not create credit
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
        // Use global variables for display confirmation
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
          // No need for response check here
          // Call processPayment directly, pass false for createCredit
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

  //TODO: MEMBER AMORTIZATION PAYMENTS TABLE
  window.displayMemberAmortizationPaymentsTable = async function (
    amortizationId
  ) {
    try {
      LoadingManager.show($mainContent)
      const payments = await fetchMemberAmortizationPayments(amortizationId)
      if (payments.success) {
        //console.log(payments.data)
        DataTableMemberAmortizationPayments(
          $memberAmortizationPaymentsTable,
          payments.data
        )
      }
      LoadingManager.hide($mainContent)

      $memberAmortizationPaymentsTable.on('click', '.notes-btn', function () {
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
      //$memberAmortizationPaymentsTable.on('click', '.invoice-btn', function () {})
    } catch (error) {
      console.error('Error fetching member amortization payments:', error)
    }
  }

  $memberApprovedAmortizationsListAction.on(
    'click',
    '.application-btn',
    async function () {
      addURLParams('tab', 'amortizations')
      setHashFragment('application')

      await handlePageContent()
    }
  )

  $memberRequestAmortizationsListAction.on(
    'click',
    '.back-btn',
    async function () {
      removeURLParams('tab')
      removeHashFragment()
      await handlePageContent()
    }
  )

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

  function resetFormRequestAmortization() {
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

  $memberRequestAmortizationsListAction.on(
    'click',
    '.request-btn',
    async function () {
      //toastr.info('clicked...')
      try {
        LoadingManager.show($('.request-amortization'))
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
        console.error('Error fetching amortization types:', error)
      } finally {
        LoadingManager.hide($('.request-amortization'))
        $('#requestAmortizationModalLabel').html(
          `<strong>${$(this).data('title')}</strong>`
        )

        openModal('#requestAmortizationModal')
      }
    }
  )

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

  $('#requestAmortizationCloseModalBtn').on('click', function () {
    resetFormRequestAmortization()
    closeModal('#requestAmortizationModal')
  })

  $('#requestAmortizationSubmitBtn').on('click', async function () {
    const amortizationTypeId = $amortizationTypeSelect.val()
    const principalAmount = $amortizationAmountInput.val()
    const monthlyAmount = $amortizationMonthlyPaymentInput.val()
    const remainingBalance = $amortizationTotalRepaymentInput.val()
    const termMonths = $amortizationTermMonthsInput.val()
    const startDate = $amortizationStartDateInput.val()
    const endDate = $amortizationEndDateInput.val()
    //validate fields
    if (
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
      term_months: termMonths
    }
    //console.table(data)
    try {
      $(this).text('Processing...').prop('disabled', true)
      const response = await createMemberAmortization(data)
      if (response.success) {
        toastr.success(response.message)
        await displayMemberRequestAmortizations()
        closeModal('#requestAmortizationModal')
        resetFormRequestAmortization()
      }
    } catch (error) {
      console.error('Error submitting request amortization:', error)
    } finally {
      $(this).text('Submit').prop('disabled', false)
    }
  })

  //TODO: MEMBER REQUEST AMORTIZATIONS tABLE
  window.displayMemberRequestAmortizations = async function () {
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

    $memberRequestAmortizationsTable.on(
      'click',
      '.edit-btn',
      async function () {
        const amortizationId = $(this).data('id')
        //toastr.info(`Amortization ID: ${amortizationId}`)
        try {
          LoadingManager.show($('.request-amortization'))
          const amortization = await fetchAmortization(amortizationId)
          if (amortization.success) {
            //console.log(amortization.data)
            $('#requestAmortizationSubmitBtn').addClass('hidden')
            $('#requestAmortizationUpdateBtn').removeClass('hidden')

            openModal('#requestAmortizationModal')
            $('#requestAmortizationModalLabel').html(
              '<strong>Update Request Amortization</strong>'
            )

            const amortizationType = await fetchAmortizationType(
              amortization.data.type_id
            )
            if (amortizationType.success) {
              // console.log(amortizationType.data)
              $amortizationTypeSelect
                .data('type-id', amortization.data.type_id)
                .data('amortization-id', amortizationId)
                .prop('disabled', true)

              $('.more-about').removeClass('hidden')
              $('#requestAmortizationTypeUI').val(amortization.data.type_name)
              $('#selectedRequestAmortizationTypeName').text(
                amortization.data.type_name
              )
              $('#selectedRequestAmortizationDescription').text(
                amortization.data.description
              )
              $('#selectedRequestAmortizationInterestRate').text(
                amortization.data.interest_rate
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

              $('#termMonthsCheck').prop('checked', true)
              //calculate term months base on the start and end date
              const startDate = new Date(amortization.data.start_date)
              const endDate = new Date(amortization.data.end_date)
              const termMonths =
                (endDate.getFullYear() - startDate.getFullYear()) * 12 +
                (endDate.getMonth() - startDate.getMonth())
              $amortizationTermMonthsInput
                .val(termMonths)
                .prop('readonly', false)

              $amortizationAmountInput
                .val(parseInt(amortization.data.principal_amount))
                .prop('readonly', false)

              $amortizationTotalRepaymentInput.val(
                amortization.data.remaining_balance
              )
              $amortizationMonthlyPaymentInput.val(
                amortization.data.monthly_amount
              )

              $amortizationStartDateInput
                .val(amortization.data.start_date)
                .prop('readonly', false)

              $amortizationEndDateInput.val(amortization.data.end_date)
            }
          }
        } catch (error) {
          console.error('Error fetching member request amortization:', error)
        } finally {
          LoadingManager.hide($('.request-amortization'))
        }
      }
    )

    $memberRequestAmortizationsTable.on(
      'click',
      '.delete-btn',
      async function () {
        const amortizationId = $(this).data('id')
        //toastr.info(`Amortization ID: ${amortizationId}`)
        swalBase.fire({
          title: 'Delete Confirmation',
          html: '<small>Are you sure you want to delete this loan request record?</small>',
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
              return new Promise(async function (resolve) {
                setTimeout(async function () {
                  // toastr.success('delete testing: ' + amortizationId)
                  try {
                    const res = await deleteAmortization(amortizationId)
                    if (res.success) {
                      toastr.success(res.message)
                      await displayMemberRequestAmortizations()
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
            !swalBase.isLoading()
          }
        })
      }
    )
  }

  $('#requestAmortizationUpdateBtn').on('click', async function () {
    const principalAmount = $amortizationAmountInput.val()
    const monthlyAmount = $amortizationMonthlyPaymentInput.val()
    const remainingBalance = $amortizationTotalRepaymentInput.val()
    const termMonths = $amortizationTermMonthsInput.val()
    const startDate = $amortizationStartDateInput.val()
    const endDate = $amortizationEndDateInput.val()

    const data = {
      //amortization_id: $amortizationTypeSelect.data('amortization-id'),
      member_id: memberId,
      type_id: $amortizationTypeSelect.data('type-id'),
      principal_amount: principalAmount,
      monthly_amount: monthlyAmount,
      remaining_balance: remainingBalance,
      start_date: startDate,
      end_date: endDate,
      term_months: termMonths
    }
    //console.table(data)
    try {
      $(this).text('Processing...').prop('disabled', true)
      const response = await updateMemberAmortization(
        $amortizationTypeSelect.data('amortization-id'),
        data
      )
      if (response.success) {
        toastr.success(response.message)
        await displayMemberRequestAmortizations()
        //closeModal('#requestAmortizationModal')
        // resetFormRequestAmortization()
      }
    } catch (error) {
      console.error('Error submitting request amortization:', error)
    } finally {
      $(this).text('Update').prop('disabled', false)
    }
  })

  $memberAmortizationPaymentsListAction.on(
    'click',
    '.back-btn',
    async function () {
      //toastr.info('clicked')
      removeURLParams('tab')
      removeURLParams('amortization_type')
      removeURLParams('amortization_id')
      removeHashFragment()
      await handlePageContent()
    }
  )

  $('#requestAmortizationUpdateBtn').on('click', async function () {
    const principalAmount = $amortizationAmountInput.val()
    const monthlyAmount = $amortizationMonthlyPaymentInput.val()
    const remainingBalance = $amortizationTotalRepaymentInput.val()
    const termMonths = $amortizationTermMonthsInput.val()
    const startDate = $amortizationStartDateInput.val()
    const endDate = $amortizationEndDateInput.val()

    const data = {
      //amortization_id: $amortizationTypeSelect.data('amortization-id'),
      member_id: memberId,
      type_id: $amortizationTypeSelect.data('type-id'),
      principal_amount: principalAmount,
      monthly_amount: monthlyAmount,
      remaining_balance: remainingBalance,
      start_date: startDate,
      end_date: endDate,
      term_months: termMonths
    }
    //console.table(data)
    try {
      $(this).text('Processing...').prop('disabled', true)
      const response = await updateMemberAmortization(
        $amortizationTypeSelect.data('amortization-id'),
        data
      )
      if (response.success) {
        toastr.success(response.message)
        await displayMemberRequestAmortizations()
        //closeModal('#requestAmortizationModal')
        // resetFormRequestAmortization()
      }
    } catch (error) {
      console.error('Error submitting request amortization:', error)
    } finally {
      $(this).text('Update').prop('disabled', false)
    }
  })

  $memberAmortizationPaymentsListAction.on(
    'click',
    '.back-btn',
    async function () {
      //toastr.info('clicked')
      removeURLParams('tab')
      removeURLParams('amortization_type')
      removeURLParams('amortization_id')
      removeHashFragment()
      await handlePageContent()
    }
  )
})
