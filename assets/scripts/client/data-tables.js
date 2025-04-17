// amortization tabs
window.DataTableMemberApprovedAmortizations = function (
  $amortizationsTable,
  data
) {
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
      {
        data: 'principal_amount',
        title: 'Principal Amount',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
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
            monthsPassed >= half ? 'status-inactive' : 'status-approved'
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
        data: 'status',
        title: 'Status',
        render: function (data) {
          let typeClass
          let icon

          if (!data) {
            return '<span class="status-badge at-loan"><i class="fas fa-clock"></i>&nbsp;Pending</span>'
          }
          switch (data) {
            case 'pending':
              typeClass = 'at-loan'
              icon = 'fas fa-info-circle'
              break
            case 'paid':
              typeClass = 'as-completed'
              icon = 'fas fa-check-circle'
              break
            case 'overdue':
              typeClass = 'as-defaulted'
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
        title: 'Actions',
        orderable: false,
        render: function (data) {
          let actionButtons = ''

          if (data.status === 'pending') {
            actionButtons += `<li><a class="dropdown-item pay-balance-btn" href="javascript:void(0)" 
              data-id="${data.amortization_id}"
              data-remaining-balance="${data.remaining_balance}"
              data-monthly-amount="${data.monthly_amount}"
              data-total-paid="${data.total_paid}" data-title="Pay Balance Due for ${data.type_name}"><strong>₱</strong> Pay Balance Due</a></li>
              <li><hr class="dropdown-divider"></li>
            `
          }

          actionButtons += `<li><a class="dropdown-item payment-history-btn"  data-id="${data.amortization_id}" data-at-name="${data.type_name}" href="javascript:void(0)"><i class="fas fa-history"></i> Payment History</a></li>`
          return `
            <div class="dropdown" id="approvedAmortizationActionDropdown">
              <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-1"></i><i class="fas fa-chevron-down" style="font-size: 8px; margin-bottom: 2px"></i>
              </button>
              <ul class="dropdown-menu profile-menu" aria-labelledby="approvedAmortizationActionDropdown">
                ${actionButtons}
              </ul>
            </div>
          `
        }
      }
    ],
    order: [[6, 'desc']]
  })

  return amortizationDataTable
}

window.DataTableMemberRequestAmortizations = function (
  $loanRequestsTable,
  data
) {
  if ($.fn.DataTable.isDataTable($loanRequestsTable)) {
    $loanRequestsTable.DataTable().destroy()
    $loanRequestsTable.empty()
  }

  let amortizationsDataTable = $($loanRequestsTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'No loan request records found',
      zeroRecords: 'No matching loan request found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Amortization',
      infoEmpty: 'Showing 0 to 0 of 0 Amortization',
      infoFiltered: '(filtered from _MAX_ total Amortization)',
      search: 'Search Amortization:',
      lengthMenu: 'Show _MENU_ Amortization'
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
      {
        data: 'principal_amount',
        title: 'Principal Amount',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'interest_rate',
        title: 'Interest Rate',
        render: function (data) {
          return `${parseFloat(data).toFixed(2)}%`
        }
      },
      {
        data: 'monthly_amount',
        title: 'Monthly Amount',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'remaining_balance',
        title: 'Total Repayment',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
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
              typeClass = 'status-pending'
              icon = 'fas fa-info-circle'
              break
            case 'approved':
              typeClass = 'status-approved'
              icon = 'fas fa-check-circle'
              break
            case 'rejected':
              typeClass = 'status-rejected'
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
        title: 'Actions',
        orderable: false,
        render: function (data) {
          let actionButtons = ''

          if (data.approval === 'pending') {
            actionButtons += `<li><a class="dropdown-item edit-btn" href="javascript:void(0)" data-id="${data.amortization_id}"><i class="fas fa-edit"></i> Edit Request</a></li>
              <li><hr class="dropdown-divider"></li>
            `
          }

          actionButtons += `<li><a class="dropdown-item delete-btn" href="javascript:void(0)" data-id="${data.amortization_id}"><i class="fas fa-trash"></i> Cancel / Delete</a></li>`
          return `
            <div class="dropdown" id="requestAmortizationActionDropdown">
              <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-1"></i><i class="fas fa-chevron-down" style="font-size: 8px; margin-bottom: 2px"></i>
              </button>
              <ul class="dropdown-menu profile-menu" aria-labelledby="requestAmortizationActionDropdown">
                ${actionButtons}
              </ul>
            </div>
          `
        }
      },
      {
        data: 'created_at',
        visible: false
      }
    ],
    order: [[9, 'desc']]
  })

  return amortizationsDataTable
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
        title: 'Actions',
        orderable: false,
        render: function (data) {
          return `
            <div class="dropdown" id="requestAmortizationActionDropdown">
              <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-1"></i><i class="fas fa-chevron-down" style="font-size: 8px; margin-bottom: 2px"></i>
              </button>
              <ul class="dropdown-menu profile-menu" aria-labelledby="requestAmortizationActionDropdown">
                <li><a class="dropdown-item notes-btn" href="javascript:void(0)" data-id="${data.amortization_id}" data-notes="${data.notes}"><i class="fas fa-file-lines"></i> Notes</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item invoice-btn" href="javascript:void(0)" data-id="${data.amortization_id}" style="cursor: no-drop"><i class="fas fa-file-invoice"></i> Invoice</a></li>
              </ul>
            </div>
          `
        }
      }
    ],
    order: [[4, 'desc']]
  })

  return paymentsDataTable
}

// transaction tab
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
