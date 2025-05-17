<?php require_once __DIR__ . '/app/includes/layouts/begin.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
<?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php' 
$type_name = isset($_GET['type_name']) && !empty($_GET['type_name']) ? urldecode($_GET['type_name']) : '';
?>

<!-- Main Content -->
<main class="main-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="border-bottom mb-4">
              <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                      <h4 class="module-title"><?= $type_name ?> Management: </h4>
                      <div class="tableContainerContent">
                          <!-- <button class="btn action-btn" id="aboutUsBtn">
                              <i class="fas fa-info-circle me-2"></i> About Us
                          </button> -->
                      </div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="row mb-3">
                  <label for="accountHolderName" class="col-sm-3 col-form-label">Account #:</label>
                  <div class="col-sm-9">
                      <select class="form-control" id="accountSelection">
                          <option value="21">Jerome Avecilla (M267066)</option>
                      </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="accountHolderName" class="col-sm-3 col-form-label">Account Holder:</label>
                  <div class="col-sm-9">
                      <input type="text" class="form-control readonly" id="accountHolderName" readonly/>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="accountType" class="col-sm-3 col-form-label">Account Type:</label>
                  <div class="col-sm-9">
                      <input type="text" class="form-control readonly" id="accountType" value="<?= $type_name ?>" readonly/>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row mb-3">
                  <label for="date" class="col-sm-3 col-form-label">Date:</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control readonly" id="date" value="<?= date('Y-m-d') ?>" readonly />
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="currentBalance" class="col-sm-3 col-form-label">Current Balance:</label>
                  <div class="col-sm-9">
                      <input type="text" class="form-control readonly" id="currentBalance" readonly/>
                  </div>
                </div>
              </div>
            </div>
            <hr style="color: #f3f3f3"/>
            <div class="row mt-4">
              <div class="col-md-8">
                <div class="table-reponsive">
                  <table class="table table-bordered table-striped transactionTable">
                    <thead>
                      <tr>
                        <th>Transaction #</th>
                        <th>Amount</th>
                        <th>Transaction Type</th>
                        <th>Date</th>
                        <th>Processed By</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th colspan="5" class="text-center">No records found.</th>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="4">Running Balance:</th>
                        <th>0.00</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              <div class="col-md-4">
                <!-- <div class="d-flex">
                  <button type="button" class="btn action-btn active">Withdraw</button>
                  <button type="button" class="btn action-btn">Deposit</button>
                  <button type="button" class="btn action-btn">Check Balance</button>
                </div> -->
                <div class="d-flex" id="accountActions">
                  <!-- Action buttons will be dynamically generated based on account type -->
                </div>
                <div id="depositForm" class="mt-3" style="display: none">
                  <div class="row mb-3">
                    <label for="daAmountInput" class="col-sm-3 col-form-label">Amount:</label>
                    <div class="col-sm-9">
                        <input type="hidden" class="form-control mb-3" id="daCaid"/>
                        <input type="hidden" class="form-control mb-3" id="daMemberId"/>
                        <input type="text" class="form-control input-amount mb-3" id="daAmountInput"/>
                        <button type="button" class="btn action-btn" onclick="$('#daAmountInput').val('').focus()">Clear</button>
                        <button type="button" class="btn action-btn" id="daSubmitBtn">Submit</button>
                    </div>
                  </div>
                </div>
                <div id="withdrawForm" class="mt-3" style="display: none">
                  <div class="row mb-3">
                    <label for="waAmountInput" class="col-sm-3 col-form-label">Amount:</label>
                    <div class="col-sm-9">
                        <input type="hidden" class="form-control mb-3" id="waCaid"/>
                        <input type="hidden" class="form-control mb-3" id="waMemberId"/>
                        <input type="text" class="form-control  input-amount mb-3" id="waAmountInput"/>
                        <button type="button" class="btn action-btn" onclick="$('#waAmountInput').val('').focus()">Clear</button>
                        <button type="button" class="btn action-btn" id="waSubmitBtn">Submit</button>
                    </div>
                  </div>
                </div>
                <div id="checkBalanceForm" class="mt-3" style="display: none">
                  <h1 style="font-size: 1.5rem; font-weight: bold"><span class="text-secondary">Current Balance:</span> <span id="cbCurrentBalance">₱0.00</span></h1>
                </div>
              </div>
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