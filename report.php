<?php
session_start();
require 'config.php';

if (!isset($_SESSION['professor'])) {
    header('Location: index.php');
    exit();
}

$db = connect_db();


$report = $db->query("
    SELECT students.name, students.division, 
           SUM(CASE WHEN attendance.status = 'Present' THEN 1 ELSE 0 END) AS present_days,
           SUM(CASE WHEN attendance.status = 'Absent' THEN 1 ELSE 0 END) AS absent_days
    FROM students
    LEFT JOIN attendance ON students.id = attendance.student_id
    GROUP BY students.id
    ORDER BY division, name
");


$monthly_attendance = $db->query("
    SELECT students.id, students.name, students.division, 
           strftime('%Y-%m', attendance.date) AS month,
           SUM(CASE WHEN attendance.status = 'Present' THEN 1 ELSE 0 END) AS present_days,
           SUM(CASE WHEN attendance.status = 'Absent' THEN 1 ELSE 0 END) AS absent_days
    FROM students
    LEFT JOIN attendance ON students.id = attendance.student_id
    GROUP BY students.id, month
    ORDER BY division, name, month
");


$defaulters_check = $db->query("
    SELECT students.id, students.name, 
           SUM(CASE WHEN attendance.status = 'Absent' THEN 1 ELSE 0 END) AS absent_days
    FROM students
    LEFT JOIN attendance ON students.id = attendance.student_id
    GROUP BY students.id
    HAVING absent_days >= 2
");

$defaulters = [];
while ($row = $defaulters_check->fetchArray()) {
    $defaulters[$row['id']] = 'Yes';
}


$monthly_attendance_data = [];
while ($row = $monthly_attendance->fetchArray()) {
    $monthly_attendance_data[] = $row;
}

if (empty($monthly_attendance_data)) {
    echo "No attendance data found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <style>
       
        .chart-container {
            width: 100%;
            height: 300px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            display:flex;
            justify-content: center;
            display: flex;
        }
        .navbar {
            background: linear-gradient(135deg, #4e73df, #1c3d73);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

       
        

       
        .modal-content {
            padding: 20px;
            border-radius: 10px;
            border: none;
        }

        .modal-header, .modal-footer {
            border: none;
        }

        .modal-title {
            font-size: 1.5rem;
        }
        
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

       
        .table th, .table td {
            text-align: center;
        }

       
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .footer {
            margin-top: auto;
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
        }
        .table-container{
            display: flex;
    justify-content: center;
    width: 100%;
    overflow-x: auto;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <img src="https://static.vecteezy.com/system/resources/thumbnails/005/544/770/small/profile-icon-design-free-vector.jpg" alt="Profile Picture" class="profile-img" style="width: 40px; height: 40px; border-radius: 50%;">
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
                    <a class="nav-link active" href="report.php">Attendance Report</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-danger ms-2">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2>Attendance Reports</h2>

 
    <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#raiseRedFlagModal">Raise a Red Flag</button>

    
    <div class="modal fade" id="raiseRedFlagModal" tabindex="-1" aria-labelledby="raiseRedFlagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="raiseRedFlagModalLabel">Raise a Red Flag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Enter Student Name</label>
                        <input type="text" class="form-control" id="studentName" required>
                    </div>
                    <div class="mb-3">
                        <label for="studentId" class="form-label">Enter Student ID</label>
                        <input type="number" class="form-control" id="studentId" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitRedFlag">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <div class="table-container">
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Division</th>
                    <th>Month</th>
                    <th>Days Present</th>
                    <th>Days Absent</th>
                    <th>Defaulter</th>
                </tr>
            </thead>
            <tbody id="monthlyReportTable">
                <?php foreach ($monthly_attendance_data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['division']); ?></td>
                        <td><?php echo htmlspecialchars($row['month']); ?></td>
                        <td><?php echo $row['present_days']; ?></td>
                        <td><?php echo $row['absent_days']; ?></td>
                        <td><?php echo (isset($defaulters[$row['id']]) ? 'Yes' : 'No'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div class="chart-container mt-4">
        <h4>Attendance Overview (Bar Chart)</h4>
        <canvas id="attendanceChart"></canvas>
    </div>
</div>


<div class="footer mt-auto">
    <p>&copy; 2025 Attendance Tracker. All Rights Reserved. <br>
       For inquiries, <a href="mailto:support@attendancetracker.com">contact us</a>.
    </p>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    var ctx = document.getElementById('attendanceChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May'], 
            datasets: [{
                label: 'Present',
                data: [12, 15, 18, 22, 20], 
                backgroundColor: 'rgba(0, 123, 255, 0.7)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }, {
                label: 'Absent',
                data: [3, 2, 4, 1, 3], 
                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

 
    document.getElementById('submitRedFlag').addEventListener('click', function() {
        var studentName = document.getElementById('studentName').value.trim();
        var studentId = document.getElementById('studentId').value.trim();

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'verify_student.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText === 'success') {
                    alert('Red Flag Raised Successfully!');
                    document.getElementById('raiseRedFlagModal').querySelector('button[data-bs-dismiss="modal"]').click();
                } else {
                    alert('Error: Student ID or Name does not match.');
                }
            }
        };
        xhr.send('studentId=' + studentId + '&studentName=' + encodeURIComponent(studentName));
    });
</script>

</body>
</html>
