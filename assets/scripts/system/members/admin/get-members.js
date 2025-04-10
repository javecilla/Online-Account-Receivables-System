$(document).ready(async function () {
  const $tableContainerElements = $('.tableContainerContent')
  const $formContainerElements = $('.formContainerContent')
  const $allTransactionsLogsContainerContent = $(
    '#allTransactionsLogsContainerContent'
  )
  const $formMemberContent = $('.formMemberContent')
  const $modeBreadCrumb = $('#modeBreadCrumb')
  const $membersTable = $('#membersTable')

  let dataTable = $($membersTable).DataTable({
    responsive: true,
    processing: true,
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
    order: [[5, 'desc']] // Sort by created_at by default
  })

  window.dislayMembers = async function () {
    try {
      $membersTable.addClass('loading')
      const members = await fetchMembers()
      //console.log(members)
      if (members.success) {
        dataTable.clear()
        dataTable.rows.add(members.data.items)
        dataTable.draw()
        const metaData = members.data.meta_data
      } else {
        console.error('Error fetching members:', response.message)
        toastr.error('Failed to load members data')
      }
    } catch (error) {
      console.error('Error fetching members:', error)
      //toastr.error('An error occurred while fetching members data')
    } finally {
      $membersTable.removeClass('loading')
    }
  }

  window.displayMembershipTypes = async function () {
    try {
      const membershipTypes = await fetchMembershipTypes()
      //console.log(membershipTypes)
      if (membershipTypes.success) {
        const $membershipTypesSelect = $('#membershipType')
        $membershipTypesSelect.empty()
        $('#membershipTypeUI').val('')
        $membershipTypesSelect.append('<option value="" selected></option>')
        membershipTypes.data.forEach((type) => {
          $membershipTypesSelect.append(
            `<option value="${type.type_id}">${type.type_name}</option>`
          )
        })

        $membershipTypesSelect.on('change', function () {
          //remove error messages
          $('#membershipTypeUI').removeClass('is-invalid')
          $('#membershipTypeError').text('')
          //appen in the ui select name membership
          const selectedMembershipTypeId = $(this).val()
          const selectedMembershipTypeName = $(this).find(':selected').text()
          $('#membershipTypeUI').val(selectedMembershipTypeName)
        })
      }
    } catch (error) {
      console.error('Error fetching membership types:', error)
      //toastr.error('Failed to load membership types')
    }
  }

  $membersTable.on('click', '.view-btn', async function () {
    const member_id = $(this).data('id')
    addURLParams('mode', 'view')
    addURLParams('member_id', member_id)
    setHashFragment('internal')

    await handlePageContent()
  })

  $membersTable.on('click', '.delete-btn', async function () {
    const memberId = $(this).data('id')
    //toastr.info(`Delete account ${memberId} - Feature coming soon`)
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
      html: '<small>Are you sure you want to delete this member record? This action is cannot be undone.</small>',
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
              //toastr.success('delete testing')
              try {
                const res = await deleteMember(memberId)
                if (res.success) {
                  toastr.success(res.message)
                  await dislayMembers()
                } else {
                  console.error('Error deleting account:', response.message)
                  toastr.error(res.message)
                }
              } catch (error) {
                console.error('Error deleting account:', error)
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

  window.displayTransactionHistory = function (data) {
    const $transactionsTable = $('#memberTransactionsTable')

    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable($transactionsTable)) {
      $transactionsTable.DataTable().destroy()
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
          data: 'notes',
          title: '#',
          render: function (data) {
            return `<span data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${data}" style="cursor: default!important" >
                <i class="fas fa-file me-2"></i>Notes
            </span>`
          }
        },
        { data: 'reference_number', title: 'Reference Number' },
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
            return moment(data).format('DD MMM YYYY h:mm A') //31 Mar 2025  5:30 PM
          }
        }
      ],
      order: [[6, 'desc']] // Sort by date descending by default
    })

    return transactionDataTable
  }

  window.displayAmortizationHistory = function (data) {
    const $amortizationTable = $('#memberAmortilizationTable')

    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable($amortizationTable)) {
      $amortizationTable.DataTable().destroy()
    }

    let amortizationDataTable = $($amortizationTable).DataTable({
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
                statusClass = '' // Default class if no match is found
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
        // {
        //   data: 'remaining_balance',
        //   title: 'Remaining Balance',
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
        }
      ],
      order: [[5, 'desc']] // Sort by start date by default
    })

    return amortizationDataTable
  }

  window.displayMemberDetails = function (data) {
    $('#memberCurrentBalance')
      .val(`₱ ${data.current_balance}`)
      .prop('readonly', true)
    $('#memberUID').val(data.member_uid).prop('readonly', true)
    $('#membershipTypeUI').val(data.membership_type)
    $membershipType.val(data.membership_id)
    $memberFirstName.val(data.first_name)
    $memberMiddleName.val(
      isNull(data.middle_name) || isEmpty(data.middle_name)
        ? data.mode === 'view'
          ? 'N/A'
          : ''
        : data.middle_name
    )
    $memberLastName.val(data.last_name)
    $memberContactNumber.val(data.contact_number.substring(3))
    $memberHouseAddress.val(data.house_address)
    $memberBarangay.val(data.barangay)
    $memberMunicipality.val(data.municipality)
    $memberProvince.val(data.province)
    $memberRegion.val(data.region)
    $('#memberCreatedAt')
      .val(formatReadableDateTime(data.member_created_at))
      .prop('readonly', true)
    $('#memberUpdatedAt')
      .val(formatReadableDateTime(data.member_updated_at))
      .prop('readonly', true)

    if (data.mode === 'view') {
      $('.memberAdditionalInformation').removeClass('hidden')
      $membershipType.prop('disabled', true)
      $memberMiddleName.prop('readonly', true)
      $memberFirstName.prop('readonly', true)
      $memberLastName.prop('readonly', true)
      $memberContactNumber.prop('readonly', true)
      $memberHouseAddress.prop('readonly', true)
      $memberBarangay.prop('readonly', true)
      $memberMunicipality.prop('readonly', true)
      $memberProvince.prop('readonly', true)
      $memberRegion.prop('readonly', true)
      $('#viewMoreAccountInfoBtn').on('click', function () {
        window.open(
          `accounts?mode=view&for=member&account_id=${data.account_id}&member_id=${data.member_id}#internal`,
          '_blank'
        )
      })
    }
    console.log(data)
  }

  window.displayAllTransactionsLogs = function (data) {
    const $transactionLogs = $('#membersTransactionLogsTable')

    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable($transactionLogs)) {
      $transactionLogs.DataTable().destroy()
    }

    let membersTransactionLogs = $($transactionLogs).DataTable({
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
            return moment(data).format('DD MMM YYYY h:mm A')
          }
        }
      ],
      order: [[5, 'desc']] // Sort by start date by default
    })

    return membersTransactionLogs
  }

  $('#viewMembersTransactionsLogsBtn').on('click', async function () {
    addURLParams('mode', 'show')
    setHashFragment('transactions-logs')

    LoadingManager.show($('.main-content'))
    await handlePageContent()
    LoadingManager.hide($('.main-content'))
  })

  async function handlePageContent() {
    const mode = getURLParams('mode')
    const hashFragment = getHashFragment()

    console.log(mode)
    console.log(hashFragment)

    if (mode === 'view' || (mode === 'update' && hashFragment === 'internal')) {
      $tableContainerElements.addClass('hidden')
      $formContainerElements.removeClass('hidden')
      $formMemberContent.removeClass('hidden')
      $('#tabContainerContent').removeClass('hidden')
      $modeBreadCrumb.text('/ ' + mode.charAt(0).toUpperCase() + mode.slice(1))
      $allTransactionsLogsContainerContent.addClass('hidden')
      try {
        const activeMemberId = getURLParams('member_id')
        const member = await fetchMember(activeMemberId)
        const amortizations = await fetchMemberAmortizations(activeMemberId)
        const transactions = await fetchMemberTransactionsHistory(
          activeMemberId
        )

        if (member.success) {
          member.data.mode = mode
          displayMemberDetails(member.data)
        }

        if (transactions.success) {
          displayTransactionHistory(transactions.data)
        }

        if (amortizations.success) {
          displayAmortizationHistory(amortizations.data)
        }
      } catch (error) {
        console.error('Error fetching member:', error)
        //toastr.error('An error occurred while fetching account')
      }
    } else if (mode === 'show' && hashFragment === 'transactions-logs') {
      $tableContainerElements.addClass('hidden')
      $formContainerElements.removeClass('hidden')
      $formMemberContent.addClass('hidden')
      $allTransactionsLogsContainerContent.removeClass('hidden')
      $modeBreadCrumb.text('/ ' + mode.charAt(0).toUpperCase() + mode.slice(1))

      try {
        const transactionLogs = await fetchMembersTransactionsLogs()
        if (transactionLogs.success) {
          displayAllTransactionsLogs(transactionLogs.data)
          // console.log(transactionLogs.data)
        }
      } catch (error) {
        console.error('Error fetching logs:', error)
        //toastr.error('An error occurred while fetching account')
      }
    } else {
      $tableContainerElements.removeClass('hidden')
      $formContainerElements.addClass('hidden')
      $formMemberContent.addClass('hidden')
      $('#tabContainerContent').addClass('hidden')
      $modeBreadCrumb.text('/')
      $allTransactionsLogsContainerContent.addClass('hidden')
    }
  }

  $('#backToTableContainerBtn').on('click', async function () {
    removeHashFragment()
    removeAllParams()
    removeAllParams()
    await handlePageContent()
  })

  await handlePageContent()
})
