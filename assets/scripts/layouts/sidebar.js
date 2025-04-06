document.addEventListener('DOMContentLoaded', function () {
  // Get the current page from meta tag and remove extension
  const currentPage = REQUEST_FILE_NAME.replace(/\.php$/, '')
  //console.log('currentPage', currentPage)
  const titlePage = document.querySelector('#pageTitle')

  // Get all sidebar links
  const sidebarLinks = document.querySelectorAll('.sidebar-menu a')

  // Loop through each link and check if its href matches the current page
  sidebarLinks.forEach((link) => {
    // Extract the last part of the URL (the page name)
    const href = link.getAttribute('href')
    const hrefPage = href.split('/').pop()
    // console.log('href page', hrefPage)
    // console.log('matches current:', hrefPage === currentPage)
    if (href && hrefPage === currentPage) {
      link.classList.add('active')
      titlePage.innerText = link.getAttribute('title')
    }
  })
})
