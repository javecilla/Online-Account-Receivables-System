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
