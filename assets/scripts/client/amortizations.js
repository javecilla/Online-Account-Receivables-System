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
    $amortizationTermMonthsInput.val('')
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

  const debouncedAmountHandler = debounce(function () {
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

  $amortizationAmountInput.on('input', debouncedAmountHandler)

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
              return new Promise(function (resolve) {
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
            !submitForm.isLoading()
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
})
