<?php

$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']); // Sanitize subject input

    // Loop through the marks array and insert the assessment data
    foreach ($_POST['marks'] as $userid => $marks) {
        $userid = intval($userid); // Ensure User ID is an integer
        $marks = mysqli_real_escape_string($conn, $marks); // Sanitize mark input

        // Check if any variable is empty
        if (empty($userid) || empty($subject) || empty($marks)) {
            echo "<div class='error-message'>Error: One or more fields are empty for User ID: $userid</div>";
            continue; // Skip this iteration
        }

        // Insert the data into the 'assignment' table
        $sql = "INSERT INTO assignment (userid, subject, marks) VALUES ('$userid', '$subject', '$marks')";

        // Check if the query was successful
        if (mysqli_query($conn, $sql)) {
            echo "<div class='success-message'>Assessment recorded successfully for User ID: $userid</div>";
        } else {
            echo "<div class='error-message'>Error recording assessment for User ID: $userid. " . mysqli_error($conn) . "</div>";
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
    <title>Assignment Management</title>
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
    <h2>Assignment Management</h2>

    <form action="" method="POST">
        <select name="subject" required>
            <option value="">Select Subject</option>
            <option value="java programming">Java Programming</option>
            <option value="cn">CN</option>
            <option value="open course">Open Course</option>
            <option value="it & environment">IT & Environment</option>
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
                    echo "<td><input type='number' name='marks[$user_id]' placeholder='Enter Marks' required></td>";
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
