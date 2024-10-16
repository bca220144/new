<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// When the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $exam = mysqli_real_escape_string($conn, $_POST['exam']);
    
    // Loop through the attendance array and insert the marks data
    foreach ($_POST['attendance'] as $userid => $mark) {
        $mark = mysqli_real_escape_string($conn, $mark); // Sanitize mark input
        
        // Check if any variable is empty
        if (empty($userid) || empty($subject) || empty($exam) || empty($mark)) {
            echo "<div class='error-message'>Error: One or more fields are empty for User ID: $userid</div>";
            continue; // Skip this iteration
        }
        
        // Insert the data
        $sql = "INSERT INTO marks (userid, subject, exam, marks) VALUES ('$userid', '$subject', '$exam', '$mark')";
        
        // Check if the query was successful
        if (mysqli_query($conn, $sql)) {
            echo "<div class='success-message'>Marks recorded successfully for User ID: $userid</div>";
        } else {
            echo "<div class='error-message'>Error recording marks for User ID: $userid. " . mysqli_error($conn) . "</div>";
        }
    }
}

// Fetch user IDs from the register table
$sql = "SELECT userid FROM register";
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
    <title>Grade Management</title>
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

        form {
            margin-bottom: 20px;
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
    <h2>Grade Management</h2>

    <form action="" method="POST">
        <select name="subject" required>
            <option value="">Select Subject</option>
            <option value="java programming">Java Programming</option>
            <option value="cn">CN</option>
            <option value="open course">Open Course</option>
            <option value="it & environment">IT & Environment</option>
        </select>
        
        <select name="exam" required>
            <option value="">Select Exam</option>
            <option value="1st internal">1st Internal</option>
            <option value="test paper">Test Paper</option>
            <option value="model exam">Model Exam</option>
        </select>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display users in table format
                while ($row = mysqli_fetch_assoc($result)) {
                    $user_id = $row['userid'];
                    echo "<tr>";
                    echo "<td>$user_id</td>";
                    echo "<td><input type='number' name='attendance[$user_id]' placeholder='Enter Marks' required></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="submit-container">
            <input type="submit" value="Submit Marks">
        </div>
    </form>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>