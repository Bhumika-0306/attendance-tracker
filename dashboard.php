<?php
session_start();
if (!isset($_SESSION['professor'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to top left, #f6f9fc, #e0e7ff);
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: linear-gradient(135deg, #4e73df, #1c3d73);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar .navbar-text {
            color: white;
            font-size: 1.2rem;
            font-weight: 500;
        }
        .navbar .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .card-custom {
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            transition: all 0.3s ease;
        }
        .card-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.2);
        }
        .card-custom .card-body {
            background: #ffffff;
            padding: 20px;
        }
        .card-custom .card-title {
            font-size: 1.5rem;
            color: #333;
            font-weight: 600;
        }
        .card-custom .card-text {
            color: #666;
            font-size: 1rem;
            font-weight: 400;
        }
        .card-footer {
            background-color: transparent;
            border-top: none;
            text-align: center;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 15px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #555;
        }
        .footer a {
            text-decoration: none;
            color: #007bff;
        }
        .btn-custom {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            font-weight: bold;
            padding: 12px;
            border-radius: 10px;
            color: white;
            width: 100%;
        }
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(37, 117, 252, 0.5);
        }
        .row-cols-md-2 .col, .row-cols-lg-3 .col {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">


<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <img src="https://static.vecteezy.com/system/resources/thumbnails/005/544/770/small/profile-icon-design-free-vector.jpg" alt="Profile Picture" class="profile-img">
            <span class="navbar-text"><?php echo "Welcome, " . $_SESSION['professor']; ?></span>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="students.php">Manage Students</a>
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


<div class="container flex-grow-1 d-flex align-items-center justify-content-center">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
       
        <div class="col">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title">Manage Students</h5>
                    <p class="card-text">View and manage student records.</p>
                </div>
                <div class="card-footer">
                    <a href="students.php" class="btn btn-custom">Go to Students</a>
                </div>
            </div>
        </div>

        
        <div class="col">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title">Mark Attendance</h5>
                    <p class="card-text">Take attendance for your classes.</p>
                </div>
                <div class="card-footer">
                    <a href="attendance.php" class="btn btn-custom">Go to Attendance</a>
                </div>
            </div>
        </div>

        
        <div class="col">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title">View Reports</h5>
                    <p class="card-text">Check student attendance reports.</p>
                </div>
                <div class="card-footer">
                    <a href="report.php" class="btn btn-custom">Go to Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="raiseQueryModal" tabindex="-1" aria-labelledby="raiseQueryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="raiseQueryModalLabel">Raise a Query</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="query" class="form-label">Your Query</label>
                        <textarea class="form-control" id="query" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-custom">Submit Query</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="footer mt-auto">
    <p>&copy; 2025 Attendance Tracker. All Rights Reserved. <br>
        For inquiries, <a href="mailto:support@attendancetracker.com">contact us</a>.
    </p>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
