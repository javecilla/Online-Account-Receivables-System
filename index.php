<?php require_once __DIR__ . '/app/includes/layouts/begin.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
<?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php' 
?>

<?php
require_once __DIR__ . '/app/helpers/system.php';
require_once __DIR__ . '/app/helpers/global.php';
require_once __DIR__ . '/app/config/session.php';

begin_session();

if(is_authenticated()) {
   if(is_admin()) {
     header('Location: '. $base_url. '/dashboard'); 
   } else {
     header('Location: '. $base_url. '/my-account');
   }
   //echo 'authenticatd';
} else {
   header('Location: '. $base_url. '/auth/login');
   //echo 'not';
}

exit;
?>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php'
?>
<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>