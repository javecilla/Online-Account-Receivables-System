$(document).ready(function () {
  const $mainContent = $('.main-content')

  // Show loading state immediately when page loads
  LoadingManager.show($mainContent)

  // Function to initialize page content
  async function initializePage() {
    try {
      // Wait for all initialization tasks to complete
      await Promise.all([
        dislayAccounts(),
        displayAccountRoles(),
        displayMembershipTypes()
      ])
    } catch (error) {
      console.error('Error initializing page:', error)
      toastr.error('An error occurred while loading the page')
    } finally {
      // Hide loading state after all content is loaded
      LoadingManager.hide($mainContent)
    }
  }

  // Start page initialization
  initializePage()
})
