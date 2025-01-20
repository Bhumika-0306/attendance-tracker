<?php
session_start();
require 'config.php';

if (!isset($_SESSION['professor'])) {
    header('Location: index.php');
    exit();
}

$db = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        $name = $_POST['name'];
        $division = $_POST['division'];
        $db->exec("INSERT INTO students (name, division) VALUES ('$name', '$division')");
    } elseif (isset($_POST['remove_student'])) {
        $id = $_POST['student_id'];
        $db->exec("DELETE FROM students WHERE id = $id");
    } elseif (isset($_POST['edit_student'])) {
      
        $id = $_POST['student_id'];
        $name = $_POST['name'];
        $division = $_POST['division'];
        $db->exec("UPDATE students SET name = '$name', division = '$division' WHERE id = $id");
    }
}


$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_division = isset($_GET['filter_division']) ? $_GET['filter_division'] : '';

$query = "SELECT * FROM students WHERE name LIKE '%$search%'";
if ($filter_division) {
    $query .= " AND division = '$filter_division'";
}

$students = $db->query($query);


$students_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $students_per_page;
$students = $db->query($query . " LIMIT $students_per_page OFFSET $offset");


$total_students = $db->querySingle("SELECT COUNT(*) FROM students WHERE name LIKE '%$search%'");
$total_pages = ceil($total_students / $students_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #4e73df, #1c3d73);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar .navbar-brand,
        .navbar .nav-link {
            color: #ffffff;
        }
        .navbar .navbar-brand:hover,
        .navbar .nav-link:hover {
            color: #adb5bd;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
            width: 100%;
        }
        .footer a {
            text-decoration: none;
            color: #007bff;
        }
        .card-custom {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .modal-header {
            background-color: #28a745;
            color: white;
        }
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .pagination .page-link {
            color: #007bff;
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
                    <a class="nav-link active" href="students.php">Manage Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="attendance.php">Mark Attendance</a>
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


<div class="container mt-5">
    <h2 class="mb-4">Manage Students</h2>


    <form method="GET" class="mb-4 d-flex">
        <input type="text" name="search" class="form-control mr-3" placeholder="Search by student name or ID" value="<?php echo $search; ?>" />
        <select name="filter_division" class="form-select mr-3">
            <option value="">All Divisions</option>
            <option value="Div A" <?php echo $filter_division == 'Div A' ? 'selected' : ''; ?>>Div A</option>
            <option value="Div B" <?php echo $filter_division == 'Div B' ? 'selected' : ''; ?>>Div B</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>


    <form method="POST" class="mb-4">
        <div class="form-group mb-3">
            <label>Student Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Division</label>
            <select name="division" class="form-select">
                <option value="Div A">Div A</option>
                <option value="Div B">Div B</option>
            </select>
        </div>
        <button type="submit" name="add_student" class="btn btn-success">Add Student</button>
    </form>

  
    <h3>Current Students</h3>
    <div class="row">
        <?php while ($student = $students->fetchArray()): ?>
            <div class="col-md-4 mb-4">
                <div class="card card-custom">
                    <div class="card-header">
                        Student ID: <?php echo $student['id']; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $student['name']; ?></h5>
                        <p class="card-text">Division: <?php echo $student['division']; ?></p>
                    </div>
                    <div class="card-footer text-center">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                            <button type="submit" name="remove_student" class="btn btn-danger btn-sm">Remove</button>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo $student['id']; ?>">Edit</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

  
    <div>
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="students.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&filter_division=<?php echo urlencode($filter_division); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="students.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&filter_division=<?php echo urlencode($filter_division); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="students.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&filter_division=<?php echo urlencode($filter_division); ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>


<?php while ($student = $students->fetchArray()): ?>
<div class="modal fade" id="editModal-<?php echo $student['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" name="name" value="<?php echo $student['name']; ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <select name="division" class="form-select">
                            <option value="Div A" <?php echo $student['division'] == 'Div A' ? 'selected' : ''; ?>>Div A</option>
                            <option value="Div B" <?php echo $student['division'] == 'Div B' ? 'selected' : ''; ?>>Div B</option>
                        </select>
                    </div>
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    <button type="submit" name="edit_student" class="btn btn-primary" style="padding: 10px 20px;">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<div class="footer mt-auto">
    <p>&copy; 2025 Attendance Tracker. All Rights Reserved. <br>
       For inquiries, <a href="mailto:support@attendancetracker.com">contact us</a>.
    </p>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
