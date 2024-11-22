<?php
require_once 'functions.php';


// Initialize errors and success messages
$login_errors = [];
$login_success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_email = trim($_POST['email']);
    $user_password = trim($_POST['password']);

    // Validate email
    if (empty($user_email)) {
        $login_errors[] = "Email is required.";
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $login_errors[] = "Please enter a valid email address.";
    }

    // Validate password
    if (empty($user_password)) {
        $login_errors[] = "Password is required.";
    }

    // Proceed if no validation errors
    if (empty($login_errors)) {
        $user = authenticateUser($user_email, $user_password); // Function to validate login

        if ($user) {
            // Successful login: redirect to the dashboard
            $_SESSION['user_name'] = $user['name']; // Store user info in the session
            $login_success = "Welcome, " . htmlspecialchars($user['name']) . "!";
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $login_errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
</head>

<body class="bg-secondary-subtle">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-4 col-lg-3">
            <!-- Display Errors -->
            <?php if (!empty($login_errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach ($login_errors as $error): ?>
                        <p class="mb-0"><?= htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Display Success Message -->
            <?php if (!empty($login_success)): ?>
                <div class="alert alert-success" role="alert">
                    <p class="mb-0"><?= htmlspecialchars($login_success); ?></p>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <div class="card">
                <div class="card-body">
                    <h1 class="h3 mb-4 fw-normal text-center">Login</h1>
                    <form method="post" action="">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com" value="<?= htmlspecialchars($user_email ?? ''); ?>">
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
