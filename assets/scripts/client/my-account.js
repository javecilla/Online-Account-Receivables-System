$(document).ready(async function () {
  window.handlePageContent = async function () {
    const tab = getURLParams('tab')
    const activeHashFragment = getHashFragment()

    if (tab === 'amortizations') {
      if (activeHashFragment === 'application') {
        $memberAmortilizationTitle.text('List of amortization requests:')

        $memberApprovedAmortizationsListAction.addClass('hidden')
        $memberRequestAmortizationsListAction.removeClass('hidden')
        $memberAmortizationPaymentsListAction.addClass('hidden')

        $memberApprovedAmortizationsListContent.addClass('hidden')
        $memberRequestAmortizationsListContent.removeClass('hidden')
        $memberAmortizationPaymentsListContent.addClass('hidden')
      } else if (activeHashFragment === 'payment-history') {
        //console.info('payment-history')
        $memberAmortilizationTitle.text(
          `List of amortization payments on ${getURLParams(
            'amortization_type'
          )}:`
        )

        $memberApprovedAmortizationsListAction.addClass('hidden')
        $memberRequestAmortizationsListAction.addClass('hidden')
        $memberAmortizationPaymentsListAction.removeClass('hidden')

        $memberApprovedAmortizationsListContent.addClass('hidden')
        $memberRequestAmortizationsListContent.addClass('hidden')
        $memberAmortizationPaymentsListContent.removeClass('hidden')

        await displayMemberAmortizationPaymentsTable(
          getURLParams('amortization_id')
        )
      }
    } else {
      //for tab amortizations (default - UI)
      $memberAmortilizationTitle.text('List of approved amortizations:')

      $memberApprovedAmortizationsListAction.removeClass('hidden')
      $memberRequestAmortizationsListAction.addClass('hidden')
      $memberAmortizationPaymentsListAction.addClass('hidden')

      $memberApprovedAmortizationsListContent.removeClass('hidden')
      $memberRequestAmortizationsListContent.addClass('hidden')
      $memberAmortizationPaymentsListContent.addClass('hidden')
    }
  }

  await handlePageContent()
})
