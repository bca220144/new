<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// When the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim the date input
    $date = trim(mysqli_real_escape_string($conn, $_POST['date']));

    // Debugging output for the submitted date
    echo "Submitted Date: $date <br>"; // Output the submitted date for debugging
    
    // Check if the attendance array is empty
    if (empty($_POST['attendance'])) {
        echo "<div class='error-message'>Please select attendance for at least one user.</div>";
    } else {
        // Loop through the attendance array and insert the attendance data
        foreach ($_POST['attendance'] as $userid => $status) {
            $status = mysqli_real_escape_string($conn, $status); // Sanitize status input
            // Prepare the SQL query
            $sql = "INSERT INTO attendance (userid, status, date) VALUES ('$userid', '$status', '$date')";

            
            
            // Execute the query and check for errors
            if (mysqli_query($conn, $sql)) {
                echo "<div class='success-message'>Attendance recorded successfully for User ID: $userid</div>";
            } else {
                echo "<div class='error-message'>Error recording attendance for User ID: $userid. " . mysqli_error($conn) . "</div>";
            }
        }
    }
}

// Fetch the user IDs from the register table
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
    <title>Attendance Management</title>
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

        tr:hover {
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        input[type="date"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            margin-bottom: 10px;
        }

        input[type="radio"] {
            margin-right: 10px;
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
    <h2>Attendance Management</h2>

    <form action="" method="POST">
        <input type="date" name="date" required>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display users in table format
                while ($row = mysqli_fetch_assoc($result)) {
                    $user_id = $row['userid'];
                    echo "<tr>";
                    echo "<td>$user_id</td>";
                    echo "<td>
                            <input type='radio' name='attendance[$user_id]' value='present' required> Present
                            <input type='radio' name='attendance[$user_id]' value='absent' required> Absent
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="submit-container">
            <input type="submit" value="Submit Attendance">
        </div>
    </form>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
