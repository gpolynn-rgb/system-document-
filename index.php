<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

auth_start_session();

if (auth_is_logged_in()) {
    header('Location: dashboard.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? (string) $_POST['email'] : '';
    $password = isset($_POST['password']) ? (string) $_POST['password'] : '';

    if (auth_attempt_login($email, $password)) {
        header('Location: dashboard.html');
        exit;
    }

    header('Location: index.html?error=1');
    exit;
}

$loginFailed = isset($_GET['error']) && $_GET['error'] === '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main class="page">
    <div class="login-box">
      <h1 class="login-box__title">Welcome back</h1>
      <p class="login-box__subtitle">Sign in to your account</p>

      <?php if ($loginFailed): ?>
        <p class="login-form__error" role="alert">Invalid email or password. Please try again.</p>
      <?php endif; ?>

      <form class="login-form" action="index.php" method="post">
        <label class="login-form__label" for="email">Email</label>
        <input
          class="login-form__input"
          type="email"
          id="email"
          name="email"
          placeholder="you@example.com"
          autocomplete="email"
          required
        />

        <label class="login-form__label" for="password">Password</label>
        <input
          class="login-form__input"
          type="password"
          id="password"
          name="password"
          placeholder="Enter your password"
          autocomplete="current-password"
          required
        />

        <button class="login-form__submit" type="submit">Login</button>
      </form>

      <div class="divider" aria-hidden="true">
        <span class="divider__line"></span>
        <span class="divider__text">or</span>
        <span class="divider__line"></span>
      </div>

      <button class="gmail-btn" type="button">
        <svg class="gmail-btn__icon" viewBox="0 0 24 24" aria-hidden="true">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Continue with Gmail
      </button>
    </div>
  </main>
</body>
</html>
