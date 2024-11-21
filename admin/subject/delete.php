<?php
ob_start(); // Start output buffering for clean redirects and output

require_once '../../functions.php'; // Include functions file for reusable code
require_once '../partials/header.php'; // Include header layout
require_once '../partials/side-bar.php'; // Include sidebar layout

guard(); // Ensure the user is authenticated
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Delete Subject</h1>

    <!-- Display error message placeholder -->
    <div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="errorMessage">
        <strong>Error:</strong> Subject not found or could not be deleted.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Display success message placeholder -->
    <div class="alert alert-success alert-dismissible fade show d-none" role="alert" id="successMessage">
        Subject deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Breadcrumb navigation -->
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="/admin/dashboard.php">Dashboard</a>
        <a class="breadcrumb-item" href="/admin/subject/add.php">Add Subject</a>
        <span class="breadcrumb-item active">Delete Subject</span>
    </nav>

    <!-- Display subject details (static placeholders for now) -->
    <div class="card mt-4">
        <div class="card-body">
            <p>Are you sure you want to delete the following subject record?</p>
            <ul>
                <li><strong>Subject Code:</strong> S001</li>
                <li><strong>Subject Name:</strong> Mathematics</li>
            </ul>
            <!-- Form for deletion confirmation -->
            <form method="post">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/admin/subject/add.php'">Cancel</button>
                <button type="submit" class="btn btn-primary">Delete Subject Record</button>
            </form>
        </div>
    </div>
</main>

<?php
require_once '../partials/footer.php'; // Include footer layout
ob_end_flush(); // End output buffering and send the output
?>
