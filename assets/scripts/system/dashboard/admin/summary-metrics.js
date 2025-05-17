window.updateSummaryMetrics = async function () {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_risk_assessment_metrics'
    )
    if (response.data.success) {
      const metrics = response.data.data
      metrics.forEach((metric) => {
        const formatCurrency = (value) =>
          `₱${parseFloat(value).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          })}`
        const formatPercentage = (value) => `${parseFloat(value).toFixed(2)}%`

        if (metric.account_type === 'Fixed Deposit') {
          document.getElementById('FDtotalAccounts').textContent =
            metric.total_accounts
          document.getElementById('FDAverageBalance').textContent =
            formatCurrency(metric.avg_balance)
          document.getElementById('FDAccountsBelowMinimum').textContent =
            metric.accounts_below_minimum
          document.getElementById('FDPercentBelowMinimum').textContent =
            formatPercentage(metric.percent_below_minimum)
        } else if (metric.account_type === 'Savings Account') {
          document.getElementById('SAtotalAccounts').textContent =
            metric.total_accounts
          document.getElementById('SAAverageBalance').textContent =
            formatCurrency(metric.avg_balance)
          document.getElementById('SAAccountsBelowMinimum').textContent =
            metric.accounts_below_minimum
          document.getElementById('SAPercentBelowMinimum').textContent =
            formatPercentage(metric.percent_below_minimum)
        } else if (metric.account_type === 'Time Deposit') {
          document.getElementById('TDtotalAccounts').textContent =
            metric.total_accounts
          document.getElementById('TDAverageBalance').textContent =
            formatCurrency(metric.avg_balance)
          document.getElementById('TDAccountsBelowMinimum').textContent =
            metric.accounts_below_minimum
          document.getElementById('TDPercentBelowMinimum').textContent =
            formatPercentage(metric.percent_below_minimum)
        } else if (metric.account_type === 'Loan') {
          document.getElementById('loanAccounts').textContent =
            metric.total_accounts
          document.getElementById('loanAverageBalance').textContent =
            formatCurrency(metric.avg_balance)
          document.getElementById('loanAccountsBelowMinimum').textContent =
            metric.accounts_below_minimum
          document.getElementById('loanPercentBelowMinimum').textContent =
            formatPercentage(metric.percent_below_minimum)
        }
      })
    }
  } catch (e) {
    console.error('Error fetching dashboard summary:', e)
  }
}
