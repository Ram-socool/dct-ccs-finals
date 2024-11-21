<?php
ob_start(); // Start output buffering to prevent header errors
$title = "Edit Student"; // Set the page title

// Include necessary files for functionality and layout
require_once '../../functions.php'; 
require_once '../partials/header.php'; 
require_once '../partials/side-bar.php'; 

guard(); // Ensure the user is authenticated

// Enable error reporting for debugging during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables for error and success messages
$error_message = '';
$success_message = '';

// Check if a valid student ID is provided via the URL
if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']); // Sanitize the student ID
    $student_data = getSelectedStudentData($student_id); // Fetch student details from the database

    if (!$student_data) {
        // Display an error if the student data is not found
        $error_message = "Student not found.";
    }
} else {
    // Display an error if no student ID is provided
    $error_message = "No student selected.";
}

// Handle form submission for updating student details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_student'])) {
    if (isset($student_id)) {
        // Sanitize and retrieve form inputs
        $updated_data = [
            'first_name' => trim($_POST['first_name'] ?? ''), // Get and clean the first name
            'last_name' => trim($_POST['last_name'] ?? '') // Get and clean the last name
        ];

        // Validate the required fields
        if (empty($updated_data['first_name']) || empty($updated_data['last_name'])) {
            $error_message = "First Name and Last Name are required."; // Validation error message
        } else {
            // Proceed with updating the database
            $connection = dbConnect(); // Establish a database connection
            $query = "UPDATE students SET first_name = ?, last_name = ? WHERE id = ?"; // SQL query for updating data
            $stmt = $connection->prepare($query); // Prepare the SQL statement
            $stmt->bind_param('ssi', $updated_data['first_name'], $updated_data['last_name'], $student_id); // Bind query parameters

            if ($stmt->execute()) {
                // Display success message and redirect after successful update
                $success_message = "Student updated successfully.";
                header("Location: ../students/register.php?update=success");
                exit();
            } else {
                // Display an error if the update fails
                $error_message = "Failed to update student: " . $stmt->error;
            }

            $stmt->close(); // Close the prepared statement
            $connection->close(); // Close the database connection
        }
    } else {
        // Display an error if the student ID is invalid
        $error_message = "Invalid student ID.";
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Edit Student</h1>

    <!-- Breadcrumb navigation for improved navigation experience -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="../students/register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
        </ol>
    </nav>

    <!-- Display error message if any -->
    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display success message if any -->
    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display the student edit form if student data is available -->
    <?php if (isset($student_data)): ?>
        <form method="post">
            <!-- Display the Student ID (read-only) -->
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student_data['student_id']); ?>" disabled>
            </div>

            <!-- Editable First Name field -->
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student_data['first_name']); ?>" placeholder="Enter First Name">
            </div>

            <!-- Editable Last Name field -->
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student_data['last_name']); ?>" placeholder="Enter Last Name">
            </div>

            <!-- Submit button for updating student details -->
            <div class="mb-3">
                <button type="submit" name="update_student" class="btn btn-primary w-100">Update Student</button>
            </div>
        </form>
    <?php endif; ?>
</main>

<!-- Include the footer layout -->
<?php require_once '../partials/footer.php'; ?>
<?php ob_end_flush(); // End output buffering ?>
