<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../config/logger.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

begin_session();

require_once __DIR__ . '/../constants/system.php';
require_once __DIR__ . '/../helpers/system.php';
require_once __DIR__ . '/../helpers/global.php';

require_once __DIR__ . '/../services/app.php';

#controllers
require_once __DIR__ . '/account.php';
require_once __DIR__ . '/employee.php';
require_once __DIR__ . '/member.php';
// TODO:
require_once __DIR__ . '/amortization.php';
require_once __DIR__ . '/notification.php';
