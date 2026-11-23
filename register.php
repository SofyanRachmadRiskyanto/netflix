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
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $email = is_string($submittedEmail) ? trim($submittedEmail) : '';

        if (!is_string($password) || !is_string($confirmPassword)) {
            $error = 'Enter a valid email and password.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
            $error = 'Enter a valid email address.';
        } elseif (strlen($password) < 8 || strlen($password) > 1024) {
            $error = 'Password must be at least 8 characters.';
        } elseif (!hash_equals($password, $confirmPassword)) {
            $error = 'Passwords do not match.';
        } else {
            $statement = app_db()->prepare('INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)');

            try {
                $statement->execute([
                    'email' => $email,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ]);
            } catch (PDOException $exception) {
                if ($exception->getCode() !== '23000') {
                    throw $exception;
                }

                $error = 'An account with that email already exists.';
            }

            if ($error === '') {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) app_db()->lastInsertId();
                $_SESSION['email'] = $email;
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
    <title>Create account - iflix</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page">
        <header class="brand"><a href="index.php" aria-label="iflix home">iflix</a></header>
        <main class="auth-card">
            <h1>Create your account</h1>
            <p class="intro">Use an email and password for iflix.</p>

            <?php if ($error !== ''): ?>
                <p class="alert" role="alert"><?= escape_html($error) ?></p>
            <?php endif; ?>

            <form method="post" action="register.php">
                <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" maxlength="254" value="<?= escape_html($email) ?>" required>

                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="128" required>

                <label for="confirm-password">Confirm password</label>
                <input id="confirm-password" name="confirm_password" type="password" autocomplete="new-password" minlength="8" maxlength="128" required>

                <button type="submit">Create account</button>
            </form>

            <p class="auth-switch">Already have an account? <a href="index.php">Sign in</a></p>
        </main>
        <footer class="footer">Use an account created for iflix. This is not a Netflix sign-in.</footer>
    </div>
</body>
</html>
