$(document).ready(async function () {
  const swalBase = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-light action-btn',
      cancelButton: 'btn btn-light action-btn',
      showLoaderOnConfirm: 'swal2-loader'
    },
    buttonsStyling: false
  })

  //TODO: AMORTIZATION PAYMENTS TABLE
  const $paymentsTable = $('#paymentsTable')
  window.displayAmortizationPayments = async function () {
    try {
      const payments = await fetchAmortizationPayments()
      if (payments.success) {
        DataTableAmortizationPayments($paymentsTable, payments.data)
      }
    } catch (error) {
      console.error('Error displaying amortization payments:', error)
    }

    //registered listener for payments
    $paymentsTable.on('click', '.notes-btn', function () {
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
    //$paymentsTable.on('click', '.invoice-btn', function () {})
  }

  $('#viewPaymentsLogsBtn').click(async function () {
    addURLParams('mode', 'view')
    addURLParams('for', 'payments')
    setHashFragment('internal')

    await handlePageContent()
  })

  //TODO: AMORTIZATIONS BY STATUS TABLE
  const $amortizationsTable = $('#amortizationsTable')
  window.displayAmortizationsByStatus = async function () {
    try {
      const amortization = await fetchAmortizationsByStatus()
      if (amortization.success) {
        //console.log(amortization.data)
        DataTableAmortizationsByStatus($amortizationsTable, amortization.data)
      }
    } catch (error) {
      console.error('Error fetching amortizations by status:', error)
    }
  }

  $amortizationsTable.on('click', '.view-btn', function () {
    const memberId = $(this).data('id')
    window.open(`members?mode=view&member_id=${memberId}#internal`, '_blank')
  })

  //TODO: AMORTIZATIONS BY APPROVAL TABLE
  const $loanRequestsTable = $('#loanRequestsTable')
  window.displayAmortizationsByApproval = async function () {
    try {
      const amortization = await fetchAmortizationsByApproval()
      if (amortization.success) {
        DataTableAmortizationsByApproval($loanRequestsTable, amortization.data)
      }
    } catch (error) {
      console.error('Error fetching amortizations by approval:', error)
    }
  }

  function handleApprovalButtonClick(buttonClass, successMessage) {
    $loanRequestsTable.on('click', buttonClass, async function () {
      const amortizationId = $(this).data('id')
      const approval = $(this).data('approval')
      // toastr.info(`amortizationId: ${amortizationId} | ${approval}`);
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
    swalBase.fire({
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
  })

  const $tableContainerElements = $('.tableContainerContent')
  const $formContainerElements = $('.formContainerContent')
  const $modeBreadCrumb = $('#modeBreadCrumb')

  async function handlePageContent() {
    const mode = getURLParams('mode')
    const forAccount = getURLParams('for')
    // const hashFragment = getHashFragment()
    if (mode === 'view' && forAccount === 'payments') {
      $tableContainerElements.addClass('hidden')
      $formContainerElements.removeClass('hidden')
      $modeBreadCrumb.text('/ View')
    } else {
      $tableContainerElements.removeClass('hidden')
      $formContainerElements.addClass('hidden')
      $modeBreadCrumb.text('/')

      removeHashFragment()
      removeAllParams()
      removeAllParams()
    }
  }

  $('#backToTableContainerBtn').click(async function () {
    removeHashFragment()
    removeAllParams()
    removeAllParams()
    await handlePageContent()
  })

  await handlePageContent()
})
