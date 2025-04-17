const memberId = $('meta[name="member-id"]').attr('content')
//console.log('memberId', memberId)

const swalBase = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-light action-btn',
    cancelButton: 'btn btn-light action-btn',
    denyButton: 'btn btn-light action-btn',
    showLoaderOnConfirm: 'swal2-loader'
  },
  buttonsStyling: false
})

const $mainContent = $('.main-content')

let memberCreditBalance = 0

window.displayMemberDetails = async function () {
  try {
    const response = await fetchMember(memberId)
    if (response.success) {
      //console.log(response.data)
      const member = response.data
      memberCreditBalance = member.credit_balance
    }
  } catch (error) {
    console.error('Error fetching member details:', error)
  }
}
