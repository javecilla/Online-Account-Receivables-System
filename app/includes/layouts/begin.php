<?php
require_once __DIR__ . '/../../helpers/system.php';
require_once __DIR__ . '/../../helpers/global.php';

$base_url = get_base_url();
$request_file_name = get_request_file_name();
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
        content="<?= $base_url ?>/assets/images/ogimage.png" />
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
        content="<?= $base_url ?>/assets/images/ogimage.png" />

    <meta name="base-url" content="<?= $base_url ?>" />
    <meta name="request-file-name" content="<?= $request_file_name ?>" />

    <title id="pageTitle">[Project Ngani] Jerome Avecilla</title>
    <link
        rel="shortcut icon"
        type="image/png"
        href="<?= $base_url ?>/assets/images/favicon.png" />
    <link rel="preconnect" href="https://jerome-avecilla.vercel.app/" crossorigin />

    <!-- libs -->
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/libs/bootstrap/css/bootstrap.min.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/libs/fontawesome/css/all.min.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/libs/toastr/toastr.min.css" loading="lazy" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/libs/sweetalert2/dist/sweetalert2.min.css" loading="lazy" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/libs/daterangepicker/daterangepicker.css" loading="lazy" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/libs/datatables/dataTables.min.css" loading="lazy" />

    <!-- app -->
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/stylesheets/common.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/stylesheets/layouts/sidebar.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/stylesheets/layouts/header.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/stylesheets/layouts/footer.css" loading="eager" />
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/stylesheets/layouts/responsive.css" loading="eager" />

    <?php
    if ($request_file_name === 'dashboard.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/system/dashboard.css" loading="eager" />
          HTML;
    } else if ($request_file_name === 'accounts.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/system/accounts.css" loading="eager" />
          HTML;
    }
    ?>

</head>

<body>