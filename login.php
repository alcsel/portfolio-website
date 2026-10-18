<?php
declare(strict_types=1);

$configFile = __DIR__ . '/config.php';
if (is_file($configFile)) {
    require $configFile;
}

function envValue(string $name): string
{
    $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);

    return is_string($value) ? trim($value) : '';
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function layout(string $title, string $body): string
{
    $safeTitle = escape($title);

    return <<<HTML
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$safeTitle}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&amp;family=Syne:wght@400;600;800&amp;display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg">
<div class="container">
<a class="navbar-brand" href="index.html">SC_</a>
<div class="ms-auto">
<a href="index.html" class="nav-link">← Back to home</a>
</div>
</div>
</nav>
<div class="login-wrap">
<div class="login-box">
{$body}
</div>
</div>
</body>
</html>
HTML;
}

function panel(string $mark, string $heading, string $text, string $linkHref, string $linkLabel): string
{
    $safeHeading = escape($heading);
    $safeText = escape($text);
    $safeHref = escape($linkHref);
    $safeLabel = escape($linkLabel);

    return <<<HTML
<div class="text-center">
<div class="login-mark">{$mark}</div>
<div class="login-title">{$safeHeading}</div>
<p class="text-muted">{$safeText}</p>
<a href="{$safeHref}" class="btn-accent mt-4 d-inline-block">{$safeLabel}</a>
</div>
HTML;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: login.html');
    exit;
}

$expectedEmail = envValue('PORTFOLIO_EMAIL');
$expectedPasswordHash = envValue('PORTFOLIO_PASSWORD_HASH');

$email = trim((string) ($_POST['email'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($expectedEmail === '' || $expectedPasswordHash === '') {
    http_response_code(503);
    echo layout(
        'Sign-in unavailable',
        '<div class="text-center">'
        . '<div class="login-mark">⚠</div>'
        . '<div class="login-title">Sign-in is not configured</div>'
        . '<p class="text-muted">This server has no credentials for the demo sign-in, so the login is refused. '
        . 'Set the <span class="mono text-accent">PORTFOLIO_EMAIL</span> and '
        . '<span class="mono text-accent">PORTFOLIO_PASSWORD_HASH</span> environment variables, '
        . 'or copy <span class="mono text-accent">config.example.php</span> to '
        . '<span class="mono text-accent">config.php</span> and fill it in.</p>'
        . '<a href="index.html" class="btn-accent mt-4 d-inline-block">Back to home</a>'
        . '</div>'
    );
    exit;
}

if ($email === '' || $password === '') {
    header('Location: login.html?error=empty');
    exit;
}

if (!hash_equals($expectedEmail, $email) || !password_verify($password, $expectedPasswordHash)) {
    header('Location: login.html?error=invalid');
    exit;
}

echo layout(
    'Welcome',
    panel(
        '✔',
        'Welcome',
        'Signed in as ' . $email,
        'index.html',
        'Back to home'
    )
);
