window.DataTableAccounts = function ($accountsTable, data) {
  if ($.fn.DataTable.isDataTable($accountsTable)) {
    $accountsTable.DataTable().destroy()
    $accountsTable.empty()
  }

  let dataTable = $($accountsTable).DataTable({
    responsive: true,
    processing: true,
    data: data,
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
      { data: 'account_uid', title: 'Account ID', orderable: false },
      { data: 'role_name', title: 'Role' },
      { data: 'email', title: 'Email' },
      // { data: 'username', title: 'Username' },
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
          const dataFor = data.role_name === 'Member' ? 'member' : 'employee'
          return `
            <div class="dropdown" id="requestAmortizationActionDropdown">
              <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars me-1"></i><i class="fas fa-chevron-down" style="font-size: 8px; margin-bottom: 2px"></i>
              </button>
              <ul class="dropdown-menu profile-menu" aria-labelledby="requestAmortizationActionDropdown">
                <li><a class="dropdown-item view-btn" href="javascript:void(0)" data-id="${data.account_id}" data-for="${dataFor}"><i class="fas fa-eye"></i> View Account</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item edit-btn" href="javascript:void(0)" data-id="${data.account_id}" data-for="${dataFor}"><i class="fas fa-edit"></i> Update Details</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item update-status-btn" href="javascript:void(0)" data-id="${data.account_id}" data-status="${data.account_status}" data-email="${data.email}" data-for="${dataFor}"><i class="fas fa-edit"></i> Update Status</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item delete-btn" href="javascript:void(0)" data-id="${data.account_id}" data-for="${dataFor}"><i class="fas fa-trash"></i> Delete Account</a></li>
              </ul>
            </div>
          `
        }
      }
      // {
      //   data: 'created_at',
      //   visible: false
      // }
    ],
    order: [[5, 'desc']]
  })

  return dataTable
}
