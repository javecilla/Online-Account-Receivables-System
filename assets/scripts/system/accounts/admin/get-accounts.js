document.addEventListener('DOMContentLoaded', async function () {
  //console.log(API_URL)
  const accountsTable = document.getElementById('accountsTable')
  let dataTable = $(accountsTable).DataTable({
    responsive: true,
    processing: true,
    language: {
      processing:
        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
      emptyTable: 'Loading accounts records...',
      zeroRecords: 'No matching accounts found',
      info: 'Showing _START_ to _END_ of _TOTAL_ Accounts',
      infoEmpty: 'Showing 0 to 0 of 0 Accounts',
      infoFiltered: '(filtered from _MAX_ total Accounts)',
      search: 'Search Account:',
      lengthMenu: 'Show _MENU_ Accounts',
      paginate: {
        first: '<i class="fas fa-angle-double-left"></i>',
        previous: '<i class="fas fa-angle-left"></i>',
        next: '<i class="fas fa-angle-right"></i>',
        last: '<i class="fas fa-angle-double-right"></i>'
      }
    },
    columns: [
      { data: 'account_uid', title: 'Account ID' },
      { data: 'role_name', title: 'Role' },
      { data: 'email', title: 'Email' },
      // { data: 'username', title: 'Username' },
      {
        data: 'account_status',
        title: 'Status',
        render: function (data) {
          const statusClass =
            data === 'active' ? 'status-active' : 'status-inactive'
          const icon = data === 'active' ? 'fa-check-circle' : 'fa-times-circle'
          return `<span class="status-badge ${statusClass}"><i class="fas ${icon}"></i>&nbsp;${
            data.charAt(0).toUpperCase() + data.slice(1)
          }</span>`
        }
      },
      {
        data: 'email_verified_at',
        title: 'Verified',
        render: function (data) {
          const verifiedClass =
            data !== null ? 'status-verified-yes' : 'status-verified-no'
          const icon = data !== null ? 'fa-shield-alt' : 'fa-circle-exclamation'
          const statusText = data !== null ? 'Verified' : 'Not Verified'
          return `<span class="status-badge ${verifiedClass}"><i class="fas ${icon}"></i>&nbsp;${statusText}</span>`
        }
      },
      {
        data: 'created_at',
        title: 'Joined Date',
        render: function (data) {
          return moment(data).format('DD MMM YYYY, h:mm A')
        }
      },
      {
        data: null,
        title: 'Actions',
        orderable: false,
        render: function (data) {
          return `
            <div class="d-flex">
              <button class="btn btn-sm action-btn view-btn" data-id="${data.account_id}">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn btn-sm action-btn edit-btn" data-id="${data.account_id}">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm action-btn delete-btn" data-id="${data.account_id}">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          `
        }
      }
    ],
    order: [[5, 'desc']] // Sort by created_at by default
  })

  async function fetchAccounts() {
    try {
      $(accountsTable).addClass('loading')

      const response = await axios.get(`${API_URL}?action=get_accounts`)
      if (response.data.success) {
        dataTable.clear()
        dataTable.rows.add(response.data.data.items)
        dataTable.draw()
        const metaData = response.data.data.meta_data
        $(accountsTable).removeClass('loading')
      } else {
        console.error('Error fetching accounts:', response.data.message)
        toastr.error('Failed to load accounts data')
      }
    } catch (error) {
      console.error('Error fetching accounts:', error)
      toastr.error('An error occurred while fetching accounts data')
    } finally {
      $(accountsTable).removeClass('loading')
    }
  }

  // Initial data fetch
  await fetchAccounts()

  // Event listeners for action buttons
  $(accountsTable).on('click', '.view-btn', function () {
    const accountId = $(this).data('id')
    // Implement view account functionality
    toastr.info(`View account ${accountId} - Feature coming soon`)
  })

  $(accountsTable).on('click', '.edit-btn', function () {
    const accountId = $(this).data('id')
    // Implement edit account functionality
    toastr.info(`Edit account ${accountId} - Feature coming soon`)
  })

  $(accountsTable).on('click', '.delete-btn', function () {
    const accountId = $(this).data('id')
    // // Implement delete account functionality with confirmation
    // Swal.fire({
    //   title: 'Are you sure?',
    //   text: "You won't be able to revert this!",
    //   icon: 'warning',
    //   showCancelButton: true,
    //   confirmButtonColor: '#3085d6',
    //   cancelButtonColor: '#d33',
    //   confirmButtonText: 'Yes, delete it!'
    // }).then((result) => {
    //   if (result.isConfirmed) {
    //     // Here you would make an API call to delete the account
    //     toastr.success(
    //       `Account ${accountId} has been deleted - Feature coming soon`
    //     )
    //   }
    // })
    toastr.info(`Delete account ${accountId} - Feature coming soon`)
  })

  // Add account button event listener
  document
    .getElementById('addAccountBtn')
    .addEventListener('click', function () {
      // Implement add account functionality
      toastr.info('Add account feature coming soon')
    })
})
