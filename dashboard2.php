<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <!-- libs -->
    <link rel="stylesheet" type="text/css" href="/assets/libs/bootstrap/css/bootstrap.min.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="/assets/libs/fontawesome/css/all.min.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="/assets/libs/toastr/toastr.min.css" loading="lazy" />
    <link rel="stylesheet" type="text/css" href="/assets/libs/sweetalert2/dist/sweetalert2.min.css" loading="lazy" />
    <link rel="stylesheet" type="text/css" href="/assets/libs/daterangepicker/daterangepicker.css" loading="lazy" />

    <link rel="stylesheet" type="text/css" href="/assets/stylesheets/common.css" loading="eager" />

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --info-color: #4895ef;
            --warning-color: #f72585;
            --danger-color: #e63946;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            min-height: 100vh;
        }

        .sidebar {
            background-color: #ffffff;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 60px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: #6c757d;
            padding: 15px 0;
            text-align: center;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-color);
        }

        .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(13, 110, 253, 0.1);
        }

        .main-content {
            margin-left: 60px;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container {
            position: relative;
        }

        .search-container input {
            padding-left: 35px;
            border-radius: 20px;
            border: 1px solid #ced4da;
        }

        .search-container i {
            position: absolute;
            left: 12px;
            top: 10px;
            color: #6c757d;
        }

        .stats-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stats-card .icon {
            font-size: 24px;
            margin-bottom: 15px;
            display: inline-block;
            padding: 10px;
            border-radius: 10px;
        }

        .stats-card .download-icon {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
        }

        .stats-card .uninstall-icon {
            background-color: rgba(231, 57, 70, 0.1);
            color: var(--danger-color);
        }

        .stats-card .member-icon {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success-color);
        }

        .stats-card .title {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .stats-card .value {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stats-card .percentage {
            font-size: 0.8rem;
            padding: 3px 8px;
            border-radius: 20px;
            display: inline-block;
        }

        .stats-card .percentage.positive {
            background-color: rgba(76, 201, 240, 0.2);
            color: var(--success-color);
        }

        .stats-card .percentage.negative {
            background-color: rgba(231, 57, 70, 0.2);
            color: var(--danger-color);
        }

        .stats-card .period {
            font-size: 0.8rem;
            color: #6c757d;
            margin-left: 10px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .device-bar {
            height: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            background-color: #0d6efd;
        }

        .device-label {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .device-value {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .product-table img {
            width: 40px;
            height: 40px;
            border-radius: 5px;
            object-fit: cover;
        }

        .product-table .product-name {
            font-weight: 500;
        }

        .product-table .product-category {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .age-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .age-item {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
        }

        .age-color {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .view-more {
            text-align: right;
            margin-top: 10px;
        }

        .view-more a {
            font-size: 0.9rem;
            color: var(--primary-color);
            text-decoration: none;
        }

        .view-more a i {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-bar"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-file-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-envelope"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-cog"></i>
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link" href="#">
                    <i class="fas fa-user-circle"></i>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h5>Dashboard</h5>
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="Search......">
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="icon download-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="title">Total Download Apps</div>
                    <div class="value">804,892</div>
                    <div>
                        <span class="percentage positive">
                            <i class="fas fa-arrow-up"></i> 12%
                        </span>
                        <span class="period">From <i class="far fa-calendar"></i> last month</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="icon uninstall-icon">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="title">Total Uninstall Apps</div>
                    <div class="value">22,671</div>
                    <div>
                        <span class="percentage negative">
                            <i class="fas fa-arrow-down"></i> 24%
                        </span>
                        <span class="period">From <i class="far fa-calendar"></i> last month</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="icon member-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="title">Total Member</div>
                    <div class="value">782,221</div>
                    <div>
                        <span class="percentage positive">
                            <i class="fas fa-arrow-up"></i> 33%
                        </span>
                        <span class="period">From <i class="far fa-calendar"></i> last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Number Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Device Number</h6>
                        <div>
                            <button class="btn btn-sm btn-light"><i class="fas fa-sync-alt"></i></button>
                            <button class="btn btn-sm btn-light"><i class="fas fa-ellipsis-v"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Total Device Member</h5>
                            <h2>782,221</h2>
                        </div>
                        <div class="device-stats">
                            <div class="device-label">Android</div>
                            <div class="device-bar" style="width: 70%;"></div>
                            <div class="device-label">iOS</div>
                            <div class="device-bar" style="width: 85%;"></div>
                            <div class="device-label">Others</div>
                            <div class="device-bar" style="width: 45%;"></div>
                        </div>
                        <div class="mt-3">
                            <div class="row text-center">
                                <div class="col">0</div>
                                <div class="col">40K</div>
                                <div class="col">80K</div>
                                <div class="col">120K</div>
                                <div class="col">160K</div>
                                <div class="col">200K</div>
                                <div class="col">240K</div>
                                <div class="col">280K</div>
                                <div class="col">320K</div>
                                <div class="col">360K</div>
                                <div class="col">400K</div>
                                <div class="col">440K</div>
                                <div class="col">480K</div>
                                <div class="col">520K</div>
                                <div class="col">560K</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Range User Member Section -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Range User Member</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div>Total Member</div>
                            <h5>782,221 Member</h5>
                        </div>
                        <div class="chart-container">
                            <canvas id="userDemographicsChart"></canvas>
                        </div>
                        <div class="age-legend">
                            <div class="age-item">
                                <div class="age-color" style="background-color: #0d6efd;"></div>
                                <span>&lt;18 Years Old</span>
                            </div>
                            <div class="age-item">
                                <div class="age-color" style="background-color: #0dcaf0;"></div>
                                <span>18 - 24 Years Old</span>
                            </div>
                            <div class="age-item">
                                <div class="age-color" style="background-color: #20c997;"></div>
                                <span>25 - 34 Years Old</span>
                            </div>
                            <div class="age-item">
                                <div class="age-color" style="background-color: #198754;"></div>
                                <span>35 - 45 Years Old</span>
                            </div>
                            <div class="age-item">
                                <div class="age-color" style="background-color: #6610f2;"></div>
                                <span>&gt;45 Years Old</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Table Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Most Popular Product</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table product-table">
                                <thead>
                                    <tr>
                                        <th>Name Product</th>
                                        <th>Sold</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40" alt="Product" class="me-2">
                                                <div>
                                                    <div class="product-name">Corner Desk with Cabinet</div>
                                                    <div class="product-category">Elmwood</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>129</td>
                                        <td>$16641</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40" alt="Product" class="me-2">
                                                <div>
                                                    <div class="product-name">VOYAGER68 v2</div>
                                                    <div class="product-category">Smokey Black, PBT Keying white</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>201</td>
                                        <td>$13467</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40" alt="Product" class="me-2">
                                                <div>
                                                    <div class="product-name">Nomura Credenza</div>
                                                    <div class="product-category"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>169</td>
                                        <td>$11907</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40" alt="Product" class="me-2">
                                                <div>
                                                    <div class="product-name">Noir Desk Shelf</div>
                                                    <div class="product-category">Hazel</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>231</td>
                                        <td>$9009</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40" alt="Product" class="me-2">
                                                <div>
                                                    <div class="product-name">Alamo Low Back Black</div>
                                                    <div class="product-category"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>93</td>
                                        <td>$5580</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40" alt="Product" class="me-2">
                                                <div>
                                                    <div class="product-name">Noir M1 Modular Mouse</div>
                                                    <div class="product-category">White</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>85</td>
                                        <td>$3230</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="view-more">
                            <a href="#">View More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="/assets/libs/jquery/jquery-3.7.1.min.js" loading="eager"></script>
    <script type="text/javascript" src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js" loading="eager"></script>
    <script type="text/javascript" src="/assets/libs/fontawesome/js/all.min.js" loading="eager"></script>
    <script type="text/javascript" src="/assets/libs/axios/axios.min.js" loading="eager"></script>
    <script type="text/javascript" src="/assets/libs/chartjs/chart.umd.js" loading="lazy"></script>
    <script type="text/javascript" src="/assets/libs/toastr/toastr.min.js" loading="lazy"></script>
    <script type="text/javascript" src="/assets/libs/sweetalert2/dist/sweetalert2.all.min.js" loading="lazy"></script>
    <script type="text/javascript" src="/assets/libs/momentjs/moment.min.js" loading="lazy"></script>
    <script type="text/javascript" src="/assets/libs/daterangepicker/daterangepicker.min.js" loading="lazy"></script>

    <script type="text/javascript" src="assets/scripts/common.js" loading="eager"></script>
    <script type="text/javascript" src="assets/scripts/constants.js" loading="eager"></script>
    <script type="text/javascript" src="assets/scripts/helpers/global.js" loading="eager"></script>
    <script type="text/javascript" src="assets/scripts/helpers/system.js" loading="eager"></script>

    <script>
        // Initialize the pie chart for user demographics
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('userDemographicsChart').getContext('2d');

            const userDemographicsChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['<18 Years Old', '18-24 Years Old', '25-34 Years Old', '35-45 Years Old', '>45 Years Old'],
                    datasets: [{
                        data: [15, 18, 20, 22, 25],
                        backgroundColor: [
                            '#0d6efd',
                            '#0dcaf0',
                            '#20c997',
                            '#198754',
                            '#6610f2'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${percentage}%`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>