let paymentHistoriesByMemberChart = null

async function updatePaymentHistoriesByMemberChart() {
  try {
    const response = await axios.get(
      '/app/router/api.php?action=get_payment_histories_by_member'
    )
    const { success, data } = response.data

    if (!success || !data) {
      throw new Error(
        'Failed to fetch payment histories by member data or data is missing.'
      )
    }

    // Aggregate total payments per member
    const memberPayments = data.reduce((acc, payment) => {
      const memberName = payment.full_name || 'Unknown Member'
      const amount = parseFloat(payment.amount) || 0

      if (!acc[memberName]) {
        acc[memberName] = 0
      }
      acc[memberName] += amount
      return acc
    }, {})

    const memberNames = Object.keys(memberPayments)
    const totalAmounts = Object.values(memberPayments)

    const chartData = {
      labels: memberNames,
      datasets: [
        {
          label: 'Total Payment Amount',
          data: totalAmounts,
          backgroundColor: [
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(199, 199, 199, 0.6)'
            // TODO: Add more colors if needed or use a function to generate them
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(199, 199, 199, 1)'
          ],
          borderWidth: 1
        }
      ]
    }

    renderPaymentHistoriesByMemberChart(chartData)
  } catch (error) {
    console.error(
      'Error fetching or processing payment histories by member data:',
      error
    )
    toastr.error('Failed to load payment histories by member data.')
    const chartContainer = document.getElementById(
      'PaymentHistoriesByMemberChart'
    ).parentNode
    chartContainer.innerHTML =
      '<p class="text-danger text-center">Could not load chart data.</p>'
  }
}

function renderPaymentHistoriesByMemberChart(chartData) {
  const ctx = document.getElementById('PaymentHistoriesByMemberChart')
  if (!ctx) {
    console.error('PaymentHistoriesByMemberChart canvas element not found.')
    return
  }

  if (paymentHistoriesByMemberChart) {
    paymentHistoriesByMemberChart.destroy()
  }

  paymentHistoriesByMemberChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y',
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
              if (context.parsed.x !== null) {
                label +=
                  '₱' +
                  context.parsed.x.toLocaleString('en-PH', {
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
            text: 'Total Amount Paid (₱)'
          },
          ticks: {
            callback: function (value) {
              return '₱' + value.toLocaleString('en-PH')
            }
          }
        },
        y: {
          title: {
            display: true,
            text: 'Member'
          },
          ticks: {
            autoSkip: false //ensure all member names are shown if possible
          }
        }
      }
    }
  })
}

window.updatePaymentHistoriesByMemberChart = updatePaymentHistoriesByMemberChart
