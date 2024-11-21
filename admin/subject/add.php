<?php
// Include the required files for functions, header, and sidebar
$title = "Add Students";
require_once(__DIR__ . '/../../functions.php');
require_once '../partials/header.php';
require_once '../partials/side-bar.php';

// Guard to ensure only authenticated users can access this page
guard();

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Handle the form submission for adding a new subject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subject'])) {
    // Retrieve and sanitize input values
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);

    // Initialize an array to collect validation errors
    $errors = [];

    // Validate the Subject Code
    if (empty($subject_code)) {
        $errors[] = "Subject Code is required.";
    } elseif (strlen($subject_code) > 4) {
        $errors[] = "Subject Code cannot exceed 4 characters.";
    }

    // Validate the Subject Name
    if (empty($subject_name)) {
        $errors[] = "Subject Name is required.";
    }

    // Check for duplicates in the database if no initial errors
    if (empty($errors)) {
        $connection = dbConnect();
        $query = "SELECT * FROM subjects WHERE subject_code = ? OR subject_name = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ss', $subject_code, $subject_name);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            if ($row['subject_code'] === $subject_code) {
                $errors[] = "Subject Code is already exist.";
            }
            if ($row['subject_name'] === $subject_name) {
                $errors[] = "Subject Name is already exist.";
            }
        }
    }

    // If no errors, add the subject
    if (empty($errors)) {
        $query = "INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ss', $subject_code, $subject_name);

        if ($stmt->execute()) {
            $success_message = "Subject added successfully!";
            $subject_code = '';
            $subject_name = '';
        } else {
            $errors[] = "Error adding subject. Please try again.";
        }
    }

    // If there are errors, render them as error messages
    if (!empty($errors)) {
        $error_message = $errors;
    }
}

// Fetch the list of subjects from the database
$connection = dbConnect();
$query = "SELECT * FROM subjects";
$result = $connection->query($query);
?>

<!-- Main Content -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Add a New Subject</h1>

    <!-- Breadcrumb navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add a New Subject</li>
        </ol>
    </nav>

    <!-- Display error messages -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>System Errors:</strong>
            <ul>
                <?php foreach ($error_message as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display success messages -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form to add a new subject -->
    <form method="post" action="">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Subject Code" value="<?php echo htmlspecialchars($subject_code ?? ''); ?>">
            <label for="subject_code">Subject Code</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Subject Name" value="<?php echo htmlspecialchars($subject_name ?? ''); ?>">
            <label for="subject_name">Subject Name</label>
        </div>
        <div class="mb-3">
            <button type="submit" name="add_subject" class="btn btn-primary w-100">Add Subject</button>
        </div>
    </form>

    <!-- List of existing subjects -->
    <h3 class="mt-5">Subject List</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['subject_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                    <td>
                        <!-- Links to edit and delete subjects -->
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<!-- Include the footer -->
<?php require_once '../partials/footer.php'; ?>
