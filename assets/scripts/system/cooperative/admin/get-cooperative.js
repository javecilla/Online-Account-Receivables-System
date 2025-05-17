$(document).ready(async function () {
  const typeName = getURLParams('type_name')
  const typeId = getURLParams('type_id')
  const $accountHolderName = $('#accountHolderName')
  const $accountType = $('#accountType')
  const $currentBalance = $('#currentBalance')
  const $accountActions = $('#accountActions')

  const $accountSelection = $('#accountSelection')
  async function displayMembersSelection() {
    try {
      const members = await fetchCooperativeAccountsByType(
        `${typeName}`,
        `${ACTIVE}`
      )
      if (members.success) {
        console.log(members.data)

        $accountSelection.empty()
        $accountSelection.append('<option value="">Select a member...</option>')

        members.data.forEach((member) => {
          $accountSelection.append(
            `<option value="${member.caid}" 
              data-member-id="${member.member_id}"
              data-member-name="${member.full_name}" 
              data-account-type="${member.membership_type}"
              data-balance="${member.current_balance || 0}">
                ${member.full_name} (${member.member_uid})
            </option>`
          )
        })

        $accountSelection.select2({
          placeholder: 'Select a member...',
          allowClear: true,
          width: '100%',
          dropdownCssClass: 'custom-select2-dropdown'
        })
      }
    } catch (error) {
      console.log(error)
    }
  }

  // Handle account selection change
  $accountSelection.on('change', async function () {
    const caid = $(this).val()
    const selectedOption = $(this).find('option:selected')
    const memberId = selectedOption.data('member-id')

    if (caid) {
      await loadTransactionHistory(memberId)
      // Update account information
      $accountHolderName.val(selectedOption.data('member-name'))
      $accountType.val(selectedOption.data('account-type'))
      $currentBalance.val(
        `₱${parseFloat(selectedOption.data('balance')).toFixed(2)}`
      )
      $('#cbCurrentBalance').text(
        `₱${parseFloat(selectedOption.data('balance')).toFixed(2)}`
      )
      // Clear previous action buttons
      $accountActions.empty()

      // Add appropriate action buttons based on account type
      if (typeName == SAVINGS_ACCOUNT) {
        // Savings Account
        $accountActions.append(`
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="depositBtn">Deposit</button>
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="withdrawBtn">Withdraw</button>
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="checkBalanceBtn">Check Balance</button>
        `)
      } else if (typeName == TIME_DEPOSIT) {
        // Time Deposit
        $accountActions.append(`
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="depositBtn">Deposit</button>
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="checkBalanceBtn">Check Balance</button>
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="withdrawBtn">Withdraw at Maturity</button>
        `)
      } else if (typeName == FIXED_DEPOSIT) {
        // Fixed Deposit
        $accountActions.append(`
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="depositBtn">Deposit</button>
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="checkBalanceBtn">Check Balance</button>
          <button type="button" class="btn action-btn mb-2" data-caid="${caid}" data-member-id="${memberId}" id="withdrawBtn">Withdraw at Maturity</button>
        `)
      }

      //loadTransactionHistory(caid)
    } else {
      // Clear fields when no account is selected
      $accountHolderName.val('')
      $accountType.val('')
      $currentBalance.val('')
      $accountActions.empty()
    }
  })

  // Function to load transaction history
  async function loadTransactionHistory(memberId) {
    try {
      // You would implement an API call to fetch transaction history
      // For now, just clear the table
      const $transactionTable = $('.transactionTable tbody')
      const $runningBalanceCell = $('.transactionTable tfoot th:last-child')
      $transactionTable.html(
        '<tr><td colspan="5" class="text-center">Loading transactions...</td></tr>'
      )
      LoadingManager.show($('.main-content'))
      const response = await fetchTransactionsByCooperative(memberId, typeName)
      if (response.success) {
        // Clear the loading message
        $transactionTable.empty()

        // Check if there are transactions
        if (response.data && response.data.length > 0) {
          // Get the latest balance from the most recent transaction
          const latestTransaction = response.data[0] // Assuming data is sorted by date desc
          const runningBalance = parseFloat(latestTransaction.new_balance)

          // Update the running balance in the footer
          $runningBalanceCell.text(`₱${runningBalance.toFixed(2)}`)

          // Update the current balance field
          $('#currentBalance').val(`₱${runningBalance.toFixed(2)}`)
          $('#cbCurrentBalance').text(`₱${runningBalance.toFixed(2)}`)

          // Populate table with transactions
          response.data.forEach((transaction) => {
            const transactionRow = `
              <tr>
                <td>${transaction.reference_number}</td>
                <td>₱${parseFloat(transaction.amount).toFixed(2)}</td>
                <td>
                  <span class="status-badge ${getTransactionTypeClass(
                    transaction.transaction_type
                  )}">
                    <i class="fas ${getTransactionTypeIcon(
                      transaction.transaction_type
                    )}"></i>&nbsp;
                    ${capitalizeFirstLetter(transaction.transaction_type)}
                  </span>
                </td>
                <td>${moment(transaction.transaction_created_at).format(
                  'DD MMM YYYY h:mm A'
                )}</td>
                <td>${transaction.processed_by || 'System'}</td>
              </tr>
            `
            $transactionTable.append(transactionRow)
          })
        } else {
          // No transactions found
          $transactionTable.html(
            '<tr><td colspan="5" class="text-center">No transaction records found.</td></tr>'
          )
          $runningBalanceCell.text('₱0.00')
        }
      }
    } catch (error) {
      console.error('Error loading transaction history:', error)
      const $transactionTable = $('.transactionTable tbody')
      $transactionTable.html(
        '<tr><td colspan="5" class="text-center">Error loading transactions. Please try again.</td></tr>'
      )
    } finally {
      LoadingManager.hide($('.main-content'))
    }
  }

  // Helper functions for transaction display
  function getTransactionTypeClass(type) {
    switch (type.toLowerCase()) {
      case 'deposit':
        return 'tt-deposit'
      case 'withdrawal':
        return 'tt-withdrawal'
      case 'fee':
        return 'tt-fee'
      case 'interest':
        return 'tt-interest'
      default:
        return ''
    }
  }

  function getTransactionTypeIcon(type) {
    switch (type.toLowerCase()) {
      case 'deposit':
        return 'fa-arrow-up'
      case 'withdrawal':
        return 'fa-arrow-down'
      case 'fee':
        return 'fa-file'
      case 'interest':
        return 'fa-percentage'
      default:
        return 'fa-exchange-alt'
    }
  }

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1)
  }

  $('.input-amount').on('input', function () {
    const inputValue = $(this).val()
    const numericValue = inputValue.replace(/[^0-9.]/g, '')
    $(this).val(numericValue)
  })

  // Event delegation for action buttons
  $accountActions.on('click', '#depositBtn', function () {
    const caid = $(this).data('caid')
    $(this).addClass('active')
    $('#withdrawBtn').removeClass('active')
    $('#checkBalanceBtn').removeClass('active')
    // Implement deposit functionality
    console.log('Deposit for account', caid)
    // Open deposit modal or form
    $('#depositForm').show()
    $('#withdrawForm').hide()
    $('#checkBalanceForm').hide()

    $('#daCaid').val(caid)
    $('#daMemberId').val($(this).data('member-id'))
    $('#daAmountInput').focus()
  })

  $accountActions.on('click', '#withdrawBtn', function () {
    const caid = $(this).data('caid')
    $(this).addClass('active')
    $('#depositBtn').removeClass('active')
    $('#checkBalanceBtn').removeClass('active')
    // Implement withdraw functionality
    console.log('Withdraw for account', caid)
    // Open withdraw modal or form
    $('#depositForm').hide()
    $('#withdrawForm').show()
    $('#checkBalanceForm').hide()

    $('#waCaid').val(caid)
    $('#waMemberId').val($(this).data('member-id'))
    $('#waAmountInput').focus()
  })

  $accountActions.on('click', '#checkBalanceBtn', function () {
    const caid = $(this).data('caid')
    $(this).addClass('active')
    $('#depositBtn').removeClass('active')
    $('#withdrawBtn').removeClass('active')
    // Implement check balance functionality
    console.log('Check balance for account', caid)
    // Show detailed balance information
    $('#depositForm').hide()
    $('#withdrawForm').hide()
    $('#checkBalanceForm').show()
  })

  $('#daSubmitBtn').click(async function () {
    const caid = $('#daCaid').val()
    const memberId = $('#daMemberId').val()
    const amount = $('#daAmountInput').val()

    if (isEmpty(caid) || isEmpty(memberId)) {
      toastr.error('An error occurred. Missing Account')
      return
    }

    if (isEmpty(amount)) {
      toastr.warning('Please input an amount to deposit.')
      $('#daAmountInput').focus()
      return
    }

    try {
      $(this).text('Processing...').prop('disabled', true)
      const response = await deposit({
        member_id: memberId,
        caid: caid,
        amount: amount
      })
      if (response.success) {
        console.log(response)
        toastr.success(response.message)
        $('#daAmountInput').val('')
        $currentBalance.val(response.data.new_balance)
        //refresh transaction history
        await loadTransactionHistory(memberId)
      } else {
        toastr.error(response.message)
      }
    } catch (e) {
      console.log(e)
    } finally {
      $(this).text('Submit').prop('disabled', false)
    }
  })

  $('#waSubmitBtn').click(async function () {
    const caid = $('#waCaid').val()
    const memberId = $('#waMemberId').val()
    const amount = $('#waAmountInput').val()
    if (isEmpty(caid) || isEmpty(memberId)) {
      toastr.error('An error occurred. Missing Account')
      return
    }
    if (isEmpty(amount)) {
      toastr.warning('Please input an amount to withdraw.')
      $('#waAmountInput').focus()
      return
    }
    try {
      $(this).text('Processing...').prop('disabled', true)
      const response = await withdraw({
        member_id: memberId,
        caid: caid,
        amount: amount
      })
      if (response.success) {
        console.log(response)
        toastr.success(response.message)
        $('#waAmountInput').val('')
        $currentBalance.val(response.data.new_balance)
        //refresh transaction history
        await loadTransactionHistory(memberId)
      }
    } catch (e) {
      console.log(e)
    } finally {
      $(this).text('Submit').prop('disabled', false)
    }
  })

  async function initializedPage() {
    try {
      LoadingManager.show($('.main-content'))

      await Promise.all([displayMembersSelection()])
    } catch (e) {
      console.log(e)
    } finally {
      LoadingManager.hide($('.main-content'))
    }
  }

  await initializedPage()
})
