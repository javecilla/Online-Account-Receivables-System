// Initialize chart data and chart instance
let loanPerformanceChart = null
const ctx = document.getElementById('loanPerformanceChart').getContext('2d')

// Fetch loan performance data and render chart
async function fetchAndRenderLoanPerformanceData() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_loan_performance_metrics'
    )
    const { data } = response.data

    // Prepare data for chart
    const chartData = {
      labels: data.map((item) => item.loan_type),
      datasets: [
        {
          label: 'Total Principal',
          data: data.map((item) => parseFloat(item.total_principal)),
          backgroundColor: '#2563eb', // Deep blue
          order: 1
        },
        {
          label: 'Remaining Amount',
          data: data.map((item) => parseFloat(item.total_remaining)),
          backgroundColor: '#f97316', // Warm orange
          order: 2
        },
        {
          label: 'Active Loans',
          data: data.map((item) => parseInt(item.active_loans)),
          type: 'line',
          borderColor: '#22c55e', // Vibrant green
          borderWidth: 2,
          fill: false,
          tension: 0.4,
          order: 0
        }
      ]
    }

    renderChart(chartData)
  } catch (error) {
    console.error('Error fetching loan performance data:', error)
  }
}

// Render the chart with the provided data
function renderChart(chartData) {
  if (loanPerformanceChart) {
    loanPerformanceChart.destroy()
  }

  loanPerformanceChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        title: {
          display: true,
          text: 'Loan Performance Overview',
          font: {
            size: 16,
            weight: 'bold'
          },
          padding: 20
        },
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            padding: 15
          }
        },
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: function (context) {
              const value = context.raw
              if (context.dataset.type === 'line') {
                return `${context.dataset.label}: ${value}`
              }
              return `${context.dataset.label}: ₱${value.toLocaleString(
                'en-PH',
                {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
                }
              )}`
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return '₱' + value.toLocaleString('en-PH')
            }
          },
          title: {
            display: true,
            text: 'Amount (₱)'
          }
        },
        y1: {
          position: 'right',
          beginAtZero: true,
          grid: {
            drawOnChartArea: false
          },
          title: {
            display: true,
            text: 'Number of Loans'
          }
        }
      }
    }
  })
}

// Initialize chart when DOM is loaded
document.addEventListener('DOMContentLoaded', fetchAndRenderLoanPerformanceData)
