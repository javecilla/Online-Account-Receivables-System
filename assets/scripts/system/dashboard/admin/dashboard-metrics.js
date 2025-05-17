window.updateDashboardMetrics = async function () {
  try {
    document.querySelectorAll('.dashboard-value').forEach((el) => {
      el.textContent = 'Loading...'
    })

    const response = await axios.get(
      '/app/router/api.php?action=get_dashboard_metrics'
    )

    if (response.data.success && response.data.data.length > 0) {
      const summaryData = response.data.data[0]

      // Update Active Member Balances (First Card)
      const totalBalanceValue = document.querySelector('#totalBalanceValue')
      const totalMemberCurrent = document.querySelector('#totalMemberCurrent')

      if (totalBalanceValue) {
        totalBalanceValue.textContent = formatCurrency(
          summaryData.total_active_balances
        )
      }
      if (totalMemberCurrent) {
        totalMemberCurrent.textContent = summaryData.total_active_members || '0'
      }

      // Update Total Amount Receivables (Second Card)
      const totalReceivables = document.querySelector('#totalReceivables')
      const totalBorrowers = document.querySelector('#totalBorrowers')

      if (totalReceivables) {
        totalReceivables.textContent = formatCurrency(
          summaryData.total_receivables
        )
      }
      if (totalBorrowers) {
        totalBorrowers.textContent = summaryData.total_borrowers || '0'
      }

      // Update Overdue Portfolio (Third Card)
      const overdueAmount = document.querySelector('#overdueAmount')
      const overdueAccounts = document.querySelector('#overdueAccounts')
      const overduePercentage = document.querySelector('#overduePercentage')

      if (overdueAmount) {
        overdueAmount.textContent = formatCurrency(
          summaryData.overdue_receivables
        )
      }
      if (overdueAccounts) {
        overdueAccounts.textContent = summaryData.overdue_accounts || '0'
      }
      if (overduePercentage) {
        overduePercentage.textContent = `${parseFloat(
          summaryData.overdue_percentage || 0
        ).toFixed(2)}%`
      }
    } else {
      throw new Error(response.data.message || 'No data available')
    }
  } catch (error) {
    console.error('Error fetching dashboard summary:', error)
    toastr.error(error.message || 'Failed to load dashboard summary data')

    // Show error state
    document.querySelectorAll('.dashboard-value').forEach((el) => {
      el.textContent = 'Error loading data'
    })
  }
}
