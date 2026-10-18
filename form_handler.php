<?php
declare(strict_types=1);

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: contact.html');
    exit;
}

$subjectLabels = [
    'collaboration' => 'Collaboration',
    'project' => 'Project',
    'other' => 'Other',
];

function field(string $name): string
{
    $value = $_POST[$name] ?? '';

    return is_string($value) ? trim($value) : '';
}

function fieldList(string $name): array
{
    $values = $_POST[$name] ?? [];
    if (!is_array($values)) {
        return [];
    }

    $clean = [];
    foreach ($values as $value) {
        if (is_string($value) && trim($value) !== '') {
            $clean[] = trim($value);
        }
    }

    return $clean;
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function display(string $value): string
{
    return $value !== '' ? escape($value) : '—';
}

$values = [
    'first_name' => field('first_name'),
    'last_name' => field('last_name'),
    'email' => field('email'),
    'phone' => field('phone'),
    'subject' => field('subject'),
    'gender' => field('gender'),
    'message' => field('message'),
];

$interests = fieldList('interests');
$consent = isset($_POST['consent']) ? 'Granted' : 'Not granted';
$subject = $subjectLabels[$values['subject']] ?? $values['subject'];
$submittedAt = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Submission received</title>
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
<a href="contact.html" class="nav-link">← Back to the form</a>
</div>
</div>
</nav>

<main class="container py-5">
<p class="section-label">// Server side result</p>
<h2 class="mb-4">Submitted form data</h2>

<div class="alert-acc mb-4">✔ Your form data was received and processed successfully.</div>

<div class="table-responsive">
<table class="table">
<thead>
<tr>
<th scope="col" class="table-label">Field</th>
<th scope="col">Value</th>
</tr>
</thead>
<tbody>
<tr><td class="mono text-accent">First name</td><td><?= display($values['first_name']) ?></td></tr>
<tr><td class="mono text-accent">Last name</td><td><?= display($values['last_name']) ?></td></tr>
<tr><td class="mono text-accent">Email</td><td><?= display($values['email']) ?></td></tr>
<tr><td class="mono text-accent">Phone</td><td><?= display($values['phone']) ?></td></tr>
<tr><td class="mono text-accent">Subject</td><td><?= display($subject) ?></td></tr>
<tr><td class="mono text-accent">Gender</td><td><?= display($values['gender']) ?></td></tr>
<tr><td class="mono text-accent">Interests</td><td><?= $interests !== [] ? escape(implode(', ', $interests)) : '—' ?></td></tr>
<tr><td class="mono text-accent">Data processing consent</td><td><?= $consent ?></td></tr>
<tr><td class="mono text-accent">Message</td><td><?= $values['message'] !== '' ? nl2br(escape($values['message']), false) : '—' ?></td></tr>
<tr><td class="mono text-accent">Submitted at</td><td><?= escape($submittedAt) ?></td></tr>
</tbody>
</table>
</div>

<a href="index.html" class="btn-accent mt-3 d-inline-block">Back to home</a>
</main>

<footer>
<div class="container">
<p>© 2026 Selim Calici · B251210378 · Sakarya University</p>
</div>
</footer>

</body>
</html>
