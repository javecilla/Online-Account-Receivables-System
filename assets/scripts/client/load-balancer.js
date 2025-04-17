$(document).ready(async function () {
  LoadingManager.show($mainContent)

  async function initializePage() {
    try {
      await Promise.all([
        displayMemberDetails(),
        displayMemberApprovedAmortizations(),
        displayMemberRequestAmortizations(),
        displayMemberTransactionsHistory()
      ])
    } catch (error) {
      console.error('Error initializing page:', error)
      toastr.error('An error occurred while loading the page')
    } finally {
      LoadingManager.hide($mainContent)
    }
  }

  await initializePage()
})
