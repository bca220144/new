<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all students' overall grades from assignments
$sql_grades = "SELECT userid, SUM(marks) AS total_marks FROM assignment GROUP BY userid";
$result_grades = mysqli_query($conn, $sql_grades);

// Fetch attendance data
$sql_attendance = "SELECT userid, COUNT(*) AS total_present FROM attendance WHERE status='present' GROUP BY userid";
$result_attendance = mysqli_query($conn, $sql_attendance);

// Fetch assignment submissions
$sql_assignments = "SELECT userid, COUNT(*) AS total_assignments FROM assignment GROUP BY userid";
$result_assignments = mysqli_query($conn, $sql_assignments);

// Fetch assessments data
$sql_assessments = "SELECT userid, COUNT(*) AS total_assessments FROM assessment GROUP BY userid";
$result_assessments = mysqli_query($conn, $sql_assessments);

// Prepare associative arrays to store data
$attendance_data = [];
while ($row = mysqli_fetch_assoc($result_attendance)) {
    $attendance_data[$row['userid']] = $row['total_present'];
}

$grades_data = [];
while ($row = mysqli_fetch_assoc($result_grades)) {
    $grades_data[$row['userid']] = $row['total_marks'];
}

$assignments_data = [];
while ($row = mysqli_fetch_assoc($result_assignments)) {
    $assignments_data[$row['userid']] = $row['total_assignments'];
}

$assessments_data = [];
while ($row = mysqli_fetch_assoc($result_assessments)) {
    $assessments_data[$row['userid']] = $row['total_assessments'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Overview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            padding: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Performance Overview</h2>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Total Marks (Assignments)</th>
                <th>Attendance (Present Days)</th>
                <th>Total Assignments Submitted</th>
                <th>Total Assessments Completed</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Merge all data and display it
            $user_ids = array_unique(array_merge(array_keys($grades_data), array_keys($attendance_data), array_keys($assignments_data), array_keys($assessments_data)));
            foreach ($user_ids as $userid) {
                $total_marks = isset($grades_data[$userid]) ? $grades_data[$userid] : 0;
                $total_attendance = isset($attendance_data[$userid]) ? $attendance_data[$userid] : 0;
                $total_assignments = isset($assignments_data[$userid]) ? $assignments_data[$userid] : 0;
                $total_assessments = isset($assessments_data[$userid]) ? $assessments_data[$userid] : 0;

                echo "<tr>";
                echo "<td>$userid</td>";
                echo "<td>$total_marks</td>";
                echo "<td>$total_attendance</td>";
                echo "<td>$total_assignments</td>";
                echo "<td>$total_assessments</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
