$(document).ready(function () {
  const $mainContent = $('.main-content')
  LoadingManager.show($mainContent)

  async function initializePage() {
    try {
      const start = moment().subtract(7, 'days')
      const end = moment()
      const currentYear = moment().year()

      // Wait for all initialization tasks to complete,Load all dashboard components concurrently
      await Promise.all([
        updateDashboardMetrics(),
        updateDateRangeText(start, end),
        updateMonthlyBalanceTrendsChart(currentYear),
        fetchAndRenderLoanPerformanceData(),
        fetchAndRenderOutstandingReceivables(),
        updatePaymentTrendsMonthlyChart(),
        updatePaymentHistoriesByMemberChart(),
        updateQuarterlyFinancialSummaryIP(),
        updateQuarterlyFinancialSummaryT(),
        updateMonthlyFinancialSummaryIP(),
        updateMonthlyFinancialSummaryT(),
        updateAnnuallyFinancialSummaryIP(),
        updateAnnuallyFinancialSummaryT()
      ])
      //await updateDateRangeText(start, end)
    } catch (error) {
      console.error('Error initializing page:', error)
      toastr.error('An error occurred while loading the page')
    } finally {
      LoadingManager.hide($mainContent)
    }
  }

  initializePage()
})
