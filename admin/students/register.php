<?php
$title = "Register a New Student"; // Set the page title

require_once '../../functions.php'; // Include global functions
require_once '../partials/header.php'; // Include header layout
require_once '../partials/side-bar.php'; // Include sidebar layout

guard(); // Ensure the user is authenticated

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize message variables
$error_message = '';
$success_message = '';

// Initialize form data
$student_id = $_POST['student_id'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $student_data = [
        'student_id' => trim($student_id),
        'first_name' => trim($first_name),
        'last_name' => trim($last_name),
    ];

    $errors = [];

    // Validation
    if (empty($student_data['student_id'])) {
        $errors[] = "Student ID is required.";
    }
    if (empty($student_data['first_name'])) {
        $errors[] = "First Name is required.";
    }
    if (empty($student_data['last_name'])) {
        $errors[] = "Last Name is required.";
    }

    // Check for duplicate Student ID in the database
    if (empty($errors)) {
        $connection = dbConnect(); // Establish a database connection
        $query = "SELECT id FROM students WHERE student_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $student_data['student_id']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Student ID already exists.";
        }

        $stmt->close();
    }

    // Proceed to insert the student if no errors
    if (empty($errors)) {
        $query = "INSERT INTO students (student_id, first_name, last_name) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($query);

        if ($stmt) {
            $stmt->bind_param('sss', $student_data['student_id'], $student_data['first_name'], $student_data['last_name']);
            if ($stmt->execute()) {
                $success_message = "Student registered successfully!";
                $student_id = $first_name = $last_name = ''; // Clear form values
            } else {
                $errors[] = "Failed to register the student: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Failed to prepare the query: " . $connection->error;
        }

        $connection->close(); // Close the database connection
    }

    // Set the error message if there are errors
    if (!empty($errors)) {
        $error_message = "System Errors:<ul>";
        foreach ($errors as $error) {
            $error_message .= "<li>" . htmlspecialchars($error) . "</li>";
        }
        $error_message .= "</ul>";
    }
}
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

    <!-- Display error or success messages -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Student Registration Form -->
    <form method="post" action="">
        <div class="mb-3">
            <label for="student_id" class="form-label">Student ID</label>
            <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Enter Student ID" value="<?php echo htmlspecialchars($student_id); ?>">
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="<?php echo htmlspecialchars($first_name); ?>">
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="<?php echo htmlspecialchars($last_name); ?>">
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
            <?php
            // Fetch students from the database
            $connection = dbConnect();
            $query = "SELECT * FROM students";
            $result = $connection->query($query);

            // Display each student
            while ($student = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                        <a href="attach-subject.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-warning">Attach Subject</a>
                    </td>
                </tr>
            <?php endwhile;

            $connection->close(); // Close database connection
            ?>
        </tbody>
    </table>
</main>

<?php require_once '../partials/footer.php'; // Include footer layout ?>
