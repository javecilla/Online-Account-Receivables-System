let annuallyFinancialSummaryIPChart = null
let annuallyFinancialSummaryTChart = null

// --- Annually Financial Summary (Income from Payments) ---

async function updateAnnuallyFinancialSummaryIP() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_annual_financial_summary_income_from_payments'
    )
    const { success, data } = response.data

    if (!success || !data) {
      throw new Error('Failed to fetch annual income data or data is missing.')
    }

    // Sort data chronologically by year
    data.sort((a, b) => parseInt(a.year) - parseInt(b.year))

    const chartData = {
      labels: data.map((item) => item.year),
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

    renderAnnuallyFinancialSummaryIP(chartData)
  } catch (error) {
    console.error('Error fetching or processing annual income data:', error)
    toastr.error('Failed to load annual income summary data.')
    const chartContainer = document.getElementById(
      'annuallyFinancialSummaryIP'
    )?.parentNode
    if (chartContainer) {
      chartContainer.innerHTML =
        '<p class="text-danger text-center">Could not load chart data.</p>'
    }
  }
}

function renderAnnuallyFinancialSummaryIP(chartData) {
  const ctx = document.getElementById('annuallyFinancialSummaryIP')
  if (!ctx) {
    console.error('annuallyFinancialSummaryIP canvas element not found.')
    return
  }

  if (annuallyFinancialSummaryIPChart) {
    annuallyFinancialSummaryIPChart.destroy()
  }

  annuallyFinancialSummaryIPChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: false // Title is in the card header
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
            text: 'Year'
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

// --- Annually Financial Summary (Transactions) ---

async function updateAnnuallyFinancialSummaryT() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_annual_financial_summary_transactions'
    )
    const { success, data } = response.data

    if (!success || !data) {
      throw new Error(
        'Failed to fetch annual transaction data or data is missing.'
      )
    }

    // Process data for grouped bar chart
    const years = [
      ...new Set(
        data.map((item) => item.year).sort((a, b) => parseInt(a) - parseInt(b))
      )
    ]
    const transactionTypes = [
      ...new Set(data.map((item) => item.transaction_type))
    ]

    const datasets = transactionTypes.map((type, index) => {
      const typeData = years.map((year) => {
        const item = data.find(
          (d) => d.year === year && d.transaction_type === type
        )
        return item ? parseFloat(item.total_amount) : 0
      })

      // Assign distinct colors (same as quarterly/monthly)
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
      labels: years,
      datasets: datasets
    }

    renderAnnuallyFinancialSummaryT(chartData)
  } catch (error) {
    console.error(
      'Error fetching or processing annual transaction data:',
      error
    )
    toastr.error('Failed to load annual transaction summary data.')
    const chartContainer = document.getElementById(
      'annuallyFinancialSummaryT'
    )?.parentNode
    if (chartContainer) {
      chartContainer.innerHTML =
        '<p class="text-danger text-center">Could not load chart data.</p>'
    }
  }
}

function renderAnnuallyFinancialSummaryT(chartData) {
  const ctx = document.getElementById('annuallyFinancialSummaryT')
  if (!ctx) {
    console.error('annuallyFinancialSummaryT canvas element not found.')
    return
  }

  if (annuallyFinancialSummaryTChart) {
    annuallyFinancialSummaryTChart.destroy()
  }

  annuallyFinancialSummaryTChart = new Chart(ctx, {
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
          position: 'top'
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Year'
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
      }
    }
  })
}
