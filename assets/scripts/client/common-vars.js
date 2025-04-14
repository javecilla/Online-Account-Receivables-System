const memberId = $('meta[name="member-id"]').attr('content')
//console.log('memberId', memberId)

const swalBase = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-light action-btn',
    cancelButton: 'btn btn-light action-btn',
    showLoaderOnConfirm: 'swal2-loader'
  },
  buttonsStyling: false
})

const $mainContent = $('.main-content')
