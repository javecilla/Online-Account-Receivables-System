window.DataTableMembers = function ($membersTable, data) {
  //const $membersTable = $('#membersTable')
  if ($.fn.DataTable.isDataTable($membersTable)) {
    $membersTable.DataTable().destroy()
    $membersTable.empty()
  }

  let membersDataTable = $($membersTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'Loading members records...',
      zeroRecords: 'No matching members found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Members',
      infoEmpty: 'Showing 0 to 0 of 0 Members',
      infoFiltered: '(filtered from _MAX_ total Members)',
      search: 'Search Member:',
      lengthMenu: 'Show _MENU_ Members',
      paginate: {
        first: '<i class="fas fa-angle-double-left"></i>',
        previous: '<i class="fas fa-angle-left"></i>',
        next: '<i class="fas fa-angle-right"></i>',
        last: '<i class="fas fa-angle-double-right"></i>'
      }
    },
    columns: [
      { data: 'member_uid', title: 'Member ID' },
      { data: 'full_name', title: 'Name' },
      {
        data: 'membership_type',
        title: 'Account Type',
        render: function (data) {
          let statusClass
          switch (data) {
            case 'Savings Account':
              statusClass = 'at-savings-account'
              break
            case 'Time Deposit':
              statusClass = 'at-time-deposit'
              break
            case 'Fixed Deposit':
              statusClass = 'at-fixed-deposit'
              break
            case 'Special Savings':
              statusClass = 'at-special-savings'
              break
            case 'Youth Savings':
              statusClass = 'at-youth-savings'
              break
            case 'Loan':
              statusClass = 'at-loan'
              break
            default:
              statusClass = '' // Default class if no match is found
          }
          return `<span class="status-badge ${statusClass}">${data}</span>`
        }
      },
      {
        data: 'current_balance',
        title: 'Balance',
        render: function (data) {
          return `&#8369;${data}` //₱5000.00
        }
      },
      {
        data: 'membership_status',
        title: 'Membership Status',
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
        data: 'opened_date',
        title: 'Opened Date',
        render: function (data) {
          return moment(data).format('DD MMM YYYY')
        }
      },
      {
        data: null,
        title: 'Actions',
        orderable: false,
        render: function (data) {
          return `
            <div class="d-flex">
              <button class="btn btn-sm action-btn view-btn" data-id="${data.member_id}">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm action-btn edit-btn" 
               onclick="window.open('accounts?mode=update&for=member&account_id=${data.account_id}&member_id=${data.member_id}#internal', '_blank')">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm action-btn delete-btn" data-id="${data.member_id}">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          `
        }
      }
    ],
    order: [[5, 'desc']]
  })

  // membersDataTable.clear()
  // membersDataTable.rows.add(data)
  // membersDataTable.draw()

  return membersDataTable
}

