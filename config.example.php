<?php
/**
 * Example Configuration File
 * Copy this file to config.php and update the values according to your environment
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'alumni_tgp');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_NAME', 'Alumni TGP');
define('SITE_URL', 'https://example.com');
define('ADMIN_EMAIL', 'admin@example.com');
define('SUPPORT_EMAIL', 'support@example.com');

// Security Configuration
define('ENCRYPTION_KEY', 'your-32-character-encryption-key');
define('JWT_SECRET', 'your-jwt-secret-key');
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_NAME', 'alumni_tgp_session');
define('PASSWORD_PEPPER', 'your-password-pepper');

// File Upload Configuration
define('UPLOAD_DIR', 'uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);

// Email Configuration
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your_smtp_username');
define('SMTP_PASS', 'your_smtp_password');
define('SMTP_SECURE', 'tls');
define('SMTP_FROM_EMAIL', 'noreply@example.com');
define('SMTP_FROM_NAME', 'Alumni TGP');

// Chat Configuration
define('CHAT_WEBSOCKET_SERVER', 'ws://localhost:8080');
define('CHAT_MESSAGE_LIMIT', 100);
define('CHAT_FILE_MAX_SIZE', 2097152); // 2MB

// Cache Configuration
define('CACHE_ENABLED', true);
define('CACHE_DIR', 'cache/');
define('CACHE_LIFETIME', 3600); // 1 hour

// API Configuration
define('API_RATE_LIMIT', 60); // requests per minute
define('API_TOKEN_LIFETIME', 86400); // 24 hours
define('API_ALLOWED_ORIGINS', ['https://example.com']);

// Social Media Configuration
define('FACEBOOK_APP_ID', 'your_facebook_app_id');
define('FACEBOOK_APP_SECRET', 'your_facebook_app_secret');
define('INSTAGRAM_CLIENT_ID', 'your_instagram_client_id');
define('INSTAGRAM_CLIENT_SECRET', 'your_instagram_client_secret');

// Push Notification Configuration
define('PUSH_PUBLIC_KEY', 'your_push_public_key');
define('PUSH_PRIVATE_KEY', 'your_push_private_key');
define('PUSH_EMAIL', 'mailto:admin@example.com');

// Debug Configuration
define('DEBUG_MODE', false);
define('ERROR_REPORTING', E_ALL);
define('DISPLAY_ERRORS', false);
define('ERROR_LOG_FILE', 'logs/error.log');

// Time Configuration
define('DEFAULT_TIMEZONE', 'Asia/Jakarta');
date_default_timezone_set(DEFAULT_TIMEZONE);

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.use_strict_mode', 1);

// Security Headers
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src \'self\' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src \'self\' data: https:; connect-src \'self\' ws: wss:;');

// Maintenance Mode
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_MESSAGE', 'Site is under maintenance. Please check back later.');
define('MAINTENANCE_ALLOWED_IPS', ['127.0.0.1']);

// Feature Flags
define('FEATURE_CHAT', true);
define('FEATURE_JOBS', true);
define('FEATURE_NOTIFICATIONS', true);
define('FEATURE_EXPORT', true);

// Custom Functions
function isDebugMode() {
    return DEBUG_MODE === true;
}

function isMaintenanceMode() {
    return MAINTENANCE_MODE === true;
}

function isFeatureEnabled($feature) {
    $constant = 'FEATURE_' . strtoupper($feature);
    return defined($constant) && constant($constant) === true;
}

// Error Handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }

    $errorType = 'Unknown Error';
    switch ($errno) {
        case E_ERROR:
            $errorType = 'Fatal Error';
            break;
        case E_WARNING:
            $errorType = 'Warning';
            break;
        case E_PARSE:
            $errorType = 'Parse Error';
            break;
        case E_NOTICE:
            $errorType = 'Notice';
            break;
        case E_CORE_ERROR:
            $errorType = 'Core Error';
            break;
        case E_CORE_WARNING:
            $errorType = 'Core Warning';
            break;
        case E_USER_ERROR:
            $errorType = 'User Error';
            break;
        case E_USER_WARNING:
            $errorType = 'User Warning';
            break;
        case E_USER_NOTICE:
            $errorType = 'User Notice';
            break;
        case E_STRICT:
            $errorType = 'Strict Notice';
            break;
        case E_RECOVERABLE_ERROR:
            $errorType = 'Recoverable Error';
            break;
    }

    $message = sprintf(
        "[%s] %s: %s in %s on line %d\n",
        date('Y-m-d H:i:s'),
        $errorType,
        $errstr,
        $errfile,
        $errline
    );

    error_log($message, 3, ERROR_LOG_FILE);

    if (DEBUG_MODE) {
        echo $message;
    }

    return true;
}

set_error_handler('customErrorHandler');