<?php
if(is_authenticated()) {
    handle_session_data_tempering();
    handle_session_hijacking();
} else {
    force_logout();
}
?>
<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <!-- <i class="fas fa-bolt"></i>
        <span>Javecilla</span> -->
        <img src="<?= $base_url ?>/assets/images/logo-text.png" width="220" alt="logo" />
    </div>
    <hr style="color:rgb(180, 180, 180)">
    <ul class="sidebar-menu">
        <li class="s-admin <?= is_employee() ? 'd-block' : 'd-none'?>">
            <a href="<?= $base_url ?>/dashboard" title="Dashboard | OARSMC">
                <!-- <i class="fas fa-gauge"></i> -->
                <i class="fas fa-table-columns"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="s-admin <?= is_employee()? 'd-block' : 'd-none'?>">
            <a href="<?= $base_url?>/reports" title="Reports | OARSMC">
                <i class="fas fa-chart-simple"></i>
                <span>Reports</span>
            </a>
        </li>
        <li class="s-admin <?= is_employee() ? 'd-block' : 'd-none'?>">
            <a href="<?= $base_url ?>/invoices" title="Invoices | OARSMC">
                <i class="fas fa-file-invoice"></i>
                <span>Invoices</span>
            </a>
        </li>
        <li class="s-admin <?= is_employee() ? 'd-block' : 'd-none'?>">
            <a href="<?= $base_url ?>/amortizations" title="Amortizations | OARSMC">
                <i class="fas fa-credit-card"></i>
                <span>Loans</span>
            </a>
        </li>
        <li class="s-admin <?= is_employee() ? 'd-block' : 'd-none'?>">
            <a href="javascript:void(0)" data-href="/cooperative-accounts" title="Cooperative Accounts | OARSMC">
                <!-- <i class="fa-solid fa-folder"></i> -->
                 <i class="fa-solid fa-id-card"></i>
                <span>Cooperative Accounts</span>
            </a>
            <ul>
                <li class="active">
                    <a href="<?= $base_url ?>/cooperative-accounts?type_name=<?=urlencode('Fixed Deposit')?>&type_id=3">Fixed</a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/cooperative-accounts?type_name=<?=urlencode('Savings Account')?>&type_id=1">Savings</a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/cooperative-accounts?type_name=<?=urlencode('Time Deposit')?>&type_id=2">Time Deposits</a>
                </li>
            </ul>
        </li>
        <li class="s-admin <?= is_employee() ? 'd-block' : 'd-none'?>">
            <a href="<?= $base_url ?>/members" title="Members | OARSMC">
                <i class="fas fa-user"></i>
                <span>Members</span>
            </a>
        </li>
        <li class="s-member <?= is_member() ? 'd-block' : 'd-none'?>">
            <a href="<?= $base_url ?>/my-account" title="My Account | OARSMC">
                <i class="fas fa-user"></i>
                <span>My Account</span>
            </a>
        </li>
        <li class="s-admin <?= is_employee() ? 'd-block' : 'd-none'?>">
            <a href="<?= $base_url ?>/accounts" title="Accounts | OARSMC">
                <i class="fas fa-users"></i>
                <span>User Accounts</span>
            </a>
        </li>
         <li class="s-admin <?= is_employee() ? 'd-block' : 'd-none'?>">
            <a href="<?= $base_url ?>/contents" title="Contents | OARSMC">
                <i class="fa-solid fa-pager"></i>
                <span>Contents</span>
            </a>
        </li>
    </ul>
</aside>