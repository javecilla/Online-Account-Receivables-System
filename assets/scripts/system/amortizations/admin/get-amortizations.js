$(document).ready(async function () {
  const $tableContainerElements = $('.tableContainerContent')
  const $formContainerElements = $('.formContainerContent')
  const $modeBreadCrumb = $('#modeBreadCrumb')

  const $amortizationsTable = $('#amortizationsTable') //table for amortizations
  window.displayAmortizationsByStatus = async function () {
    try {
      const amortization = await fetchAmortizationsByStatus()
      if (amortization.success) {
        const data = amortization.data
        //console.log(amortization.data)

        // Display the table// Destroy existing DataTable if it exists
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
                    statusClass = '' // Default class if no match is found
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
            // {
            //   data: 'remaining_balance',
            //   title: 'Remaining Balance',
            //   render: function (data) {
            //     return `&#8369;${parseFloat(data).toFixed(2)}`
            //   }
            // },
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
                let icon =
                  monthsPassed >= half ? 'fa-arrow-down' : 'fa-arrow-up'

                // Calculate total repayment amount (principal + interest)
                const principalAmount = parseFloat(data.principal_amount) || 0
                const interestRate = parseFloat(data.interest_rate) || 0
                const interestAmount =
                  (principalAmount * interestRate) / 100 || 0
                const totalRepayment = principalAmount + interestAmount

                // Calculate percentage based on total repayment
                const totalPaid = parseFloat(data.total_paid) || 0
                let percentagePaid = 0

                if (totalRepayment > 0) {
                  percentagePaid = ((totalPaid / totalRepayment) * 100).toFixed(
                    2
                  )
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
    } catch (error) {
      console.error('Error fetching amortizations by status:', error)
      //toastr.error('Failed to load amortizations by status')
    }
  }

  const $loanRequestsTable = $('#loanRequestsTable') //table for loan requests
  window.displayAmortizationsByApproval = async function () {
    try {
      const amortization = await fetchAmortizationsByApproval()
      if (amortization.success) {
        const data = amortization.data

        // Display the table// Destroy existing DataTable if it exists
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
                    statusClass = '' // Default class if no match is found
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
    } catch (error) {
      console.error('Error fetching amortizations by approval:', error)
      //toastr.error('Failed to load amortizations by approval')
    }
  }

  $amortizationsTable.on('click', '.view-btn', function () {
    const memberId = $(this).data('id')
    window.open(
      `members?mode=view&member_id=${memberId}&udm=amortizations#internal`,
      '_blank'
    )
  })

  function handleApprovalButtonClick(buttonClass, successMessage) {
    $loanRequestsTable.on('click', buttonClass, async function () {
      const amortizationId = $(this).data('id')
      const approval = $(this).data('approval')
      // toastr.info(`amortizationId: ${amortizationId} | ${approval}`);

      // Disable button
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
        toastr.error('Failed to update approval status')
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

    const swalDelete = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-light action-btn',
        cancelButton: 'btn btn-light action-btn',
        showLoaderOnConfirm: 'swal2-loader'
      },
      buttonsStyling: false
    })

    swalDelete.fire({
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
                } else {
                  console.error(
                    'Error deleting amortization:',
                    response.message
                  )
                  toastr.error(res.message)
                }
              } catch (error) {
                console.error('Error deleting amortization:', error)
              }
              swalDelete.close()
            }, 1500) //end set timeout
          }) // end promise
        } // end else for checking response
      }, // end preconfirm
      allowOutsideClick: function () {
        !submitForm.isLoading()
      }
    }) //end swal
  })

  function displayAmortizationPayments(data) {
    console.log('Data passed to DataTable:', data) // Debugging line
    const $paymentsTable = $('#paymentsTable')

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
          data: 'notes',
          title: '#',
          render: function (data) {
            return `<span data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${data}" style="cursor: default!important" >
                <i class="fas fa-file me-2"></i>Notes
            </span>`
          }
        },
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
        }
      ],
      order: [[5, 'desc']]
    })

    return paymentsDataTable
  }

  async function handlePageContent() {
    const mode = getURLParams('mode')
    const forAccount = getURLParams('for')
    const hashFragment = getHashFragment()
    if (mode === 'view' && forAccount === 'payments') {
      LoadingManager.show($('.main-content'))
      const payments = await fetchAmortizationPayments()
      try {
        if (payments.success) {
          console.log('Payments data:', payments.data) // Debugging line
          $tableContainerElements.addClass('hidden')
          $formContainerElements.removeClass('hidden') // Ensure this line is executed
          $modeBreadCrumb.text('/ View')

          displayAmortizationPayments(payments.data)
        }
      } catch (error) {
        console.error('Error fetching amortizations payments:', error)
        // toastr.error('Failed to load amortizations payments')
      }
      LoadingManager.hide($('.main-content'))
    } else {
      $tableContainerElements.removeClass('hidden')
      $formContainerElements.addClass('hidden')
      $modeBreadCrumb.text('/')

      removeHashFragment()
      removeAllParams()
      removeAllParams()
    }
  }

  $('#viewPaymentsLogsBtn').click(async function () {
    addURLParams('mode', 'view')
    addURLParams('for', 'payments')
    setHashFragment('internal')

    await handlePageContent()
  })

  $('#backToTableContainerBtn').click(async function () {
    removeHashFragment()
    removeAllParams()
    removeAllParams()

    await handlePageContent()
  })

  await handlePageContent()
})