window.DataTableMembersTransactionLogs = function ($transactionsTable, data) {
  if ($.fn.DataTable.isDataTable($transactionsTable)) {
    $transactionsTable.DataTable().destroy()
    $transactionsTable.empty()
  }

  let transactionLogs = $($transactionsTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'No transaction logs records found',
      zeroRecords: 'No matching transaction logs found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Transaction Logs',
      infoEmpty: 'Showing 0 to 0 of 0 Transaction Logs',
      infoFiltered: '(filtered from _MAX_ total Transaction Logs)',
      search: 'Search Transaction:',
      lengthMenu: 'Show _MENU_ Transaction Logs'
    },
    columns: [
      { data: 'reference_number', title: 'Reference Number' },
      { data: 'member_full_name', title: 'Name' },
      {
        data: 'transaction_type',
        title: 'Transaction Type',
        render: function (data) {
          let typeClass
          let icon
          switch (data) {
            case 'deposit':
              typeClass = 'tt-deposit'
              icon = 'fa-arrow-up'
              break
            case 'withdrawal':
              typeClass = 'tt-withdrawal'
              icon = 'fa-arrow-down'
              break
            case 'fee':
              typeClass = 'ff-fee'
              icon = 'fa-file'
              break
            case 'interest':
              typeClass = 'ff-interest'
              icon = 'fa-file'
              break
            default:
              typeClass = ''
              icon = ''
          }
          return `<span class="status-badge ${typeClass}"><i class="fas ${icon}"></i>&nbsp;${
            data.charAt(0).toUpperCase() + data.slice(1)
          }</span>`
        }
      },
      //{ data: 'payment_method', title: 'Payment Method' },
      {
        data: 'amount',
        title: 'Amount',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'previous_balance',
        title: 'Previous Balance',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'new_balance',
        title: 'New Balance',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'transaction_created_at',
        title: 'Date and Time',
        render: function (data) {
          return moment(data).format('DD MMM YYYY h:mm A') // 01 Jan 2023 12:00 AM
        }
      },
      {
        data: null,
        title: 'Action',
        orderable: false,
        render: function (data) {
          return `
            <div class="d-flex">
              <button class="btn btn-sm action-btn notes-btn" data-id="${data.transaction_id}" data-notes="${data.notes}"><i class="fas fa-file-lines"></i> Notes</button>
            </div>
          `
        }
      },
      {
        data: 'transaction_id',
        visible: false // Hide the column
      }
    ],
    order: [[8, 'desc']]
  })

  return transactionLogs
}

