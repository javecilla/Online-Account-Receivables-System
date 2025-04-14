const $memberTransactionsHistoryTable = $('#memberTransactionsHistoryTable')

$(document).ready(async function () {
  window.displayMemberTransactionsHistory = async function () {
    try {
      const transactions = await fetchMemberTransactionsHistory(memberId)
      if (transactions.success) {
        DataTableMemberTransactionsHistory(
          $memberTransactionsHistoryTable,
          transactions.data
        )
      }
    } catch (error) {
      console.error('Error fetching member transactions:', error)
    }

    $memberTransactionsHistoryTable.on('click', '.notes-btn', function () {
      const transactionId = $(this).data('id')
      const notes = $(this).data('notes')
      //toastr.info(`Transaction ID: ${transactionId}`)
      swalBase.fire({
        title: 'Notes:',
        html: `<small>${notes}</small>`
        //icon: 'info'
      })
    })
  }
})
