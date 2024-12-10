<?php
require_once 'db_config.php'; // Include your database connection

// Retrieve form data
$blood_group = isset($_POST['blood_group']) ? $_POST['blood_group'] : '';

// Prepare and execute the SQL query with correct column names
$sql = "SELECT name, age, gender, email, mobile_number, state, district, address, blood_group 
        FROM donate 
        WHERE blood_group = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $blood_group);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Donors</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        h2 {
            color: #4CAF50;
            margin-top: 20px;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .button-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Available Donors</h2>

<?php
// Display the results
if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>State</th>
                <th>District</th>
                <th>Address</th>
                <th>Blood Group</th>
            </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['name'] . "</td>
                <td>" . $row['age'] . "</td>
                <td>" . $row['gender'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>" . $row['mobile_number'] . "</td>
                <td>" . $row['state'] . "</td>
                <td>" . $row['district'] . "</td>
                <td>" . $row['address'] . "</td>
                <td>" . $row['blood_group'] . "</td>
              </tr>";
    }
    echo "</table>";

    // Add button at the bottom of the table
    echo "<div class='button-container'>
    <a href='contact_donors.php'>
        <button>Contact Donors</button>
    </a>
  </div>";
} else {
    echo "<p>No donors found matching your criteria.</p>";
}

// Close connection
$stmt->close();
$conn->close();
?>

</body>
</html>
