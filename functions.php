
<?php
function authenticateUser($email, $password) {
    $conn = dbConnect();
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, md5($password)); // Hash password with MD5
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc(); // Return user data
    }

    return false; // No user found
}   
?>

<?php
function dbConnect() {
    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "dct-ccs-finals";

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
