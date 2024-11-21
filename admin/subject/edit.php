<?php
ob_start(); // Start output buffering to handle clean redirects and prevent "headers already sent" errors

require_once(__DIR__ . '/../../functions.php'); // Include global functions
require_once '../partials/header.php'; // Include the header layout
require_once '../partials/side-bar.php'; // Include the sidebar layout

guard(); // Ensure the user is authenticated before accessing this page

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $subject_id = $_GET['id']; // Get the subject ID from the URL

    // Fetch subject details from the database
    $connection = dbConnect();
    $query = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the subject exists, retrieve its details
    if ($result->num_rows > 0) {
        $subject = $result->fetch_assoc();
        $subject_code = $subject['subject_code'];
        $subject_name = $subject['subject_name'];
    } else {
        // Redirect to the subjects list if the subject is not found
        header("Location: ../subject/add.php");
        exit();
    }
} else {
    // Redirect to the subjects list if the 'id' parameter is missing
    header("Location: ../subject/add.php");
    exit();
}

// Initialize message variables
$error_message = '';
$success_message = '';

// Handle the form submission for updating the subject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subject'])) {
    $subject_name = trim($_POST['subject_name']); // Retrieve and sanitize the subject name input

    // Validate the subject name
    $errors = [];
    if (empty($subject_name)) {
        $errors[] = "Subject Name is required.";
    }

    // If no validation errors, proceed to update the subject
    if (empty($errors)) {
        $query = "UPDATE subjects SET subject_name = ? WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('si', $subject_name, $subject_id);

        if ($stmt->execute()) {
            // Redirect after a successful update
            header("Cache-Control: no-cache, no-store, must-revalidate"); // Clear browser cache
            header("Location: ../subject/add.php"); // Redirect to subjects list
            exit();
        } else {
            // Handle database errors
            $error_message = renderAlert(["Error updating subject: " . $stmt->error], 'danger');
        }
    } else {
        // Display validation errors
        $error_message = renderAlert($errors, 'danger');
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Edit Subject</h1>

    <!-- Breadcrumb navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="../subject/add.php">Subjects</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
        </ol>
    </nav>

    <!-- Display error messages -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display success messages -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Edit Subject Form -->
    <form method="post" action="">
        <!-- Subject Code Field (Read-only) -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Subject Code" value="<?php echo htmlspecialchars($subject_code); ?>" disabled>
            <label for="subject_code">Subject Code</label>
        </div>

        <!-- Subject Name Field (Editable) -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Subject Name" value="<?php echo htmlspecialchars($subject_name); ?>" required>
            <label for="subject_name">Subject Name</label>
        </div>

        <!-- Form Buttons -->
        <div class="mb-3 d-flex gap-2">
            <button type="submit" name="update_subject" class="btn btn-primary flex-grow-1">Update Subject</button>
            <a href="../subject/add.php" class="btn btn-secondary flex-grow-1">Cancel</a>
        </div>
    </form>
</main>

<?php require_once '../partials/footer.php'; // Include footer layout ?>
<?php ob_end_flush(); // End output buffering and send the output ?>
