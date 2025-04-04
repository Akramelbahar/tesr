<?php
session_start();
require_once 'db_connection.php';

// Initialize database connection
$db = new Database();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Student Registration
    if (isset($_POST['register_student'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $department = $_POST['department'];
        $graduation_year = $_POST['graduation_year'];

        $stmt = $db->prepare("INSERT INTO students (first_name, last_name, email, phone, department, graduation_year) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $department, $graduation_year);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Student registered successfully!";
        } else {
            $_SESSION['error'] = "Error registering student: " . $db->error();
        }
        $stmt->close();
    }

    // Internship Application
    if (isset($_POST['apply_internship'])) {
        $student_id = $_POST['student_id'];
        $company_id = $_POST['company_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $position = $_POST['position'];
        $description = $_POST['description'];

        $stmt = $db->prepare("INSERT INTO internships (student_id, company_id, start_date, end_date, position, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $student_id, $company_id, $start_date, $end_date, $position, $description);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Internship application submitted successfully!";
        } else {
            $_SESSION['error'] = "Error submitting internship application: " . $db->error();
        }
        $stmt->close();
    }
}

// Fetch data for dropdowns and lists
$students_query = $db->prepare("SELECT id, CONCAT(first_name, ' ', last_name) as full_name FROM students");
$students_query->execute();
$students_result = $students_query->get_result();

$companies_query = $db->prepare("SELECT id, company_name FROM companies");
$companies_query->execute();
$companies_result = $companies_query->get_result();

$internships_query = $db->prepare("
    SELECT i.id, s.first_name, s.last_name, c.company_name, i.position, i.status, i.start_date, i.end_date 
    FROM internships i
    JOIN students s ON i.student_id = s.id
    JOIN companies c ON i.company_id = c.id
");
$internships_query->execute();
$internships_result = $internships_query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Internship Management System</h1>

        <!-- Notification Area -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="notification success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="notification error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Student Registration Section -->
        <section class="registration-section">
            <h2>Student Registration</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="tel" name="phone" placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <input type="text" name="department" placeholder="Department" required>
                    <input type="number" name="graduation_year" placeholder="Graduation Year" required>
                </div>
                <button type="submit" name="register_student">Register Student</button>
            </form>
        </section>

        <!-- Internship Application Section -->
        <section class="internship-section">
            <h2>Internship Application</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <select name="student_id" required>
                        <option value="">Select Student</option>
                        <?php while($student = $students_result->fetch_assoc()): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo $student['full_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <select name="company_id" required>
                        <option value="">Select Company</option>
                        <?php while($company = $companies_result->fetch_assoc()): ?>
                            <option value="<?php echo $company['id']; ?>"><?php echo $company['company_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="date" name="start_date" placeholder="Start Date" required>
                    <input type="date" name="end_date" placeholder="End Date" required>
                </div>
                <div class="form-group">
                    <input type="text" name="position" placeholder="Position" required>
                    <textarea name="description" placeholder="Internship Description" required></textarea>
                </div>
                <button type="submit" name="apply_internship">Submit Internship Application</button>
            </form>
        </section>

        <!-- Internships List Section -->
        <section class="internships-list">
            <h2>Current Internships</h2>
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Company</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($internship = $internships_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $internship['first_name'] . ' ' . $internship['last_name']; ?></td>
                            <td><?php echo $internship['company_name']; ?></td>
                            <td><?php echo $internship['position']; ?></td>
                            <td><?php echo $internship['status']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($internship['start_date'])) . ' - ' . date('M d, Y', strtotime($internship['end_date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
<?php 
// Close database connection
$db->close(); 
?>
