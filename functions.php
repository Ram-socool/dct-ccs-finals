<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Establish and return a database connection.
 *
 * @return mysqli|null Database connection object, or null if connection fails.
 */
function dbConnect() {
    $DbConfig = [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'dct-ccs-finals'
    ];

    // Create a new database connection.
    $connection = new mysqli(
        $DbConfig['host'], 
        $DbConfig['user'], 
        $DbConfig['password'], 
        $DbConfig['database']
    );

    // Check if connection was successful and log error if not.
    if ($connection->connect_error) {
        error_log("Connection failed: " . $connection->connect_error, 3, '/var/log/db_errors.log');
        return null; // Return null if connection failed.
    }

    return $connection; // Return the connection object.
}


/**
 * Authenticates a user using their email and password.
 * 
 * @param string $email The user's email.
 * @param string $password The user's password (plaintext, hashed internally).
 * 
 * @return array|bool The user's data if authentication is successful, otherwise false.
 */
function authenticateUser($email, $password) {
    $conn = dbConnect();

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $hashed_password = md5($password); // Hash password with MD5
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user data if found
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }

    return false; // Return false if no user found
}

/**
 * Displays a list of validation errors as a Bootstrap alert.
 * 
 * @param array $errors An array of error messages.
 * 
 * @return string The HTML string for the alert.
 */
function displayErrors($errors) {
    if (empty($errors)) {
        return '';
    }

    $html = '<div class="alert alert-danger" role="alert">';
    $html .= '<strong>Validation Errors:</strong><ul>';
    foreach ($errors as $error) {
        $html .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $html .= '</ul>';
    $html .= '</div>';

    return $html;
}

/**
 * Renders a Bootstrap alert with customizable messages and type.
 * 
 * @param array|string $messages The messages to display (string or array).
 * @param string $type The alert type (default: 'danger').
 * 
 * @return string The HTML string for the alert.
 */
function renderAlert($messages, $type = 'danger') {
    if (empty($messages)) {
        return '';
    }

    // Convert messages to an array if they aren't already
    if (!is_array($messages)) {
        $messages = [$messages];
    }

    $html = '<div class="alert alert-' . htmlspecialchars($type) . '" role="alert">';
    $html .= '<ul>';
    foreach ($messages as $message) {
        $html .= '<li>' . htmlspecialchars($message) . '</li>';
    }
    $html .= '</ul>';
    $html .= '</div>';

    return $html;
}

/**
 * Checks for duplicate subjects in the database.
 * 
 * @param string $subject_code The subject code.
 * @param string $subject_name The subject name.
 * 
 * @return string|null Returns an error message if a duplicate is found, otherwise null.
 */
function checkDuplicateSubject($subject_code, $subject_name) {
    $conn = dbConnect();

    $query = "SELECT * FROM subjects WHERE subject_code = ? OR subject_name = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $subject_code, $subject_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "A subject with the same code or name already exists.";
    }

    return null; // No duplicates found
}

function guard() {
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $baseURL = $protocol . $host . '/'; 

        exit();
    }
}

function getSelectedStudentData($student_id) {
    $connection = dbConnect();
    $query = "SELECT * FROM students WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    $stmt->close();
    $connection->close();

    return $student;
}
?>
