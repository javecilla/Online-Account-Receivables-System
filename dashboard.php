<?php
require_once __DIR__ . '/app/helpers/system.php';
require_once __DIR__ . '/app/helpers/global.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Jerome Avecilla" />
    <meta name="github" content="https://github.com/javecilla/readme" />
    <meta name="website" content="https://jerome-avecilla.vercel.app/" />
    <meta name="title" content="Jerome Avecilla | IT211 Project" />
    <meta
        name="description"
        content="IT211 Project Online Accounts Receivable System for a Multipurpose Cooperative by Jerome Avecilla." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://jerome-avecilla.infinityfreeapp.com/" />
    <meta
        property="og:title"
        content="Jerome Avecilla | IT211 Project" />
    <meta
        property="og:description"
        content="IT211 Project Online Accounts Receivable System for a Multipurpose Cooperative by Jerome Avecilla." />
    <meta
        property="og:image"
        content="<?= get_base_url() ?>/assets/images/ogimage.png" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="img_profile" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:site_name" content="Jerome Avecilla | IT211 Project" />
    <meta
        property="article:author"
        content="https://www.facebook.com/jerome.avecilla24" />
    <meta
        property="article:publisher"
        content="https://www.facebook.com/jerome.avecilla24" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@_javecilla" />
    <meta name="twitter:creator" content="@_javecilla" />
    <meta
        name="twitter:title"
        content="Jerome Avecilla | IT211 Project" />
    <meta
        name="twitter:description"
        content="IT211 Project Online Accounts Receivable System for a Multipurpose Cooperative by Jerome Avecilla." />
    <meta
        name="twitter:image"
        content="<?= get_base_url() ?>/assets/images/ogimage.png" />
    <meta name="base-url" content="<?= get_base_url() ?>" />

    <title>[Test] Dashboard</title>
    <link
        rel="shortcut icon"
        type="image/png"
        href="<?= get_base_url() ?>/assets/images/favicon.png" />
    <link rel="preconnect" href="https://jerome-avecilla.vercel.app/" crossorigin />

    <!-- libs -->
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/libs/bootstrap/css/bootstrap.min.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/libs/fontawesome/css/all.min.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/libs/toastr/toastr.min.css" loading="lazy" />
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/libs/sweetalert2/dist/sweetalert2.min.css" loading="lazy" />
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/libs/daterangepicker/daterangepicker.css" loading="lazy" />

    <!-- app -->
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/stylesheets/common.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/stylesheets/layouts/sidebar.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/stylesheets/layouts/header.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/stylesheets/layouts/footer.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/stylesheets/layouts/responsive.css" loading="eager" />

    <link rel="stylesheet" type="text/css" href="<?= get_base_url() ?>/assets/stylesheets/system/dashboard.css" loading="eager" />
</head>

<body>
    <?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
    <?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
    <?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php' 
    ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <!-- Summary Cards Row -->
            <div class="row mb-4">
                <!-- Total Balances -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title"><i class="fas fa-wallet text-primary me-2"></i>Total Balances</h2>
                            <button class="btn btn-sm" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Total outstanding balances of all active members">
                                <i class="fas fa-info-circle text-secondary"></i>
                            </button>
                        </div>
                        <h3 class="card-value" id="totalBalanceValue">₱0.00</h3>
                        <p class="card-subtitle">Active Members: <span id="totalMemberCurrent">0</span></p>
                    </div>
                </div>
                <!-- Account Receivables -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title"><i class="fas fa-money-check text-success me-2"></i>Account Receivables</h2>
                            <button class="btn btn-sm" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Total receivable amount and number of active borrowers">
                                <i class="fas fa-info-circle text-secondary"></i>
                            </button>
                        </div>
                        <h3 class="card-value" id="totalReceivables">₱0.00</h3>
                        <p class="card-subtitle">Total Borrowers: <span id="totalBorrowers">0</span></p>

                    </div>
                </div>

                <!-- Overdue Summary -->
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title"><i class="fas fa-exclamation-circle text-warning me-2"></i>Overdue Summary</h2>
                            <button class="btn btn-sm" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Summary of overdue accounts and total overdue amount">
                                <i class="fas fa-info-circle text-secondary"></i>
                            </button>
                        </div>
                        <h3 class="card-value" id="overdueAmount">₱0.00</h3>
                        <p class="card-subtitle">Overdue Accounts: <span id="overdueAccounts">0</span></p>
                        <p class="card-subtitle">Overdue Rate: <span id="overduePercentage">0%</span></p>
                    </div>
                </div>
            </div>

            <!-- Daily Transaction Stats -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title">Daily Transaction Summary</h2>
                            <div id="dateRange" class="d-inline-block">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span>
                                <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="dailyTransactionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Balance Trends & Loan Performance Metrics-->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title">Monthly Balance Trends</h2>
                            <!-- filter by year -->
                            <select class="form-select" style="width: 90px;">
                                <option value="2025" selected>2025</option>
                                <!-- populate years dynamically -->
                            </select>
                        </div>
                        <div class="chart-container">
                            <canvas id="monthlyBalanceChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title">Loan Performance Metrics</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="loanPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TODO: Monthly Receivables Trend & Monthly Overdue Metrics -->
            <div class="row">
                <div class="col-md-7 mb-4">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title">TODO: Monthly_Receivables_Trend</h2>
                            <!-- <div id="dateRangeReceivabeleTrends" class="d-inline-block">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span>
                                <i class="fa fa-caret-down"></i>
                            </div> -->
                        </div>
                        <div class="chart-container">
                            <canvas id="monthlyReceivablesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mb-4">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title">TODO: Monthly_Overdue_Metrics</h2>
                            <!-- <div id="dateRangeOverdueMetrics" class="d-inline-block">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span>
                                <i class="fa fa-caret-down"></i>
                            </div> -->
                        </div>
                        <div class="chart-container">
                            <canvas id="monthlyOverdueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php //require_once __DIR__ . '/app/includes/layouts/footer.php' 
    ?>

    <!-- libs -->
    <script type=" text/javascript" src="<?= get_base_url() ?>/assets/libs/jquery/jquery-3.7.1.min.js" loading="eager">
    </script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js" loading="eager"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/libs/fontawesome/js/all.min.js" loading="eager"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/libs/axios/axios.min.js" loading="eager"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/libs/chartjs/chart.umd.js" loading="lazy"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/libs/toastr/toastr.min.js" loading="lazy"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/libs/sweetalert2/dist/sweetalert2.all.min.js" loading="lazy"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/libs/momentjs/moment.min.js" loading="lazy"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/libs/daterangepicker/daterangepicker.min.js" loading="lazy"></script>

    <!-- app -->
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/common.js" loading="eager"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/constants.js" loading="eager"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/helpers/global.js" loading="eager"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/helpers/system.js" loading="eager"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/layouts/header.js" loading="eager"></script>

    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/system/dashboard/daily-transactions.js" loading="lazy"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/system/dashboard/summary-metrics.js" loading="lazy"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/system/dashboard/monthly-balance-trends.js" loading="lazy"></script>
    <script type="text/javascript" src="<?= get_base_url() ?>/assets/scripts/system/dashboard/loan-performance-metrics.js" loading="lazy"></script>
</body>

</html>