window.DataTableMemberAmortizations = function ($amortizationsTable, data) {
  // console.log(data)
  if ($.fn.DataTable.isDataTable($amortizationsTable)) {
    $amortizationsTable.DataTable().destroy()
    $amortizationsTable.empty()
  }

  let amortizationDataTable = $($amortizationsTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'No amortization records found',
      zeroRecords: 'No matching amortization found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Amortizations',
      infoEmpty: 'Showing 0 to 0 of 0 Amortizations',
      infoFiltered: '(filtered from _MAX_ total Amortizations)',
      search: 'Search Amortization:',
      lengthMenu: 'Show _MENU_ Amortizations'
    },
    columns: [
      {
        data: 'type_name',
        title: 'Amortization Type',
        render: function (data) {
          let statusClass
          switch (data) {
            case 'Educational Loan':
              statusClass = 'at-educational-loan'
              break
            case 'Calamity Loan':
              statusClass = 'at-calamity-loan'
              break
            case 'Business Loan':
              statusClass = 'at-business-loan'
              break
            case 'Personal Loan':
              statusClass = 'at-personal-loan'
              break
            case 'Agricultural Loan':
              statusClass = 'at-agricultural-loan'
              break
            default:
              statusClass = ''
          }
          return `<span class="status-badge ${statusClass}">${data}</span>`
        }
      },
      // {
      //   data: 'principal_amount',
      //   title: 'Principal Amount',
      //   render: function (data) {
      //     return `&#8369;${parseFloat(data).toFixed(2)}`
      //   }
      // },
      {
        data: 'remaining_balance',
        title: 'Balance Due',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'total_paid',
        title: 'Total Paid',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: null,
        title: 'Progress',
        render: function (data) {
          const startDate = moment(data.start_date)
          const endDate = moment(data.end_date)
          const currentDate = moment()
          // Calculate total months between start and end date
          const totalMonths = endDate.diff(startDate, 'months')
          const half = Math.floor(totalMonths / 2)
          // Calculate months passed since start date
          const monthsPassed = currentDate.diff(startDate, 'months')
          let typeClass =
            monthsPassed >= half ? 'status-inactive' : 'as-completed'
          let icon = monthsPassed >= half ? 'fa-arrow-down' : 'fa-arrow-up'

          // Calculate total repayment amount (principal + interest)
          const principalAmount = parseFloat(data.principal_amount) || 0
          const interestRate = parseFloat(data.interest_rate) || 0
          const interestAmount = (principalAmount * interestRate) / 100 || 0
          const totalRepayment = principalAmount + interestAmount

          // Calculate percentage based on total repayment
          const totalPaid = parseFloat(data.total_paid) || 0
          let percentagePaid = 0

          if (totalRepayment > 0) {
            percentagePaid = ((totalPaid / totalRepayment) * 100).toFixed(2)
            // Cap at 100% for display purposes
            if (percentagePaid > 100) percentagePaid = 100
          }

          return `<span class="status-badge ${typeClass}" data-bs-toggle="tooltip" title="${monthsPassed} months passed out of ${totalMonths}">
      <i class="fas ${icon}"></i> &nbsp;<span>${percentagePaid}%</span>
    </span>`
        }
      },
      {
        data: 'approval',
        title: 'Approval',
        render: function (data) {
          let typeClass
          let icon
          switch (data) {
            case 'pending':
              typeClass = 'as-pending'
              icon = 'fas fa-info-circle'
              break
            case 'approved':
              typeClass = 'as-approved'
              icon = 'fas fa-check-circle'
              break
            case 'rejected':
              typeClass = 'as-rejected'
              icon = 'fas fa-times-circle'
              break
            default:
              typeClass = ''
              icon = ''
          }
          return `<span class="status-badge ${typeClass}"><i class="fas ${icon}"></i>&nbsp;${
            data.charAt(0).toUpperCase() + data.slice(1)
          }</span>`
        }
      },
      {
        data: 'status',
        title: 'Status',
        render: function (data) {
          let typeClass
          let icon

          if (!data) {
            return '<span class="status-badge ap-others"><i class="fas fa-clock"></i>&nbsp;Defaulted</span>'
          }
          switch (data) {
            case 'paid':
              typeClass = 'as-completed'
              icon = 'fas fa-info-circle'
              break
            case 'pending':
              typeClass = 'at-loan'
              icon = 'fas fa-check-circle'
              break
            case 'overdue':
              typeClass = 'ap-others'
              icon = 'fas fa-times-circle'
              break
            default:
              typeClass = ''
              icon = ''
          }
          return `<span class="status-badge ${typeClass}"><i class="${icon}"></i>&nbsp;${
            data.charAt(0).toUpperCase() + data.slice(1)
          }</span>`
        }
      },
      {
        data: 'start_date',
        title: 'Start Date',
        render: function (data) {
          return moment(data).format('DD MMM YYYY')
        }
      },
      {
        data: 'end_date',
        title: 'End Date',
        render: function (data) {
          return moment(data).format('DD MMM YYYY')
        }
      },
      {
        data: null,
        title: 'Action',
        orderable: false,
        render: function (data) {
          const buttonAction =
            data.approval === 'pending' || data.approval === 'rejected'
              ? '<button class="btn btn-sm action-btn" title="No action is required this loan is pending." style="pointer-events: none">No action required</button>'
              : `<button class="btn btn-sm action-btn viewAmortizationPaymentsBtn" data-id="${data.amortization_id}" data-at-name="${data.type_name}"><i class="fas fa-history"></i> View Payments</button>`

          return `
            <div class="d-flex">
              ${buttonAction}
            </div>
          `
        }
      }
    ],
    order: [[6, 'desc']]
  })

  return amortizationDataTable
}

