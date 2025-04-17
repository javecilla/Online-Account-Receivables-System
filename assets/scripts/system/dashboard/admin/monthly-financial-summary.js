let monthlyFinancialSummaryIPChart = null
let monthlyFinancialSummaryTChart = null

// --- Monthly Financial Summary (Income from Payments) ---

async function updateMonthlyFinancialSummaryIP() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_monthly_financial_summary_income_from_payments'
    )
    const { success, data } = response.data

    if (!success || !data) {
      throw new Error('Failed to fetch monthly income data or data is missing.')
    }

    // Sort data chronologically by month_year (e.g., 2025-01, 2025-02)
    data.sort((a, b) => {
      return new Date(a.month_year + '-01') - new Date(b.month_year + '-01')
    })

    const chartData = {
      labels: data.map((item) =>
        moment(item.month_year + '-01').format('YYYY MMM')
      ),
      datasets: [
        {
          label: 'Total Payment Income',
          data: data.map((item) => parseFloat(item.total_payment_income)),
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }
      ]
    }

    renderMonthlyFinancialSummaryIP(chartData)
  } catch (error) {
    console.error('Error fetching or processing monthly income data:', error)
    toastr.error('Failed to load monthly income summary data.')
    const chartContainer = document.getElementById(
      'monthlyFinancialSummaryIP'
    )?.parentNode
    if (chartContainer) {
      chartContainer.innerHTML =
        '<p class="text-danger text-center">Could not load chart data.</p>'
    }
  }
}

function renderMonthlyFinancialSummaryIP(chartData) {
  const ctx = document.getElementById('monthlyFinancialSummaryIP')
  if (!ctx) {
    console.error('monthlyFinancialSummaryIP canvas element not found.')
    return
  }

  if (monthlyFinancialSummaryIPChart) {
    monthlyFinancialSummaryIPChart.destroy()
  }

  monthlyFinancialSummaryIPChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              let label = context.dataset.label || ''
              if (label) {
                label += ': '
              }
              if (context.parsed.y !== null) {
                label +=
                  '₱' +
                  context.parsed.y.toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  })
              }
              return label
            }
          }
        },
        legend: {
          display: false // Only one dataset, legend not crucial
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Month'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Total Income (₱)'
          },
          ticks: {
            callback: function (value) {
              return '₱' + value.toLocaleString('en-PH')
            }
          }
        }
      }
    }
  })
}

// --- Monthly Financial Summary (Transactions) ---

async function updateMonthlyFinancialSummaryT() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_monthly_financial_summary_transactions'
    )
    const { success, data } = response.data

    if (!success || !data) {
      throw new Error(
        'Failed to fetch monthly transaction data or data is missing.'
      )
    }

    // Process data for grouped bar chart
    const months = [
      ...new Set(
        data
          .map((item) => item.month_year)
          .sort((a, b) => new Date(a + '-01') - new Date(b + '-01'))
      )
    ].map((month) => moment(month + '-01').format('YYYY MMM'))

    const transactionTypes = [
      ...new Set(data.map((item) => item.transaction_type))
    ]

    const datasets = transactionTypes.map((type, index) => {
      const typeData = months.map((month) => {
        const item = data.find(
          (d) =>
            moment(d.month_year + '-01').format('YYYY MMM') === month &&
            d.transaction_type === type
        )
        return item ? parseFloat(item.total_amount) : 0
      })

      // Assign distinct colors (same as quarterly)
      const colors = [
        { bg: 'rgba(255, 99, 132, 0.6)', border: 'rgba(255, 99, 132, 1)' },
        { bg: 'rgba(75, 192, 192, 0.6)', border: 'rgba(75, 192, 192, 1)' },
        { bg: 'rgba(255, 206, 86, 0.6)', border: 'rgba(255, 206, 86, 1)' },
        { bg: 'rgba(153, 102, 255, 0.6)', border: 'rgba(153, 102, 255, 1)' },
        { bg: 'rgba(255, 159, 64, 0.6)', border: 'rgba(255, 159, 64, 1)' }
      ]
      const color = colors[index % colors.length]

      return {
        label: type.charAt(0).toUpperCase() + type.slice(1),
        data: typeData,
        backgroundColor: color.bg,
        borderColor: color.border,
        borderWidth: 1
      }
    })

    const chartData = {
      labels: months,
      datasets: datasets
    }

    renderMonthlyFinancialSummaryT(chartData)
  } catch (error) {
    console.error(
      'Error fetching or processing monthly transaction data:',
      error
    )
    toastr.error('Failed to load monthly transaction summary data.')
    const chartContainer = document.getElementById(
      'monthlyFinancialSummaryT'
    )?.parentNode
    if (chartContainer) {
      chartContainer.innerHTML =
        '<p class="text-danger text-center">Could not load chart data.</p>'
    }
  }
}

function renderMonthlyFinancialSummaryT(chartData) {
  const ctx = document.getElementById('monthlyFinancialSummaryT')
  if (!ctx) {
    console.error('monthlyFinancialSummaryT canvas element not found.')
    return
  }

  if (monthlyFinancialSummaryTChart) {
    monthlyFinancialSummaryTChart.destroy()
  }

  monthlyFinancialSummaryTChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: false
        },
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: function (context) {
              let label = context.dataset.label || ''
              if (label) {
                label += ': '
              }
              if (context.parsed.y !== null) {
                label +=
                  '₱' +
                  context.parsed.y.toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  })
              }
              return label
            }
          }
        },
        legend: {
          position: 'top'
        }
      },
      scales: {
        x: {
          stacked: false,
          title: {
            display: true,
            text: 'Month'
          }
        },
        y: {
          stacked: false,
          title: {
            display: true,
            text: 'Total Amount (₱)'
          },
          ticks: {
            callback: function (value) {
              return '₱' + value.toLocaleString('en-PH')
            }
          }
        }
      }
    }
  })
}
