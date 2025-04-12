<!-- Header -->
<header class="header">
    <!-- left side -->
    <div class="header-left">
        <span class="menu-button"><i class="fa-solid fa-bars"></i></span>
    </div>

    <!-- right side -->
    <div class="header-right">
        <!-- notification dropdown -->
        <div class="dropdown notification-dropdown">
            <button class="notification-btn" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
            <ul class="dropdown-menu notification-menu" aria-labelledby="notificationDropdown">
                <li class="dropdown-header">Notifications</li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li class="notification-item">
                    <a href="#" class="dropdown-item">
                        <div class="notification-content">
                            <div class="notification-icon"><i class="fas fa-info-circle text-info"></i></div>
                            <div class="notification-text">
                                <p class="notification-title">New payment received</p>
                                <p class="notification-time">2 minutes ago</p>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li class="notification-item">
                    <a href="#" class="dropdown-item">
                        <div class="notification-content">
                            <div class="notification-icon"><i class="fas fa-exclamation-circle text-warning"></i></div>
                            <div class="notification-text">
                                <p class="notification-title">Account update required</p>
                                <p class="notification-time">1 hour ago</p>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li class="notification-item">
                    <a href="#" class="dropdown-item">
                        <div class="notification-content">
                            <div class="notification-icon"><i class="fas fa-check-circle text-success"></i></div>
                            <div class="notification-text">
                                <p class="notification-title">System update completed</p>
                                <p class="notification-time">Yesterday</p>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
            </ul>
        </div>

        <!-- user profile dropdown -->
        <div class="dropdown profile-dropdown">
            <button class="profile-btn" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= $base_url ?>/assets/images/<?= $_SESSION['role_name'] == 'Administrator' ? 'jerome-profile' : 'default-profile'?>.png" class="round-circle" width="40" height="40" alt="profile" loading="lazy" />
                <div class="text-container">
                    <span class="profile-name"><?= $_SESSION['full_name'] ?></span>
                    <span class="user-role"><?= $_SESSION['role_name'] ?></span>
                </div>
                <i class="fas fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu profile-menu" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> My Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#logout" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>