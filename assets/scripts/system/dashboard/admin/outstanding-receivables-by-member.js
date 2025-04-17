const outstandingReceivablesCtx = document
  .getElementById('OutstandingReceivablesByMemberChart')
  .getContext('2d')
let outstandingReceivablesChart

async function fetchAndRenderOutstandingReceivables() {
  try {
    const url =
      '/app/router/api.php?action=get_outstanding_receivables_by_member'
    const response = await axios.get(url)

    if (response.data.success) {
      const chartData = processOutstandingReceivablesData(response.data.data)
      renderOutstandingReceivablesChart(chartData)
    } else {
      toastr.error(
        response.data.message || 'Failed to load outstanding receivables data'
      )
    }
  } catch (error) {
    console.error('Error fetching outstanding receivables:', error)
    toastr.error(
      error.response?.data?.message ||
        'Failed to fetch outstanding receivables data'
    )
  }
}

function processOutstandingReceivablesData(data) {
  if (!data || data.length === 0) {
    return { labels: [], datasets: [] }
  }

  // Sort data by total_outstanding_receivable in descending order and take top N (e.g., top 10)
  const sortedData = data
    .sort(
      (a, b) =>
        parseFloat(b.total_outstanding_receivable) -
        parseFloat(a.total_outstanding_receivable)
    )
    .slice(0, 10) //Limit 10 members

  const labels = sortedData.map((item) => item.full_name)
  const amounts = sortedData.map((item) =>
    parseFloat(item.total_outstanding_receivable)
  )

  return {
    labels: labels,
    datasets: [
      {
        label: 'Outstanding Amount',
        data: amounts,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }
    ]
  }
}

// Function to render the chart
function renderOutstandingReceivablesChart(chartData) {
  if (outstandingReceivablesChart) {
    outstandingReceivablesChart.destroy()
  }

  outstandingReceivablesChart = new Chart(outstandingReceivablesCtx, {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y',
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              return `Outstanding: ₱${context.parsed.x.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
              })}`
            }
          }
        },
        legend: {
          display: false //hide legend as there's only one dataset
        },
        title: {
          display: true,
          text: 'Top 10 Members by Outstanding Receivables'
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return '₱' + value.toLocaleString('en-PH')
            }
          },
          title: {
            display: true,
            text: 'Outstanding Amount (₱)'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Member Name'
          }
        }
      }
    }
  })
}

window.fetchAndRenderOutstandingReceivables =
  fetchAndRenderOutstandingReceivables
