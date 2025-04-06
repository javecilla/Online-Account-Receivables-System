document.addEventListener('DOMContentLoaded', () => {
  const monthlyBalanceChart = document.getElementById('monthlyBalanceChart')
  let chart = null

  // Get current year for initial load
  const currentYear = new Date().getFullYear()
  const yearSelect = document.querySelector('.form-select')

  // Populate year select with 5 years (current year + 4 future years)
  for (let year = currentYear; year <= currentYear + 4; year++) {
    const option = document.createElement('option')
    option.value = year
    option.textContent = year
    option.selected = year === 2025 // Default to 2025 as per requirements
    yearSelect.appendChild(option)
  }

  // Function to format currency
  const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
      style: 'currency',
      currency: 'PHP'
    }).format(value)
  }

  // Function to fetch and update chart data
  const updateChart = (year) => {
    axios
      .get(`/app/router/api.php?action=get_monthly_balance_trends&year=${year}`)
      .then((response) => {
        if (response.data.success) {
          //console.log('Monthly Balance Trends Data:', response.data.data)
          const data = response.data.data

          // Process data for chart
          const accountTypes = [
            ...new Set(data.map((item) => item.account_type))
          ]

          // Initialize datasets with zero values for all months
          const datasets = accountTypes.map((type) => {
            return {
              label: type,
              data: Array(12).fill(0), // Initialize with zeros for all months
              backgroundColor: getAccountTypeColor(type),
              borderColor: getAccountTypeColor(type),
              borderWidth: 1
            }
          })

          // Populate data for months that have values
          data.forEach((item) => {
            const monthIndex = parseInt(item.month_year.split('-')[1]) - 1 // Convert month to 0-based index
            const typeIndex = accountTypes.indexOf(item.account_type)
            if (typeIndex !== -1 && monthIndex >= 0 && monthIndex < 12) {
              datasets[typeIndex].data[monthIndex] = parseFloat(
                item.total_transactions
              )
            }
          })

          // Destroy existing chart if it exists
          if (chart) {
            chart.destroy()
          }

          // Create new chart
          chart = new Chart(monthlyBalanceChart, {
            type: 'bar',
            data: {
              labels: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
              ],
              datasets: datasets
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                x: {
                  stacked: true,
                  grid: {
                    display: false
                  }
                },
                y: {
                  stacked: true,
                  beginAtZero: true,
                  ticks: {
                    callback: function (value) {
                      return formatCurrency(value)
                    }
                  }
                }
              },
              plugins: {
                legend: {
                  position: 'bottom',
                  labels: {
                    usePointStyle: true,
                    padding: 20
                  }
                },
                tooltip: {
                  callbacks: {
                    label: function (context) {
                      return `${context.dataset.label}: ${formatCurrency(
                        context.raw
                      )}`
                    }
                  }
                }
              }
            }
          })
        }
      })
      .catch((error) => {
        console.error('Error fetching monthly balance trends:', error)
        toastr.error('Failed to load monthly balance trends data')
      })
  }

  // Function to get color for each account type
  const getAccountTypeColor = (type) => {
    const colors = {
      'Fixed Deposit': getComputedStyle(
        document.documentElement
      ).getPropertyValue('--primary-color'),
      Loan: getComputedStyle(document.documentElement).getPropertyValue(
        '--danger-color'
      ),
      'Savings Account': getComputedStyle(
        document.documentElement
      ).getPropertyValue('--success-color'),
      'Special Savings': getComputedStyle(
        document.documentElement
      ).getPropertyValue('--warning-color'),
      'Time Deposit': getComputedStyle(
        document.documentElement
      ).getPropertyValue('--info-color')
    }
    return colors[type] || '#6c757d' // Default color if type not found
  }

  // Event listener for year selection
  yearSelect.addEventListener('change', (e) => {
    updateChart(e.target.value)
  })

  // Initial chart load
  updateChart(2025)
})
