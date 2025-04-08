<?php require_once __DIR__ . '/app/includes/layouts/begin.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
<?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php' 
?>

<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="border-bottom mb-4">
                                <div class="col-12">

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="module-title"><span class="text-danger">TODO:</span> Accounts Management</h4>
                                        <div>
                                            <button class="btn action-btn">
                                                Export
                                            </button>
                                            <button class="btn action-btn" id="addAccountBtn">
                                                <i class="fas fa-plus-circle me-2"></i>Create Account
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table id="accountsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Account ID</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <!-- <th>Username</th> -->
                                        <th>Status</th>
                                        <th>Verified</th>
                                        <th>Joined Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php' 
?>
<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>