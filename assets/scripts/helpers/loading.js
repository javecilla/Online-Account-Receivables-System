// Loading state management utility
const LoadingManager = {
  show: function (container) {
    // Create loading overlay if it doesn't exist
    let $loadingOverlay = $(container).find('.loading-overlay')
    if ($loadingOverlay.length === 0) {
      $loadingOverlay = $('<div class="loading-overlay">')
        .append(
          '<div class="loading-spinner"><i class="fas fa-cog fa-spin"></i></div>'
        )
        .css({
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          backgroundColor: 'rgba(255, 255, 255, 0.8)',
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          zIndex: 1000
        })
      $(container).css('position', 'relative').append($loadingOverlay)
    }
    $loadingOverlay.fadeIn(200)
  },

  hide: function (container) {
    const $loadingOverlay = $(container).find('.loading-overlay')
    if ($loadingOverlay.length > 0) {
      $loadingOverlay.fadeOut(200, function () {
        $(this).remove()
      })
    }
  }
}

// Export the LoadingManager
window.LoadingManager = LoadingManager
