$(document).ready(function () {
  const $mainContent = $('.main-content')
  LoadingManager.show($mainContent)
  async function initializePage() {
    try {
      await Promise.all([
        displayAccountsList(),
        displayAccountRoles(),
        displayMembershipTypes()
      ])
    } catch (error) {
      console.error('Error initializing page:', error)
      toastr.error('An error occurred while loading the page')
    } finally {
      LoadingManager.hide($mainContent)
    }
  }

  initializePage()
})
