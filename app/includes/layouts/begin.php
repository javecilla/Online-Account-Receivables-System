<?php
require_once __DIR__ . '/../../helpers/system.php';
require_once __DIR__ . '/../../helpers/global.php';
require_once __DIR__ . '/../../config/session.php';

$base_url = get_base_url();
$request_file_name = get_request_file_name();

begin_session();
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
    <meta
        name="keywords"
        content="Account Receivable, Online Account Receivable for Multipurpose Cooperative" />
    <meta
        name="robots"
        content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />

    <meta
        name="googlebot"
        content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <meta
        name="bingbot"
        content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <meta
        name="google-site-verification"
        content="<?= env('GOOGLE_SITE_VERIFICATION') ?>" />


    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= $base_url ?>" />
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

    <title id="pageTitle">IT211 Project | Avecilla</title>

    <?php
      if(is_authenticated()) {
        //var_dump($_SESSION);
        echo <<<HTML
                <meta name="ip-address" content="{$_SESSION['ip_address']}" />
                <meta name="user-agent" content="{$_SESSION['user_agent']}" />
                <meta name="session-hash" content="{$_SESSION['session_hash']}" />
                <meta name="session-id" content="{$_SESSION['session_id']}" />
                <meta name="account-id" content="{$_SESSION['account_id']}" />
                <meta name="account-uid" content="{$_SESSION['account_uid']}" />
                <meta name="email" content="{$_SESSION['email']}" />
                <meta name="username" content="{$_SESSION['username']}" />
                <meta name="account-status" content="{$_SESSION['account_status']}" />
                <meta name="role-name" content="{$_SESSION['role_name']}" />
                <meta name="first-name" content="{$_SESSION['first_name']}" />
                <meta name="last-name" content="{$_SESSION['last_name']}" />
                <meta name="middle-name" content="{$_SESSION['middle_name']}" />
                <meta name="full-name" content="{$_SESSION['full_name']}" />
                <meta name="contact-number" content="{$_SESSION['contact_number']}" />
          HTML;

          if($_SESSION['role_name'] == 'Administrator') {
            echo <<<HTML
                <meta name="employee-id" content="{$_SESSION['employee_id']}" />
            HTML;
          } else {
            //Member
            echo <<<HTML
                <meta name="member-id" content="{$_SESSION['member_id']}" />
            HTML;
          }
      }
    ?>

    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="<?= $base_url ?>/assets/images/favicons/favicon-32x32.png" />
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="<?= $base_url ?>/assets/images/favicons/favicon-16x16.png" />
    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="<?= $base_url ?>/assets/images/favicons/favicon-180x180.png" />
    <meta
        name="msapplication-TileImage"
        content="<?= $base_url ?>/assets/images/favicons/favicon.png" />

    <link
        rel="shortcut icon"
        type="image/png"
        href="<?= $base_url ?>/assets/images/favicons/favicon.png" />

    <link rel="canonical" href="<?= $base_url ?>" />
    <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
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
    if ($request_file_name === 'login.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/client/login.css" loading="eager" />
          <script src="https://www.google.com/recaptcha/api.js" async defer></script>
          HTML;
    } else if ($request_file_name === 'account-verification.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/client/account-verification.css" loading="eager" />
          <script src="https://www.google.com/recaptcha/api.js" async defer></script>
          HTML;
    } else if ($request_file_name === 'account-request.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/client/account-request.css" loading="eager" />
          <script src="https://www.google.com/recaptcha/api.js" async defer></script>
          HTML;
    } else if ($request_file_name === 'dashboard.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/system/dashboard.css" loading="eager" />
          HTML;
    } else if ($request_file_name === 'accounts.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/system/accounts.css" loading="eager" />
          HTML;
    } else if ($request_file_name === 'members.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/system/members.css" loading="eager" />
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/system/member-tabs.css" loading="eager" />
          HTML;
    } else if ($request_file_name === 'amortizations.php') {
        echo <<<HTML
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/system/amortizations.css" loading="eager" />
          <link rel="stylesheet" type="text/css" href="{$base_url}/assets/stylesheets/system/amortization-tabs.css" loading="eager" />
          HTML;
    }
    ?>

</head>

<body>