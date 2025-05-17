const dialyTransactionCtx = document
  .getElementById('dailyTransactionChart')
  .getContext('2d')
let transactionChart
const chartColors = {
  deposit: 'rgb(67, 97, 238)',
  withdrawal: 'rgb(88, 145, 192)',
  interest: 'rgb(75, 192, 192)',
  fee: 'rgb(153, 102, 255)',
  loan_payment: 'rgb(255, 159, 64)',
  credit: 'rgb(255, 99, 132)',
  credit_used: 'rgb(255, 205, 86)'
}

// Initialize date range picker with custom display format
const start = moment().subtract(7, 'days')
const end = moment()

const updateDateRangeText = async (start, end) => {
  document.querySelector('#dateRange span').innerHTML =
    start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')
  await loadDailyTransactions(
    start.format('YYYY-MM-DD'),
    end.format('YYYY-MM-DD')
  )
}

// Expose the loadDailyTransactions function globally
window.loadDailyTransactions = loadDailyTransactions

$('#dateRange').daterangepicker(
  {
    startDate: start,
    endDate: end,
    maxDate: moment(),
    ranges: {
      Today: [moment(), moment()],
      Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [
        moment().subtract(1, 'month').startOf('month'),
        moment().subtract(1, 'month').endOf('month')
      ]
    }
  },
  updateDateRangeText
)

// Initial date display
// await updateDateRangeText(start, end)

async function loadDailyTransactions(startDate = null, endDate = null) {
  try {
    let url = '/app/router/api.php?action=get_daily_transaction_stats'
    if (startDate && endDate) {
      url += `&start_date=${startDate}&end_date=${endDate}`
    }

    const response = await axios.get(url)
    if (response.data.success) {
      const chartData = processChartData(response.data.data)
      renderDailyTransactionChart(chartData)
    } else {
      toastr.error(response.data.message || 'Failed to load transaction data')
    }
  } catch (error) {
    console.error('Error:', error)
    toastr.error(error.response?.data?.message || 'Failed to fetch data')
  }
}

function processChartData(data) {
  if (!data || data.length === 0) {
    return { labels: [], datasets: [] }
  }

  // Get unique dates and transaction types
  const dates = [...new Set(data.map((item) => item.transaction_date))].sort()
  const types = [...new Set(data.map((item) => item.transaction_type))]

  // Create datasets for each transaction type
  const datasets = types.map((type) => {
    const typeData = dates.map((date) => {
      const entry = data.find(
        (item) =>
          item.transaction_date === date && item.transaction_type === type
      )
      return entry ? parseFloat(entry.total_amount) : 0
    })

    return {
      label: type.charAt(0).toUpperCase() + type.slice(1),
      data: typeData,
      backgroundColor: chartColors[type] || '#858796',
      borderColor: chartColors[type] || '#858796',
      borderWidth: 2,
      fill: false,
      tension: 0.1
    }
  })

  return {
    labels: dates.map((date) => {
      return new Date(date).toLocaleDateString('en-PH', {
        month: 'short',
        day: 'numeric'
      })
    }),
    datasets: datasets
  }
}

function renderDailyTransactionChart(chartData) {
  if (transactionChart) {
    transactionChart.destroy()
  }

  // Create gradients for each transaction type
  const gradients = {}
  Object.keys(chartColors).forEach((type) => {
    const gradient = dialyTransactionCtx.createLinearGradient(0, 0, 0, 300)
    const color = chartColors[type]
    gradient.addColorStop(
      0,
      color.replace('rgb', 'rgba').replace(')', ', 0.3)')
    )
    gradient.addColorStop(1, color.replace('rgb', 'rgba').replace(')', ', 0)'))
    gradients[type] = gradient
  })

  // Update datasets with gradients and styling
  const enhancedDatasets = chartData.datasets.map((dataset) => ({
    ...dataset,
    tension: 0.4,
    fill: true,
    backgroundColor: gradients[dataset.label.toLowerCase()],
    pointBackgroundColor: chartColors[dataset.label.toLowerCase()],
    pointBorderColor: '#ffffff',
    pointBorderWidth: 2,
    pointRadius: 4,
    pointHoverRadius: 6
  }))

  transactionChart = new Chart(dialyTransactionCtx, {
    type: 'line',
    data: {
      ...chartData,
      datasets: enhancedDatasets
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: function (context) {
              return `${
                context.dataset.label
              }: ₱${context.parsed.y.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
              })}`
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
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return '₱' + value.toLocaleString('en-PH')
            }
          },
          title: {
            display: true,
            text: 'Transaction Amount (₱)'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Date'
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

window.updateDateRangeText = updateDateRangeText
