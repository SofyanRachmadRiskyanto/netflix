<?php
declare(strict_types=1);

require __DIR__ . DIRECTORY_SEPARATOR . 'auth.php';
start_app_session();

if (!empty($_SESSION['user_id'])) {
    redirect_to('dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_valid_csrf_token()) {
        http_response_code(400);
        $error = 'Form expired. Refresh the page and try again.';
    } else {
        $submittedEmail = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $email = is_string($submittedEmail) ? trim($submittedEmail) : '';

        if (!is_string($password) || $email === '' || $password === '') {
            $error = 'Enter your email and password.';
        } else {
            $statement = app_db()->prepare('SELECT id, email, password_hash FROM users WHERE email = :email LIMIT 1');
            $statement->execute(['email' => $email]);
            $user = $statement->fetch();

            if (!$user || !password_verify($password, $user['password_hash'])) {
                $error = 'Email or password is incorrect.';
            } else {
                if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                    $update = app_db()->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :id');
                    $update->execute([
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'id' => $user['id'],
                    ]);
                }

                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                redirect_to('dashboard.php');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - iflix</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <header class="brand"><a href="index.php" aria-label="iflix home">iflix</a></header>
        <main class="auth-card">
            <h1>Welcome back</h1>
            <p class="intro">Sign in to your iflix account.</p>

            <?php if ($error !== ''): ?>
                <p class="alert" role="alert"><?= escape_html($error) ?></p>
            <?php endif; ?>

            <form method="post" action="index.php">
                <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" autocomplete="username" maxlength="254" value="<?= escape_html($email) ?>" required>

                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>

                <button type="submit">Sign in</button>
            </form>

            <p class="auth-switch">New to iflix? <a href="register.php">Create an account</a></p>
        </main>
        <footer class="footer">Use an account created for iflix. This is not a Netflix sign-in.</footer>
    </div>
</body>
</html>
