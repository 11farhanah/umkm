<?php

define('APP_ROOT', dirname(__DIR__));
define('APP_PUBLIC', APP_ROOT . '/public');
define('APP_CONFIG', APP_ROOT . '/config');
define('APP_INCLUDES', APP_ROOT . '/includes');
define('APP_URL', rtrim(getenv('APP_URL') ?: 'http://127.0.0.1:8000', '/'));
