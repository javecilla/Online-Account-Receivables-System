$(document).ready(function () {
  // Get the current page from meta tag and remove extension
  const currentPage = REQUEST_FILE_NAME.replace(/\.php$/, '')
  //console.log('currentPage', currentPage)
  const titlePage = $('#pageTitle')

  // Get all sidebar links
  const sidebarLinks = $('.sidebar-menu a')

  // Loop through each link and check if its href matches the current page
  sidebarLinks.each(function () {
    // Extract the last part of the URL (the page name)
    const href = $(this).attr('href')
    const hrefPage = href.split('/').pop()

    // console.log('href page', hrefPage)
    // console.log('data href page', dataHrefPage)
    // console.log('matches current:', hrefPage === currentPage)
    if (href === 'javascript:void(0)') {
      const dataHref = $(this).data('href')
      const dataHrefPage = dataHref.split('/').pop()

      if (dataHref && dataHrefPage === currentPage) {
        $(this).addClass('active')
        titlePage.text($(this).attr('title'))
      }
    } else {
      if (href && hrefPage === currentPage) {
        $(this).addClass('active')
        titlePage.text($(this).attr('title'))
      }
    }
  })
})
