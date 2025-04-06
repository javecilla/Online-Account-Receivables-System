<?php


declare(strict_types=1);

require_once __DIR__ . '/app/config/env.php';
require_once __DIR__ . '/app/helpers/global.php';

$credentials = [
    'hostname' => env('DB_HOST'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'database' => env('DB_DATABASE'),
    'port'     => env('DB_PORT'),
];

//var_dump(print_r($credentials, true));
var_dump(is_valid_string('53423')); //true
var_dump(is_valid_string('jerome')); //true
var_dump(is_valid_string('jerom111')); //true
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css" loading="eager" />
    <link rel="stylesheet" href="assets/libs/fontawesome/css/all.min.css" loading="eager" />
    <link rel="stylesheet" href="assets/libs/toastr/toastr.min.css" loading="lazy" />
    <link rel="stylesheet" href="assets/libs/sweetalert2/dist/sweetalert2.min.css" loading="lazy" />
</head>

<body>

    <button type="button" id="toastr" class=" btn btn-success">toastr</button>
    <button type="button" id="swal" class=" btn btn-danger">swal</button>

    <i class="fas fa-check"></i>

    <div>
        <canvas id="myChart"></canvas>
    </div>

    <script src="assets/libs/jquery/jquery-3.7.1.min.js" loading="eager"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js" loading="eager"></script>
    <script src="assets/libs/fontawesome/js/all.min.js" loading="eager"></script>
    <script src="assets/libs/toastr/toastr.min.js" loading="lazy"></script>
    <script src="assets/libs/sweetalert2/dist/sweetalert2.all.min.js" loading="lazy"></script>
    <script src="assets/libs/chartjs/chart.js" loading="lazy"></script>

    <script src="assets/scripts/common.js" loading="eager"></script>
    <script src="assets/scripts/constants.js" loading="eager"></script>
    <script src="assets/scripts/helpers/global.js" loading="eager"></script>
    <script src="assets/scripts/helpers/system.js" loading="eager"></script>

    <script type="text/javascript">
        $(document).ready(() => {
            const ctx = document.getElementById('myChart');
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May'],
                    datasets: [{
                        label: 'Sales',
                        data: [10, 20, 15, 25, 30],
                        borderColor: 'blue',
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            $('#toastr').click(() => {
                // toastr.success('Hello World!');
                toastr.warning('error na')
            });

            $('#swal').click(() => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Hello World!',
                    icon: 'success',
                    confirmButtonText: 'Cool'
                });
            });

            $.ajax({
                url: 'https://jsonplaceholder.typicode.com/posts/1',
                type: 'GET',
                dataType: 'json',
                success: (data) => {
                    console.log(data);
                },
                error: (xhr, status, error) => {
                    console.error('Error:', error);
                }
            });


            // const ctx = $('#myChart');

            // new Chart(ctx, {
            //     type: 'bar',
            //     data: {
            //         labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            //         datasets: [{
            //             label: '# of Votes',
            //             data: [12, 19, 3, 5, 2, 3],
            //             borderWidth: 1
            //         }]
            //     },
            //     options: {
            //         scales: {
            //             y: {
            //                 beginAtZero: true
            //             }
            //         }
            //     }
            // });
        });
    </script>
</body>

</html>