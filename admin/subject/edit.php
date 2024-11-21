<?php
ob_start(); // Start output buffering to handle potential header redirection issues
require_once(__DIR__ . '/../../functions.php'); // Include the required functions file
require_once '../partials/header.php'; // Include the header layout
require_once '../partials/side-bar.php'; // Include the sidebar layout
guard(); // Ensure only authenticated users can access this page

// Initialize variables for error and success messages
$error_message = '';
$success_message = '';

?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Edit Subject</h1>

    <!-- Breadcrumb navigation to guide the user -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="../subject/add.php">Subjects</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
        </ol>
    </nav>

    <!-- Display error messages if any -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display success messages if any -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Edit Subject Form -->
    <form method="post" action="">
        <!-- Subject Code field (read-only, disabled) -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Subject Code" value="" disabled>
            <label for="subject_code">Subject Code</label>
        </div>

        <!-- Subject Name field (editable by the user) -->
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Subject Name" value="" required>
            <label for="subject_name">Subject Name</label>
        </div>

        <!-- Action buttons -->
        <div class="mb-3 d-flex gap-2">
            <!-- Submit button to update the subject -->
            <button type="submit" name="update_subject" class="btn btn-primary flex-grow-1">Update Subject</button>

            <!-- Cancel button to navigate back to the subjects list -->
            <a href="../subject/add.php" class="btn btn-secondary flex-grow-1">Cancel</a>
        </div>
    </form>
</main>

<!-- Include the footer layout -->
<?php require_once '../partials/footer.php'; ?>
<?php ob_end_flush(); // End output buffering ?>