window.DataTableMemberAmortizationPayments = function ($paymentsTable, data) {
  if ($.fn.DataTable.isDataTable($paymentsTable)) {
    $paymentsTable.DataTable().destroy()
    $paymentsTable.empty()
  }

  let paymentsDataTable = $($paymentsTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'No payment records found',
      zeroRecords: 'No matching payment found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Payment',
      infoEmpty: 'Showing 0 to 0 of 0 Payment',
      infoFiltered: '(filtered from _MAX_ total Payment)',
      search: 'Search Payment:',
      lengthMenu: 'Show _MENU_ Payment'
    },
    columns: [
      {
        data: 'reference_number',
        title: 'Reference Number',
        orderable: false
      },
      {
        data: 'payment_made_by',
        title: 'Payment By'
      },
      {
        data: 'payment_method',
        title: 'Payment Method',
        render: function (data) {
          let typeClass
          let text
          switch (data) {
            case 'cash':
              typeClass = 'ap-cash'
              text = 'Cash'
              break
            case 'check':
              typeClass = 'ap-check'
              text = 'Check'
              break
            case 'bank_transfer':
              typeClass = 'ap-bank-transfer'
              text = 'Bank Transfer'
              break
            case 'online_payment':
              typeClass = 'ap-online-payment'
              text = 'Online Payment'
              break
            case 'others':
              typeClass = 'ap-others'
              text = 'Others'
              break
            default:
              typeClass = ''
              text = ''
          }
          return `<span class="status-badge ${typeClass}">${text}</span>`
        }
      },
      {
        data: 'amount',
        title: 'Amount',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },

      {
        data: 'payment_created_at',
        title: 'Payment Date',
        render: function (data) {
          return moment(data).format('DD MMM YYYY')
        }
      },
      {
        data: 'processed_by',
        title: 'Processed By'
      },
      {
        data: null,
        title: 'Action',
        orderable: false,
        render: function (data) {
          return `
            <div class="d-flex">
              <button class="btn btn-sm action-btn notes-btn" data-id="${data.amortization_id}" data-notes="${data.notes}"><i class="fas fa-file-lines"></i> Notes</button>
              <button class="btn btn-sm action-btn invoice-btn" data-id="${data.amortization_id}" style="cursor: no-drop"><i class="fas fa-file"></i> Invoice</button>
            </div>
          `
        }
      }
    ],
    order: [[4, 'desc']]
  })

  return paymentsDataTable
}

window.DataTableMemberTransactionsHistory = function (
  $transactionsTable,
  data
) {
  if ($.fn.DataTable.isDataTable($transactionsTable)) {
    $transactionsTable.DataTable().destroy()
    $transactionsTable.empty()
  }

  let transactionDataTable = $($transactionsTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'No transaction records found',
      zeroRecords: 'No matching transactions found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Transactions',
      infoEmpty: 'Showing 0 to 0 of 0 Transactions',
      infoFiltered: '(filtered from _MAX_ total Transactions)',
      search: 'Search Transaction:',
      lengthMenu: 'Show _MENU_ Transactions'
    },
    columns: [
      {
        data: 'reference_number',
        title: 'Reference Number',
        orderable: false
      },
      {
        data: 'transaction_type',
        title: 'Transaction Type',
        render: function (data) {
          let typeClass
          let icon
          switch (data) {
            case 'deposit':
              typeClass = 'tt-deposit'
              icon = 'fa-arrow-up'
              break
            case 'withdrawal':
              typeClass = 'tt-withdrawal'
              icon = 'fa-arrow-down'
              break
            case 'fee':
              typeClass = 'ff-fee'
              icon = 'fa-file'
              break
            case 'interest':
              typeClass = 'ff-interest'
              icon = 'fa-file'
              break
            default:
              typeClass = ''
              icon = ''
          }
          return `<span class="status-badge ${typeClass}"><i class="fas ${icon}"></i>&nbsp;${
            data.charAt(0).toUpperCase() + data.slice(1)
          }</span>`
        }
      },
      {
        data: 'amount',
        title: 'Amount',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'previous_balance',
        title: 'Previous Balance',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'new_balance',
        title: 'New Balance',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'transaction_created_at',
        title: 'Date and Time',
        render: function (data) {
          return moment(data).format('DD MMM YYYY h:mm A')
        }
      },
      {
        data: null,
        title: 'Action',
        orderable: false,
        render: function (data) {
          return `
            <div class="d-flex">
              <button class="btn btn-sm action-btn notes-btn" data-id="${data.transaction_id}" data-notes="${data.notes}"><i class="fas fa-file-lines"></i> Notes</button>
            </div>
          `
        }
      },
      {
        data: 'transaction_id',
        visible: false
      }
    ],
    order: [[7, 'desc']]
  })

  return transactionDataTable
}
