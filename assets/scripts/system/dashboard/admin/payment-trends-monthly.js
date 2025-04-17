let paymentTrendsMonthlyChart = null

async function updatePaymentTrendsMonthlyChart() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_payment_trends_monthly'
    )
    const { success, data } = response.data

    if (!success || !data) {
      throw new Error('Failed to fetch payment trends data or data is missing.')
    }

    // Sort data by payment_month
    data.sort((a, b) => new Date(a.payment_month) - new Date(b.payment_month))

    const chartData = {
      labels: data.map((item) => moment(item.payment_month).format('MMM YYYY')),
      datasets: [
        {
          label: 'Total Amount Paid',
          data: data.map((item) => parseFloat(item.total_amount_paid)),
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.2)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          yAxisID: 'yAmount'
        },
        {
          label: 'Total Payments',
          data: data.map((item) => parseInt(item.total_payments)),
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.2)',
          borderWidth: 2,
          fill: false,
          tension: 0.4,
          yAxisID: 'yCount'
        }
      ]
    }

    renderPaymentTrendsMonthlyChart(chartData)
  } catch (error) {
    console.error('Error fetching or processing payment trends data:', error)
    toastr.error('Failed to load monthly payment trends data.')
    // const chartContainer = document.getElementById(
    //   'PaymentTrendsMonthlyChart'
    // ).parentNode
    // chartContainer.innerHTML =
    //   '<p class="text-danger text-center">Could not load chart data.</p>'
  }
}

function renderPaymentTrendsMonthlyChart(chartData) {
  const ctx = document.getElementById('PaymentTrendsMonthlyChart')
  if (!ctx) {
    console.error('PaymentTrendsMonthlyChart canvas element not found.')
    return
  }

  if (paymentTrendsMonthlyChart) {
    paymentTrendsMonthlyChart.destroy()
  }

  paymentTrendsMonthlyChart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: false
          // text: 'Monthly Payment Trends',
          // font: {
          //   size: 16,
          //   weight: 'bold'
          // }
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
                if (context.dataset.yAxisID === 'yAmount') {
                  label +=
                    '₱' +
                    context.parsed.y.toLocaleString('en-PH', {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2
                    })
                } else {
                  label += context.parsed.y
                }
              }
              return label
            }
          }
        },
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            padding: 15
          }
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Month'
          }
        },
        yAmount: {
          type: 'linear',
          display: true,
          position: 'left',
          title: {
            display: true,
            text: 'Amount Paid (₱)'
          },
          ticks: {
            callback: function (value) {
              return '₱' + value.toLocaleString('en-PH')
            }
          }
        },
        yCount: {
          type: 'linear',
          display: true,
          position: 'right',
          title: {
            display: true,
            text: 'Number of Payments'
          },
          grid: {
            drawOnChartArea: false //only want the grid lines for one axis to show up
          },
          ticks: {
            beginAtZero: true,
            stepSize: 1
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

window.updatePaymentTrendsMonthlyChart = updatePaymentTrendsMonthlyChart
