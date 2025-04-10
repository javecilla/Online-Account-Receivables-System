/**
 * Member Tabs Functionality
 * Handles the tab navigation for member information, amortilization, and transaction history
 */

$(document).ready(function () {
  // Initialize tab functionality
  const $memberContentTabs = $('#memberContentTabs')
  const $memberTabContent = $('#memberContentTabsContent')

  // Function to activate a specific tab
  window.activateMemberTab = function (tabId) {
    // Remove active class from all tabs
    $memberContentTabs.find('.nav-link').removeClass('active')
    $memberTabContent.find('.tab-pane').removeClass('show active')

    // Add active class to selected tab
    $(`#${tabId}-tab`).addClass('active')
    $(`#${tabId}`).addClass('show active')
  }

  // Handle tab clicks
  $memberContentTabs.find('.nav-link').on('click', function (e) {
    e.preventDefault()
    const target = $(this).attr('data-bs-target').replace('#', '')
    activateMemberTab(target)
  })

  // Default to member-info tab when viewing member details
  $('#backToTableContainerBtn').on('click', function () {
    // Reset to default tab when going back to table view
    setTimeout(function () {
      activateMemberTab('member-info')
    }, 100)
  })
})
