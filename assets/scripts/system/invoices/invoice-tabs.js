$(document).ready(function () {
  const $invoiceContentTabs = $('#invoiceContentTabs')
  const $invoicesTabsContent = $('#invoicesTabsContent')

  window.activateAmortizationTab = function (tabId) {
    $invoiceContentTabs.find('.nav-link').removeClass('active')
    $invoicesTabsContent.find('.tab-pane').removeClass('show active')

    $(`#${tabId}-tab`).addClass('active')
    $(`#${tabId}`).addClass('show active')
  }

  $invoiceContentTabs.find('.nav-link').on('click', function (e) {
    e.preventDefault()
    const target = $(this).attr('data-bs-target').replace('#', '')
    activateAmortizationTab(target)
  })

  // $('#testBtn').on('click', function () {
  //   setTimeout(function () {
  //     activateAmortizationTab('amortization')
  //   }, 100)
  // })
})
