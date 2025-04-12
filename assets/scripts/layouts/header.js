$(document).ready(function () {
  // Initialize Bootstrap dropdowns if not already initialized by Bootstrap
  if (typeof bootstrap !== 'undefined') {
    const dropdownElementList = $('.dropdown-toggle')
    const dropdownList = dropdownElementList.map(function () {
      return new bootstrap.Dropdown(this)
    })
  }

  // Toggle notification dropdown manually if needed
  const notificationBtn = $('#notificationDropdown')
  if (notificationBtn.length) {
    notificationBtn.on('click', function (e) {
      // Custom notification handling if needed
      console.log('Notification dropdown clicked')
    })
  }

  // Toggle profile dropdown manually if needed
  const profileBtn = $('#profileDropdown')
  if (profileBtn.length) {
    profileBtn.on('click', function (e) {
      // Custom profile dropdown handling if needed
      console.log('Profile dropdown clicked')
    })
  }

  // Close dropdowns when clicking outside
  $(document).on('click', function (e) {
    // Close notification dropdown when clicking outside
    if (
      !$(e.target).closest('.notification-dropdown').length &&
      $('.notification-dropdown .dropdown-menu.show').length
    ) {
      const dropdownElement = bootstrap.Dropdown.getInstance(notificationBtn[0])
      if (dropdownElement) {
        dropdownElement.hide()
      }
    }

    // Close profile dropdown when clicking outside
    if (
      !$(e.target).closest('.profile-dropdown').length &&
      $('.profile-dropdown .dropdown-menu.show').length
    ) {
      const dropdownElement = bootstrap.Dropdown.getInstance(profileBtn[0])
      if (dropdownElement) {
        dropdownElement.hide()
      }
    }
  })

  $('#logoutBtn').click(async function () {
    try {
      $('#logoutBtn').text('Logging you out...').prop('disabled', true)
      LoadingManager.show($('.main-content'))
      const response = await logoutAccount()
      if (response.success) {
        window.location.href = response.redirect
      }
    } catch (error) {
      $('#logoutBtn')
        .html('<i class="fas fa-sign-out-alt"></i> Logout')
        .prop('disabled', false)
      LoadingManager.hide($('.main-content'))
      console.error('Error logout:', error)
    }
  })
})
