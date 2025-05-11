$(document).ready(async function () {
  //console.log('test')

  $('#createInvoiceFormBtn').click(async function () {
    addURLParams('mode', 'create')
    setHashFragment('invoice')
    await handlePageContent()
  })

  $('.backToInvoiceListBtn').click(async function () {
    removeURLParams('mode')
    removeHashFragment()
    await handlePageContent()
  })

  const today = new Date()
  const year = today.getFullYear()
  const month = String(today.getMonth() + 1).padStart(2, '0')
  const day = String(today.getDate()).padStart(2, '0')
  $('#invoiceDateToDay').text(`${year}${month}${day}`)
  $('#invoiceNumberLastDigit').val(Math.floor(1000 + Math.random() * 9000))

  //set default due date (30 days from today)
  function setDefaultDueDate() {
    const defaultDueDate = new Date()
    defaultDueDate.setDate(defaultDueDate.getDate() + 30)
    return formatDate(defaultDueDate)
  }
  $('#generatedRecurringInvoices').empty().addClass('hidden')

  const $createInvoiceMemberSelect = $('#createInvoiceMember')
  const $createInvoiceTypeSelect = $('#createInvoiceType')
  const $createInvoiceRecurringPeriodSelect = $('#createInvoiceRecurringPeriod')
  const $createInvoiceNumberInput = $('#createInvoiceNumber')
  const $overrideInvoiceNumberCheck = $('#overrideInvoiceNumberCheck')

  $createInvoiceTypeSelect.on('change', function () {
    //const selectedType = $(this).val()
    const selectedTypeText = $(this).find('option:selected').text()
    if (selectedTypeText === 'Recurring') {
      $('#recurringPeriodSelectionContainer').removeClass('hidden')
      $('#invoiceType').text('REC')
      $('#recurringAmountNote').removeClass('hidden')
      if (!$('#createInvoiceDueDate').val()) {
        $('#createInvoiceDueDate').val(setDefaultDueDate())
      }

      // If due date and recurring period are already set
      const dueDate = $('#createInvoiceDueDate').val()
      const period = $createInvoiceRecurringPeriodSelect
        .find('option:selected')
        .text()
      if (dueDate && period && period !== '') {
        //console.log('update the recurring invoices display')
        updateRecurringInvoicesDisplay(dueDate, period)
      } else {
        //console.log('Hide the recurring invoices display if period is not selected')
        $('#generatedRecurringInvoices').empty().addClass('hidden')
      }
    } else {
      $('#recurringPeriodSelectionContainer').addClass('hidden')
      $('#invoiceType').text('INV')
      $createInvoiceRecurringPeriodSelect.val('')
      $('#createInvoiceRecurringPeriodUI').val('')
      $('#recurringAmountNote').addClass('hidden')
      $('#generatedRecurringInvoices').empty().addClass('hidden')
    }

    $('#createInvoiceTypeUI').val(selectedTypeText)
  })

  //generate future dates based on recurring period and initial due date
  function generateFutureDates(initialDate, period, count = 3) {
    const dates = []
    let currentDate = new Date(initialDate)

    for (let i = 0; i < count; i++) {
      let nextDate = new Date(currentDate)

      switch (period) {
        case 'Monthly':
          nextDate.setMonth(nextDate.getMonth() + (i + 1))
          break
        case 'Quarterly':
          nextDate.setMonth(nextDate.getMonth() + (i + 1) * 3)
          break
        case 'Annually':
          nextDate.setFullYear(nextDate.getFullYear() + (i + 1))
          break
      }

      dates.push(nextDate)
    }

    return dates
  }

  // Get the current invoice count setting or use default
  function getInvoicePreviewCount() {
    const count = parseInt($('#invoicePreviewCount').val()) || 3
    // Ensure the count is between 1 and 12
    return Math.min(Math.max(count, 1), 12)
  }

  // Validate invoice preview count input
  $(document).on('input', '#invoicePreviewCount', function () {
    let value = $(this).val()
    // Remove non-numeric characters
    value = value.replace(/[^0-9]/g, '')
    // Ensure value is between 1 and 12
    if (value !== '') {
      const numValue = parseInt(value)
      if (numValue < 1) value = '1'
      if (numValue > 12) value = '12'
    }
    $(this).val(value)
  })

  function formatDate(date) {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
  }

  //update the recurring invoices display
  function updateRecurringInvoicesDisplay(initialDate, period) {
    if (!initialDate || !period) {
      $('#generatedRecurringInvoices').empty().addClass('hidden')
      return
    }

    // Get the current invoice preview count setting
    const previewCount = getInvoicePreviewCount()
    const futureDates = generateFutureDates(
      initialDate,
      period,
      previewCount - 1
    )
    const $container = $('#generatedRecurringInvoices')

    $container.empty().removeClass('hidden')

    $container.append(`<h6 class="mt-3 mb-2 fw-bold text-secondary">Generated Recurring Invoices:</h6>
      <p><small>Note: <em>For recurring invoices, the full amount will be applied to each generated invoice. The amount is not divided among the invoices.</em></small></p>
      <div class="mb-3">
        <label for="invoicePreviewCount" class="form-label">Number of future invoices to preview:</label>
        <div class="d-flex align-items-center">
          <input type="number" id="invoicePreviewCount" class="form-control form-control-sm" style="width: 80px" min="1" max="12" value="${previewCount}">
          <button id="updateInvoicePreviewBtn" class="btn btn-sm action-btn ms-2">Update</button>
        </div>
      </div>
    `)

    const $invoiceList = $('<div class="recurring-invoice-list"></div>')
    $container.append($invoiceList)

    //initial invoice (always checked and enabled)
    $invoiceList.append(`
      <div class="recurring-invoice-item active">
        <button class="action-btn">
          <i class="fas fa-file-invoice"></i> <strong>(1) ${formatDate(
            new Date(initialDate)
          )}</strong> - Initial Invoice
        </button>
        <div class="radio-container"><input type="radio" name="recurringInvoice" checked/></div>
      </div>
    `)

    //mga future invoices (disabled by default)
    futureDates.forEach((date, index) => {
      const periodText =
        period === 'Monthly'
          ? 'month'
          : period === 'Quarterly'
          ? 'quarter'
          : 'year'
      $invoiceList.append(`
        <div class="recurring-invoice-item">
          <button class="action-btn">
            <i class="fas fa-file-invoice"></i> <strong>(${
              index + 2
            }) ${formatDate(date)}</strong> - Next ${periodText}
          </button>
          <div class="radio-container"><input type="radio" name="recurringInvoice" disabled/></div>
        </div>
      `)
    })
  }

  $('#createInvoiceDueDate').on('change', function () {
    if ($createInvoiceTypeSelect.val() === '1') {
      const dueDate = $(this).val()
      const period = $createInvoiceRecurringPeriodSelect
        .find('option:selected')
        .text()

      if (dueDate && period) {
        updateRecurringInvoicesDisplay(dueDate, period)
      }
    }
  })

  // Event delegation for the update invoice preview button
  $(document).on('click', '#updateInvoicePreviewBtn', function () {
    if ($createInvoiceTypeSelect.val() === '1') {
      const dueDate = $('#createInvoiceDueDate').val()
      const period = $createInvoiceRecurringPeriodSelect
        .find('option:selected')
        .text()

      if (dueDate && period) {
        updateRecurringInvoicesDisplay(dueDate, period)
      }
    }
  })

  $createInvoiceRecurringPeriodSelect.on('change', function () {
    const selectedPeriodText = $(this).find('option:selected').text()
    $('#createInvoiceRecurringPeriodUI').val(selectedPeriodText)

    const dueDate = $('#createInvoiceDueDate').val()
    if (dueDate) {
      updateRecurringInvoicesDisplay(dueDate, selectedPeriodText)
    }
  })

  $overrideInvoiceNumberCheck.on('change', function () {
    if ($overrideInvoiceNumberCheck.is(':checked')) {
      $('#invoiceNumberLastDigit').prop('readonly', false).focus()
    } else {
      $('#invoiceNumberLastDigit').prop('readonly', true)
    }
  })

  $('#invoiceNumberLastDigit').on('input', function () {
    const value = $(this).val()
    const numericValue = value.replace(/\D/g, '')
    const truncatedValue = numericValue.slice(0, 4)
    $(this).val(truncatedValue)
  })

  async function handlePageContent() {
    const mode = getURLParams('mode')
    const hash = getHashFragment()

    if (mode === 'create' && hash === 'invoice') {
      $('.tableContainerContent').addClass('hidden')
      $('.formContainerContent').removeClass('hidden')
      //await displayMembersSelection()

      // Reset form fields when opening the form
      resetInvoiceForm()
    } else {
      $('.tableContainerContent').removeClass('hidden')
      $('.formContainerContent').addClass('hidden')
    }
  }

  // Function to reset the invoice form
  function resetInvoiceForm() {
    // Reset invoice type to Regular
    $createInvoiceTypeSelect.val('0')
    $('#createInvoiceTypeUI').val('Regular')
    $('#invoiceType').text('INV')

    // Hide recurring period selection
    $('#recurringPeriodSelectionContainer').addClass('hidden')
    $createInvoiceRecurringPeriodSelect.val('')
    $('#createInvoiceRecurringPeriodUI').val('')

    // Hide recurring notes and display
    $('#recurringAmountNote').addClass('hidden')
    $('#generatedRecurringInvoices').empty().addClass('hidden')

    // Clear due date field - will be set if user changes to Recurring
    $('#createInvoiceDueDate').val('')

    // Reset other form fields as needed
    $('#createInvoiceAmount').val('')
    $('#createInvoiceDescription').val('')

    // Reset invoice preview count to default (3)
    if ($('#invoicePreviewCount').length) {
      $('#invoicePreviewCount').val('3')
    }
  }

  window.displayMembersSelection = async function () {
    try {
      const members = await fetchMembersByCriteria(
        `${SAVINGS_ACCOUNT},${TIME_DEPOSIT},${FIXED_DEPOSIT},${SPECIAL_SAVINGS},${YOUTH_SAVINGS},${LOAN}`,
        `${ACTIVE}`
      )
      if (members.success) {
        //console.log(members.data)

        $createInvoiceMemberSelect.empty()
        $createInvoiceMemberSelect.append(
          '<option value="">Select a member...</option>'
        )

        members.data.forEach((member) => {
          $createInvoiceMemberSelect.append(
            `<option value="${member.member_id}">${member.full_name} (${member.email})</option>`
          )
        })

        $createInvoiceMemberSelect.select2({
          placeholder: 'Select a member...',
          allowClear: true,
          width: '100%',
          dropdownCssClass: 'custom-select2-dropdown'
          //theme: 'bootstrap-5'
        })
      }
    } catch (error) {
      console.log(error)
    }
  }

  await handlePageContent()
})
