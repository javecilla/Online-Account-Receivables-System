/**
 * Header JavaScript functionality
 * Handles notification and profile dropdowns
 */

document.addEventListener('DOMContentLoaded', function () {
  // Initialize Bootstrap dropdowns if not already initialized by Bootstrap
  if (typeof bootstrap !== 'undefined') {
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
    const dropdownList = [...dropdownElementList].map(
      (dropdownToggleEl) => new bootstrap.Dropdown(dropdownToggleEl)
    )
  }

  // Toggle notification dropdown manually if needed
  const notificationBtn = document.getElementById('notificationDropdown')
  if (notificationBtn) {
    notificationBtn.addEventListener('click', function (e) {
      // Custom notification handling if needed
      // For example, mark notifications as read when opened
      console.log('Notification dropdown clicked')
    })
  }

  // Toggle profile dropdown manually if needed
  const profileBtn = document.getElementById('profileDropdown')
  if (profileBtn) {
    profileBtn.addEventListener('click', function (e) {
      // Custom profile dropdown handling if needed
      console.log('Profile dropdown clicked')
    })
  }

  // Close dropdowns when clicking outside
  document.addEventListener('click', function (e) {
    // Close notification dropdown when clicking outside
    if (
      !e.target.closest('.notification-dropdown') &&
      document.querySelector('.notification-dropdown .dropdown-menu.show')
    ) {
      const dropdownElement = bootstrap.Dropdown.getInstance(notificationBtn)
      if (dropdownElement) {
        dropdownElement.hide()
      }
    }

    // Close profile dropdown when clicking outside
    if (
      !e.target.closest('.profile-dropdown') &&
      document.querySelector('.profile-dropdown .dropdown-menu.show')
    ) {
      const dropdownElement = bootstrap.Dropdown.getInstance(profileBtn)
      if (dropdownElement) {
        dropdownElement.hide()
      }
    }
  })
})
