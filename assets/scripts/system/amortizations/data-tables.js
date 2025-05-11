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
            <div class="dropdown" id="requestAmortizationActionDropdown">
              <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-1"></i><i class="fas fa-chevron-down" style="font-size: 8px; margin-bottom: 2px"></i>
              </button>
              <ul class="dropdown-menu profile-menu" aria-labelledby="requestAmortizationActionDropdown">
                <li><a class="dropdown-item notes-btn" href="javascript:void(0)" data-id="${data.amortization_id}" data-notes="${data.notes}"><i class="fas fa-file-lines"></i> View Payment Notes</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)" data-id="${data.amortization_id}"><i class="fas fa-file-image"></i> Prof of Payments</a></li>
              </ul>
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
  //console.log(data)
  if ($.fn.DataTable.isDataTable($amortizationsTable)) {
    $amortizationsTable.DataTable().destroy()
    $amortizationsTable.empty()
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
        title: 'Member'
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
      // {
      //   data: 'monthly_amount',
      //   title: 'Monthly Amount',
      //   render: function (data) {
      //     return `&#8369;${parseFloat(data).toFixed(2)}`
      //   }
      // },
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
          //data-bs-toggle="tooltip" title="${monthsPassed} months passed out of ${totalMonths}"
          return `<span class="status-badge ${typeClass}">
      <i class="fas ${icon}"></i> &nbsp;<span>${percentagePaid}%</span>
    </span>`
        }
      },
      {
        data: null, // Calculated from other data
        title: 'Next Due',
        orderable: true, // Usually calculated columns aren't easily sortable
        render: function (data, type, row) {
          // Check if loan is already paid or term ended
          if (row.status === 'paid' || row.status === 'completed') {
            // Added completed check
            return '<span class="status-badge as-completed">Paid</span>'
          }
          const currentDate = moment()
          const startDate = moment(row.start_date)
          const endDate = moment(row.end_date)

          // --- Calculate the theoretical next due date ---
          const paymentDay = startDate.date()

          // Check if loan start date is in the future
          if (startDate.isAfter(currentDate)) {
            // For future loans, the first payment is due on the start date
            const daysUntilStart = startDate.diff(currentDate, 'days')
            return `<span class="status-badge status-active" data-bs-toggle="tooltip" title="Loan starts on ${startDate.format(
              'DD MMM YYYY'
            )}">Starts in ${daysUntilStart} days</span>`
          }

          let theoreticalNextDueDate = currentDate.clone().date(paymentDay)

          // If this month's payment day has already passed, the *next* theoretical due date is next month
          // But for overdue calculation, we might need the *last* missed one.
          // Let's calculate the due date for the *current* cycle first.
          if (theoreticalNextDueDate.isBefore(currentDate, 'day')) {
            // If today is past this month's payment day, the *next* due date is next month
            theoreticalNextDueDate.add(1, 'month')
          }
          // Ensure calculated next due date is not after the end date
          if (theoreticalNextDueDate.isAfter(endDate)) {
            theoreticalNextDueDate = endDate
          }
          // --- End theoretical next due date calculation ---

          // --- Handle Term End separately ---
          // Check if the loan term has ended, even if not marked 'paid'
          if (currentDate.isAfter(endDate)) {
            if (row.status === 'overdue') {
              // Calculate months overdue since the end date
              const monthsOverdueSinceEnd = currentDate.diff(endDate, 'months')
              const overdueText =
                monthsOverdueSinceEnd > 0
                  ? `Overdue by ${monthsOverdueSinceEnd} month${
                      monthsOverdueSinceEnd > 1 ? 's' : ''
                    }`
                  : 'Overdue' // If less than a month past end date
              return `<span class="status-badge as-defaulted" data-bs-toggle="tooltip" title="Loan term ended ${endDate.format(
                'DD MMM YYYY'
              )}">${overdueText}</span>`
            }
            // If term ended but status isn't 'paid' or 'overdue', show Term Ended
            return `<span class="status-badge status-inactive">Term Ended</span>`
          }
          // --- End Term End Handling ---

          const daysUntilDue = theoreticalNextDueDate.diff(currentDate, 'days')

          // Display based on days remaining or overdue status
          const dueSoonThreshold = 7
          let badgeClass = 'status-active' // Default style (e.g., 'status-info' or your active style)
          let text = `Due in ${daysUntilDue} days`
          let tooltipDate = theoreticalNextDueDate.format('DD MMM YYYY')
          let tooltipText = `Next Payment: ${tooltipDate}`

          if (row.status === 'overdue') {
            badgeClass = 'as-defaulted' // Overdue style

            // Calculate the *first* missed due date
            // This requires iterating back from the theoreticalNextDueDate until we find the first one
            // that is before the current date.
            let firstMissedDueDate = theoreticalNextDueDate.clone()
            while (firstMissedDueDate.isSameOrAfter(currentDate, 'day')) {
              firstMissedDueDate.subtract(1, 'month')
              // Adjust day if month subtraction changed it (e.g., Feb 30 -> Feb 28)
              firstMissedDueDate.date(paymentDay)
              // Safety break if something goes wrong, or if it goes before start date
              if (firstMissedDueDate.isBefore(startDate)) break
            }
            // If the loop went too far back (before start date), use the start date's first payment cycle due date
            if (firstMissedDueDate.isBefore(startDate)) {
              firstMissedDueDate = startDate.clone().date(paymentDay)
              if (firstMissedDueDate.isBefore(startDate)) {
                // If start date itself is late in month
                firstMissedDueDate.add(1, 'month')
              }
            }

            const monthsOverdue = currentDate.diff(firstMissedDueDate, 'months')

            if (monthsOverdue > 0) {
              text = `Overdue by ${monthsOverdue} month${
                monthsOverdue > 1 ? 's' : ''
              }`
              tooltipText = `First missed payment likely around ${firstMissedDueDate.format(
                'DD MMM YYYY'
              )}`
            } else {
              // If overdue but less than a full month, show simpler message
              text = 'Overdue'
              tooltipText = `Payment was due on/before ${firstMissedDueDate.format(
                'DD MMM YYYY'
              )}`
            }
          } else if (daysUntilDue <= 0) {
            // Due today (but status is not 'overdue' yet)
            // Check if the loan was created today (start date is today)
            if (startDate.isSame(currentDate, 'day')) {
              // For loans created today, show when the first payment is due (typically one month later)
              const firstDueDate = startDate
                .clone()
                .add(1, 'month')
                .date(paymentDay)
              badgeClass = 'status-active' // Use a more neutral style for new loans
              text = `First Due ${firstDueDate.format('DD MMM')}`
              tooltipText = `First payment due on ${firstDueDate.format(
                'DD MMM YYYY'
              )}`
            } else {
              // Regular due today case
              badgeClass = 'as-defaulted' // Use overdue style or a specific 'due-today' style
              text = 'Due Today'
              tooltipText = `Payment due today: ${tooltipDate}`
            }
          } else if (daysUntilDue <= dueSoonThreshold) {
            // Due soon
            badgeClass = 'status-pending' // Warning style (or your 'due-soon' style)
            text = `Due in ${daysUntilDue} days`
            tooltipText = `Next Payment: ${tooltipDate}`
          }
          // Else (due later than threshold), the default text and badgeClass are used.

          return `<span class="status-badge ${badgeClass}" data-bs-toggle="tooltip" title="${tooltipText}">${text}</span>`
        }
      },
      {
        data: 'status',
        title: 'Status',
        render: function (data) {
          //console.log(data)
          let typeClass
          let icon
          switch (data) {
            case 'paid':
              typeClass = 'as-completed'
              icon = 'fas fa-info-circle'
              break
            case 'pending':
              typeClass = 'as-dilaw'
              icon = 'fas fa-check-circle'
              break
            case 'overdue':
              typeClass = 'as-defaulted'
              icon = 'fas fa-clock'
              break
            case 'defaulted':
            default:
              typeClass = 'as-defaulted'
              icon = 'fas fa-times-circle'
              break
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
            <div class="dropdown" id="requestAmortizationActionDropdown">
              <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-1"></i><i class="fas fa-chevron-down" style="font-size: 8px; margin-bottom: 2px"></i>
              </button>
              <ul class="dropdown-menu profile-menu" aria-labelledby="requestAmortizationActionDropdown">
                <li><a class="dropdown-item view-details-btn" href="javascript:void(0)" data-amortization-id="${data.amortization_id}"><i class="fas fa-eye me-1"></i> View Details</a></li>
                <li><a class="dropdown-item update-status-btn" href="javascript:void(0)" data-amortization-id="${data.amortization_id}" data-amortization-status="${data.status}" data-member-id="${data.member_id}" data-email="${data.email}" data-full-name="${data.full_name}"><i class="fas fa-edit me-1"></i> Update Status</a></li>
                <li><a class="dropdown-item send-reminder-btn" href="javascript:void(0)" data-amortization-id="${data.amortization_id}" data-member-id="${data.member_id}" data-email="${data.email}" data-full-name="${data.full_name}"><i class="fas fa-bell me-1"></i> Notify Member</a></li>
                <li><a class="dropdown-item pay-balance-btn" href="javascript:void(0)" 
                  data-id="${data.amortization_id}"
                  data-member-id="${data.member_id}"
                  data-remaining-balance="${data.remaining_balance}"
                  data-monthly-amount="${data.monthly_amount}"
                  data-current-balance="${data.current_balance}"
                  data-credit-balance="${data.credit_balance}"
                  data-total-paid="${data.total_paid}" 
                  data-title="Pay '${data.full_name}' Balance Due for '${data.type_name}'"><strong class="me-2">₱</strong> Pay Balance Due</a></li>
                <li>
                <li><a class="dropdown-item view-payments-history-btn" href="javascript:void(0)" data-amortization-id="${data.amortization_id}" data-amortization-type-name="${data.type_name}" data-member-id="${data.member_id}"><i class="fas fa-history me-1"></i> Payments History</a></li>
              </ul>
            </div>
          `

          /*
            <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item mark-overdue-btn" href="javascript:void(0)" data-id="${data.amortization_id}"><i class="fas fa-clock me-1"></i> Mark Overdue</a></li>
          */
        }
      },
      {
        data: 'updated_at',
        visible: false
      }
    ],
    order: [[8, 'desc']],
    drawCallback: function (settings) {
      var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
      )

      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        var existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl)
        if (existingTooltip) {
          existingTooltip.dispose()
        }
        return new bootstrap.Tooltip(tooltipTriggerEl, {
          customClass: 'custom-tooltip'
        })
      })
    }
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
        title: 'Member'
      },
      // {
      //   data: 'current_balance',
      //   title: 'Current Balance',
      //   render: function (data) {
      //     return `&#8369;${parseFloat(data).toFixed(2)}`
      //   }
      // },
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
    order: [[7, 'desc']]
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
                <li><a class="dropdown-item notes-btn" href="javascript:void(0)" data-id="${data.amortization_id}" data-notes="${data.notes}"><i class="fas fa-file-lines"></i> View Payment Notes</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)" data-id="${data.amortization_id}"><i class="fas fa-file-image"></i> Prof of Payments</a></li>
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

window.DataTableAmortizationTypes = function ($loanTypesTable, data) {
  if ($.fn.DataTable.isDataTable($loanTypesTable)) {
    $loanTypesTable.DataTable().destroy()
    $loanTypesTable.empty()
  }
  let loanTypesDataTable = $($loanTypesTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'No loan type records found',
      zeroRecords: 'No matching loan type found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Loan Type',
      infoEmpty: 'Showing 0 to 0 of 0 Loan Type',
      infoFiltered: '(filtered from _MAX_ total Loan Type)',
      search: 'Search Loan Type:',
      lengthMenu: 'Show _MENU_ Loan Type'
    },
    columns: [
      {
        data: 'type_name',
        title: 'Amortization Type'
      },
      {
        data: 'description',
        title: 'Description',
        orderable: false
      },
      {
        data: 'interest_rate',
        title: 'Interest Rate (%)'
      },
      {
        data: 'term_months',
        title: 'Term (Months)'
      },
      {
        data: 'minimum_amount',
        title: 'Minimum Amount',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'maximum_amount',
        title: 'Maximum Amount',
        render: function (data) {
          return `&#8369;${parseFloat(data).toFixed(2)}`
        }
      },
      {
        data: 'status',
        title: 'Status',
        render: function (data) {
          if (data === 'active') {
            return `<span class="status-badge status-active">Active</span>`
          } else {
            return `<span class="status-badge status-inactive">Inactive</span>`
          }
        }
      },
      {
        data: null,
        title: 'Actions',
        orderable: false,
        render: function (data) {
          // return `
          //   <button class="btn btn-sm action-btn edit-btn" data-id="${data.type_id}">
          //     <i class="fas fa-edit"></i> Edit
          //   </button>
          //   <button class="btn btn-sm action-btn delete-btn" data-id="${data.type_id}">
          //     <i class="fas fa-trash"></i> Delete
          //   </button>
          // `
          return `
            <div class="dropdown" id="requestAmortizationActionDropdown">
              <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-1"></i><i class="fas fa-chevron-down" style="font-size: 8px; margin-bottom: 2px"></i>
              </button>
              <ul class="dropdown-menu profile-menu" aria-labelledby="requestAmortizationActionDropdown">
                <li><a class="dropdown-item edit-btn" href="javascript:void(0)" data-id="${data.type_id}"><i class="fas fa-edit me-2"></i> Edit Details</a></li>
                <li><a class="dropdown-item delete-btn" href="javascript:void(0)" data-id="${data.type_id}"><i class="fas fa-trash me-2"></i> Delete Type</a></li>
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

  return loanTypesDataTable
}
