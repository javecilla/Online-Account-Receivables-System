document.addEventListener('DOMContentLoaded', function () {
  // Initialize chart and date range picker
  let monthlyReceivablesChart = null
  const monthlyReceivablesCtx = document.getElementById(
    'monthlyReceivablesChart'
  )

  // Initialize date range picker
  $('#dateRangeReceivabeleTrends').daterangepicker(
    {
      startDate: moment().subtract(6, 'months'),
      endDate: moment(),
      ranges: {
        'Last 6 Months': [moment().subtract(6, 'months'), moment()],
        'Last Year': [moment().subtract(1, 'year'), moment()],
        'Year to Date': [moment().startOf('year'), moment()]
      },
      locale: {
        format: 'MMM DD, YYYY'
      }
    },
    function (start, end) {
      fetchAndRenderData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
    }
  )

  // Initial data fetch
  fetchAndRenderData(
    moment().subtract(6, 'months').format('YYYY-MM-DD'),
    moment().format('YYYY-MM-DD')
  )

  // Fetch data and render chart
  async function fetchAndRenderData(startDate, endDate) {
    try {
      const response = await axios.get(
        '/app/router/api.php?action=get_monthly_receivables_trend'
      )
      const { data } = response.data

      // Sort data by date
      data.sort((a, b) => new Date(a.month_date) - new Date(b.month_date))

      // Prepare data for chart
      const chartData = {
        labels: data.map((item) => moment(item.month_date).format('MMM YYYY')),
        datasets: [
          {
            label: 'Total Principal',
            data: data.map((item) => parseFloat(item.total_principal)),
            type: 'bar',
            backgroundColor: '#2563eb',
            order: 2
          },
          {
            label: 'Remaining Balance',
            data: data.map((item) => parseFloat(item.total_remaining)),
            type: 'bar',
            backgroundColor: '#f97316',
            order: 1
          },
          {
            label: 'Collection Rate (%)',
            data: data.map((item) => parseFloat(item.collection_rate)),
            type: 'line',
            borderColor: '#22c55e',
            backgroundColor: '#22c55e',
            borderWidth: 2,
            fill: false,
            tension: 0.4,
            yAxisID: 'percentage',
            order: 0
          }
        ]
      }

      renderChart(chartData)
    } catch (error) {
      console.error('Error fetching monthly receivables data:', error)
      toastr.error('Failed to load monthly receivables data')
    }
  }

  // Render chart function
  function renderChart(chartData) {
    if (monthlyReceivablesChart) {
      monthlyReceivablesChart.destroy()
    }

    monthlyReceivablesChart = new Chart(monthlyReceivablesCtx, {
      data: chartData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Monthly Receivables Trend',
            font: {
              size: 16,
              weight: 'bold'
            }
          },
          tooltip: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: function (context) {
                if (context.dataset.yAxisID === 'percentage') {
                  return `${context.dataset.label}: ${context.parsed.y.toFixed(
                    2
                  )}%`
                }
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
          x: {
            stacked: true,
            title: {
              display: true,
              text: 'Month'
            }
          },
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            stacked: false,
            title: {
              display: true,
              text: 'Amount (₱)'
            },
            ticks: {
              callback: function (value) {
                return '₱' + value.toLocaleString('en-PH')
              }
            }
          },
          percentage: {
            type: 'linear',
            display: true,
            position: 'right',
            title: {
              display: true,
              text: 'Collection Rate (%)'
            },
            grid: {
              drawOnChartArea: false
            },
            ticks: {
              callback: function (value) {
                return value + '%'
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
})
