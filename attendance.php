<?php
session_start();
require 'config.php';

if (!isset($_SESSION['professor'])) {
    header('Location: index.php');
    exit();
}

$db = connect_db();
$success = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = date('Y-m-d');
    $present_count = 0; 
    $absent_count = 0;  
    
    foreach ($_POST['attendance'] as $student_id => $status) {
       
        $stmt = $db->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
        $stmt->bindValue(1, $student_id, SQLITE3_INTEGER);
        $stmt->bindValue(2, $date, SQLITE3_TEXT);
        $stmt->bindValue(3, $status, SQLITE3_TEXT);
        $stmt->execute();
        
        if ($status === 'Present') {
            $present_count++;
        } else {
            $absent_count++;
        }
    }
    

    $total_students = $db->querySingle("SELECT COUNT(*) FROM students");
    
    $success = "Attendance marked successfully!";
}


$students = $db->query("SELECT * FROM students ORDER BY division, name");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Roboto', sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #4e73df, #1c3d73);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: white;
        }
        .navbar a:hover {
            color: #f1f1f1;
        }
        .navbar .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .footer {
            background-color: #ffffff;
            padding: 20px 0;
            text-align: center;
            color: #333;
        }
        .footer a {
            text-decoration: none;
            color: #007bff;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            padding: 12px 30px;
            font-size: 16px;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .form-select {
            border-radius: 12px;
            border: 1px solid #ccc;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 15px;
        }
        .table {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-summary {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }
        .card-summary .alert {
            margin-bottom: 15px;
        }
        .container {
            margin-top: 50px;
        }
        .content {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <img src="https://static.vecteezy.com/system/resources/thumbnails/005/544/770/small/profile-icon-design-free-vector.jpg" alt="Profile Picture" class="profile-img">
            <span class="navbar-text"><?php echo "Welcome, " . ($_SESSION['professor'] ?? "Guest"); ?></span>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="attendance.php">Mark Attendance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="report.php">View Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#raiseQueryModal">Raise a Query</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-danger ms-2">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container content">
    <div class="header">
        <h2>Mark Attendance</h2>
    </div>

    <!-- Attendance Success Message -->
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <!-- Attendance Form -->
    <form method="POST">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Division</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $students->fetchArray()): ?>
                        <tr>
                            <td><?php echo $student['id']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['division']; ?></td>
                            <td>
                                <select name="attendance[<?php echo $student['id']; ?>]" class="form-select">
                                    <option value="Present">Present</option>
                                    <option value="Absent">Absent</option>
                                </select>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-custom">Submit Attendance</button>
    </form>

  
    <?php if ($success): ?>
        <div class="card-summary">
            <h5>Today's Attendance Summary</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="alert alert-success">
                        <strong>Present:</strong> <?php echo $present_count; ?> / <?php echo $total_students; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-danger">
                        <strong>Absent:</strong> <?php echo $absent_count; ?> / <?php echo $total_students; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-info">
                        <strong>Total Students:</strong> <?php echo $total_students; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->
<div class="footer mt-auto">
    <p>&copy; 2025 Attendance Tracker. All Rights Reserved. <br>
       For inquiries, <a href="mailto:support@attendancetracker.com">contact us</a>.
    </p>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
