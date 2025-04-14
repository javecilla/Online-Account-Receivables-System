window.DataTableAmortizationPayments = function ($paymentsTable, data) {
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
        title: 'Referrence Number'
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
    order: [[5, 'desc']]
  })

  return paymentsDataTable
}

window.DataTableAmortizationsByStatus = function ($amortizationsTable, data) {
  if ($.fn.DataTable.isDataTable($amortizationsTable)) {
    $amortizationsTable.DataTable().destroy()
    $loanRequestsTable.empty()
  }

  let amortizationsDataTable = $($amortizationsTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'No amortization records found',
      zeroRecords: 'No matching amortizations found',
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
        data: 'full_name',
        title: 'Member Name'
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
        data: 'monthly_amount',
        title: 'Monthly Amount',
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
          switch (data) {
            case 'active':
              typeClass = 'status-active'
              icon = 'fas fa-info-circle'
              break
            case 'completed':
              typeClass = 'as-completed'
              icon = 'fas fa-check-circle'
              break
            case 'defaulted':
              typeClass = 'as-defaulted'
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
        data: null,
        title: 'Actions',
        orderable: false,
        render: function (data) {
          return `
            <div class="d-flex">
              <button class="btn btn-sm action-btn view-btn" data-id="${data.member_id}">
                <i class="fas fa-eye"></i> View
              </button>
            </div>
          `
        }
      },
      {
        data: 'updated_at',
        visible: false // Hide the column
      }
    ],
    order: [[10, 'desc']]
  })

  return amortizationsDataTable
}

window.DataTableAmortizationsByApproval = function ($loanRequestsTable, data) {
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
        data: 'full_name',
        title: 'Member Name'
      },
      {
        data: 'current_balance',
        title: 'Current Balance',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
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
          const isRejected = data.approval === 'rejected'
          const approvedVisibility = isRejected ? 'd-none' : ''
          const revertVisibility = isRejected ? '' : 'd-none'

          return `
            <div class="d-flex">
              <button class="btn btn-sm action-btn approved-btn ${approvedVisibility}" data-id="${data.amortization_id}" data-approval="approved">
                <i class="fas fa-check-circle"></i> Approved
              </button>
              <button class="btn btn-sm action-btn reject-btn ${approvedVisibility}" data-id="${data.amortization_id}" data-approval="rejected">
                <i class="fas fa-minus-circle"></i> Reject
              </button>

              <button class="btn btn-sm action-btn revert-btn ${revertVisibility}" data-id="${data.amortization_id}" data-approval="pending">
                <i class="fas fa-undo"></i> Revert
              </button>
              <button class="btn btn-sm action-btn delete-btn ${revertVisibility}" data-id="${data.amortization_id}">
                <i class="fas fa-trash"></i> Delete
              </button>
            </div>
          `
        }
      },
      {
        data: 'created_at',
        visible: false
      }
    ],
    order: [[8, 'desc']]
  })

  return amortizationsDataTable
}
