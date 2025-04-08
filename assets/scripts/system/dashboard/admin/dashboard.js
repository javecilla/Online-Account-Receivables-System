$(document).ready(function () {
  const $mainContent = $('.main-content')

  // Show loading state immediately when page loads
  LoadingManager.show($mainContent)

  // Function to initialize page content
  async function initializePage() {
    try {
      const start = moment().subtract(7, 'days')
      const end = moment()
      const currentYear = moment().year()

      // Wait for all initialization tasks to complete
      await Promise.all([
        // Load all dashboard components concurrently
        updateDashboardMetrics(),
        updateDateRangeText(start, end),
        updateMonthlyBalanceTrendsChart(currentYear),
        fetchAndRenderLoanPerformanceData()
      ])
      //await updateDateRangeText(start, end)
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
