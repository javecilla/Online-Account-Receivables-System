let quarterlyFinancialSummaryIPChart = null
let quarterlyFinancialSummaryTChart = null

// --- Quarterly Financial Summary (Income from Payments) ---

async function updateQuarterlyFinancialSummaryIP() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_quarterly_financial_summary_income_from_payments'
    )
    const { success, data } = response.data

    if (!success || !data) {
      throw new Error(
        'Failed to fetch quarterly income data or data is missing.'
      )
    }

    // Sort data chronologically by quarter_year (e.g., 2025-Q1, 2025-Q2)
    data.sort((a, b) => {
      const [yearA, quarterA] = a.quarter_year.split('-Q')
      const [yearB, quarterB] = b.quarter_year.split('-Q')
      if (yearA !== yearB) {
        return parseInt(yearA) - parseInt(yearB)
      }
      return parseInt(quarterA) - parseInt(quarterB)
    })

    const chartData = {
      labels: data.map((item) => item.quarter_year),
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

    renderQuarterlyFinancialSummaryIP(chartData)
  } catch (error) {
    console.error('Error fetching or processing quarterly income data:', error)
    toastr.error('Failed to load quarterly income summary data.')
    const chartContainer = document.getElementById(
      'quarterlyFinancialSummaryIP'
    )?.parentNode
    if (chartContainer) {
      chartContainer.innerHTML =
        '<p class="text-danger text-center">Could not load chart data.</p>'
    }
  }
}

function renderQuarterlyFinancialSummaryIP(chartData) {
  const ctx = document.getElementById('quarterlyFinancialSummaryIP')
  if (!ctx) {
    console.error('quarterlyFinancialSummaryIP canvas element not found.')
    return
  }

  if (quarterlyFinancialSummaryIPChart) {
    quarterlyFinancialSummaryIPChart.destroy()
  }

  quarterlyFinancialSummaryIPChart = new Chart(ctx, {
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
          display: false
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Quarter'
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

// --- Quarterly Financial Summary (Transactions) ---

async function updateQuarterlyFinancialSummaryT() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_quarterly_financial_summary_transactions'
    )
    const { success, data } = response.data

    if (!success || !data) {
      throw new Error(
        'Failed to fetch quarterly transaction data or data is missing.'
      )
    }

    const quarters = [
      ...new Set(
        data
          .map((item) => item.quarter_year)
          .sort((a, b) => {
            const [yearA, quarterA] = a.split('-Q')
            const [yearB, quarterB] = b.split('-Q')
            if (yearA !== yearB) {
              return parseInt(yearA) - parseInt(yearB)
            }
            return parseInt(quarterA) - parseInt(quarterB)
          })
      )
    ]
    const transactionTypes = [
      ...new Set(data.map((item) => item.transaction_type))
    ]

    const datasets = transactionTypes.map((type, index) => {
      const typeData = quarters.map((quarter) => {
        const item = data.find(
          (d) => d.quarter_year === quarter && d.transaction_type === type
        )
        return item ? parseFloat(item.total_amount) : 0
      })

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
      labels: quarters,
      datasets: datasets
    }

    renderQuarterlyFinancialSummaryT(chartData)
  } catch (error) {
    console.error(
      'Error fetching or processing quarterly transaction data:',
      error
    )
    toastr.error('Failed to load quarterly transaction summary data.')
    const chartContainer = document.getElementById(
      'quarterlyFinancialSummaryT'
    )?.parentNode
    if (chartContainer) {
      chartContainer.innerHTML =
        '<p class="text-danger text-center">Could not load chart data.</p>'
    }
  }
}

function renderQuarterlyFinancialSummaryT(chartData) {
  const ctx = document.getElementById('quarterlyFinancialSummaryT')
  if (!ctx) {
    console.error('quarterlyFinancialSummaryT canvas element not found.')
    return
  }

  if (quarterlyFinancialSummaryTChart) {
    quarterlyFinancialSummaryTChart.destroy()
  }

  quarterlyFinancialSummaryTChart = new Chart(ctx, {
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
          position: 'bottom',
          labels: {
            padding: 15
          }
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Quarter'
          }
        },
        y: {
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
      },
      interaction: {
        intersect: false,
        mode: 'index'
      }
    }
  })
}

window.updateQuarterlyFinancialSummaryIP = updateQuarterlyFinancialSummaryIP
window.updateQuarterlyFinancialSummaryT = updateQuarterlyFinancialSummaryT
