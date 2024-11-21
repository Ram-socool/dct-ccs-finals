<?php
$title = "Register a New Student"; // Set the title for the page

require_once '../../functions.php'; // Include reusable functions
require_once '../partials/header.php'; // Include header layout
require_once '../partials/side-bar.php'; // Include sidebar layout

guard(); // Ensure only authenticated users can access this page
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Register a New Student</h1>

    <!-- Breadcrumb navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register Student</li>
        </ol>
    </nav>

    <!-- Placeholder for error messages -->
    <div class="alert alert-danger alert-dismissible fade show d-none" id="errorMessage" role="alert">
        <strong>Error:</strong> Validation failed. Please correct the errors and try again.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Placeholder for success messages -->
    <div class="alert alert-success alert-dismissible fade show d-none" id="successMessage" role="alert">
        Student successfully registered!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Student Registration Form -->
    <form method="post" action="">
        <div class="mb-3">
            <label for="student_id" class="form-label">Student ID</label>
            <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Enter Student ID" value="">
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="">
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary w-100">Add Student</button>
        </div>
    </form>

    <hr>

    <h2 class="h4">Student List</h2>

    <!-- Table displaying list of registered students -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Student ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Placeholder for student list rows -->
            <tr>
                <td>S001</td>
                <td>John</td>
                <td>Doe</td>
                <td>
                    <a href="edit.php?id=1" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete.php?id=1" class="btn btn-sm btn-danger">Delete</a>
                    <a href="attach-subject.php?id=1" class="btn btn-sm btn-warning">Attach Subject</a>
                </td>
            </tr>
            <tr>
                <td>S002</td>
                <td>Jane</td>
                <td>Smith</td>
                <td>
                    <a href="edit.php?id=2" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete.php?id=2" class="btn btn-sm btn-danger">Delete</a>
                    <a href="attach-subject.php?id=2" class="btn btn-sm btn-warning">Attach Subject</a>
                </td>
            </tr>
        </tbody>
    </table>
</main>

<?php require_once '../partials/footer.php'; // Include footer layout ?>
