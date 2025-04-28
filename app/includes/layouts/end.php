<!-- libs -->
<script type=" text/javascript" src="<?= $base_url ?>/assets/libs/jquery/jquery-3.7.1.min.js" loading="eager">
</script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/fontawesome/js/all.min.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/axios/axios.min.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/chartjs/chart.umd.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/toastr/toastr.min.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/sweetalert2/dist/sweetalert2.all.min.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/momentjs/moment.min.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/daterangepicker/daterangepicker.min.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/datatables/dataTables.min.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/libs/daterangepicker/daterangepicker.min.js" loading="lazy"></script>

<!-- app -->
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/common.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/constants.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/helpers/global.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/helpers/system.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/helpers/loading.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/layouts/header.js" loading="eager"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/layouts/sidebar.js" loading="eager"></script>

<!-- autoload all services -->
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/system/accounts/services.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/system/members/services.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/system/employee/services.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/system/amortizations/services.js" loading="lazy"></script>
<script type="text/javascript" src="<?= $base_url ?>/assets/scripts/system/notifications/services.js" loading="lazy"></script>
<?php
if ($request_file_name === 'login.php') {
    echo <<<HTML
          <script type="text/javascript" src="{$base_url}/assets/scripts/client/login.js" loading="lazy"></script>
          HTML;
} else if ($request_file_name === 'account-verification.php') {
    echo <<<HTML
          <script type="text/javascript" src="{$base_url}/assets/scripts/client/account-verification.js" loading="lazy"></script>
          HTML;
}  else if ($request_file_name === 'account-request.php') {
    echo <<<HTML
          <script type="text/javascript" src="{$base_url}/assets/scripts/client/account-request.js" loading="lazy"></script>
          HTML;
} else if ($request_file_name === 'dashboard.php') {
    echo <<<HTML
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/daily-transactions.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/summary-metrics.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/monthly-balance-trends.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/loan-performance-metrics.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/outstanding-receivables-by-member.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/payment-histories-by-member.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/payment-trends-monthly.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/quarterly-financial-summary.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/monthly-financial-summary.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/annually-financial-summary.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/dashboard/admin/dashboard.js" loading="lazy"></script>
    HTML;
} else if ($request_file_name === 'accounts.php') {
    echo <<<HTML
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/accounts/data-tables.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/accounts/admin/get-accounts.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/accounts/admin/accounts.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/accounts/admin/create-member-accounts.js" loading="lazy"></script>
    HTML;
} else if ($request_file_name === 'members.php') {
    echo <<<HTML
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/members/data-tables.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/members/admin/update-member.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/members/admin/get-members.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/members/admin/members.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/members/member-tabs.js" loading="lazy"></script>
    HTML;
} else if ($request_file_name === 'amortizations.php') {
    echo <<<HTML
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/amortizations/data-tables.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/amortizations/admin/get-amortizations.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/amortizations/admin/amortizations.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/system/amortizations/amortization-tabs.js" loading="lazy"></script>
    HTML;
} else if ($request_file_name === 'my-account.php') {
    echo <<<HTML
        <script type="text/javascript" src="{$base_url}/assets/scripts/client/common-vars.js" loading="eager"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/client/data-tables.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/client/amortizations.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/client/transactions.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/client/my-account.js" loading="lazy"></script>
        <script type="text/javascript" src="{$base_url}/assets/scripts/client/load-balancer.js" loading="lazy"></script>
    HTML;
}
?>
</body>

</html>