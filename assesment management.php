<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// When the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $assessment_type = mysqli_real_escape_string($conn, $_POST['assessment_type']);

    // Loop through the assessment array and insert the data
    foreach ($_POST['assessment'] as $userid => $mark) {
        $mark = mysqli_real_escape_string($conn, $mark); // Sanitize mark input
        
        // Prepare SQL query to insert data
        $sql = "INSERT INTO assessment (userid, subject, assessment_type, mark) 
                VALUES ('$userid', '$subject', '$assessment_type', '$mark')";

        if (mysqli_query($conn, $sql)) {
            echo "<div class='success-message'>Assessment recorded successfully for User ID: $userid</div>";
        } else {
            echo "<div class='error-message'>Error recording assessment for User ID: $userid. " . mysqli_error($conn) . "</div>";
        }
    }
}

// Fetch user IDs and names from the register table
$sql = "SELECT userid, name FROM register";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Management</title>
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
        input[type="number"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Assessment Management</h2>

    <form action="" method="POST">
        <select name="subject" required>
            <option value="">Select Subject</option>
            <option value="Java Programming">Java Programming</option>
            <option value="CN">CN</option>
            <option value="Open Course">Open Course</option>
            <option value="IT & Environment">IT & Environment</option>
        </select>
        
        <select name="assessment_type" required>
            <option value="">Select Assessment Type</option>
            <option value="Group Discussion">Group Discussion</option>
            <option value="Seminar">Seminar</option>
            <option value="Project">Project</option>
        </select>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display users in table format
                while ($row = mysqli_fetch_assoc($result)) {
                    $user_id = $row['userid'];
                    $name = $row['name'];
                    echo "<tr>";
                    echo "<td>$user_id</td>";
                    echo "<td>$name</td>";
                    echo "<td><input type='number' name='assessment[$user_id]' placeholder='Enter Marks' required></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="submit-container">
            <input type="submit" value="Submit Assessment">
        </div>
    </form>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
