$(document).ready(function () {
  // Initialize tab functionality
  const $amortizationContentTabs = $('#amortizationContentTabs')
  const $amortizationTabContent = $('#amortizationContentTabsContent')

  // Function to activate a specific tab
  window.activateAmortizationTab = function (tabId) {
    // Remove active class from all tabs
    $amortizationContentTabs.find('.nav-link').removeClass('active')
    $amortizationTabContent.find('.tab-pane').removeClass('show active')

    // Add active class to selected tab
    $(`#${tabId}-tab`).addClass('active')
    $(`#${tabId}`).addClass('show active')
  }

  // Handle tab clicks
  $amortizationContentTabs.find('.nav-link').on('click', function (e) {
    e.preventDefault()
    const target = $(this).attr('data-bs-target').replace('#', '')
    activateAmortizationTab(target)
  })

  // Default to member-info tab when viewing member details
  $('#backToTableContainerBtn').on('click', function () {
    // Reset to default tab when going back to table view
    setTimeout(function () {
      activateAmortizationTab('amortization')
    }, 100)
  })
})
