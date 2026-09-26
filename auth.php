<?php

declare(strict_types=1);

function auth_start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function auth_is_logged_in(): bool
{
    auth_start_session();
    return !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * @return bool True when credentials match config.
 */
function auth_attempt_login(string $email, string $password): bool
{
    $config = require __DIR__ . '/config.php';
    $email = strtolower(trim($email));

    $validEmail = hash_equals(strtolower($config['login_email']), $email);
    $validPassword = hash_equals($config['login_password'], $password);

    if (!$validEmail || !$validPassword) {
        return false;
    }

    auth_start_session();
    session_regenerate_id(true);
    $_SESSION['logged_in'] = true;
    $_SESSION['user_email'] = $email;

    return true;
}

function auth_logout(): void
{
    auth_start_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            (bool) $params['secure'],
            (bool) $params['httponly']
        );
    }
    session_destroy();
}

function auth_require_login(string $redirectTo = 'index.php'): void
{
    if (!auth_is_logged_in()) {
        header('Location: ' . $redirectTo);
        exit;
    }
}
