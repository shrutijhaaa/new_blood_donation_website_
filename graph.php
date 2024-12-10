<?php
// db_config.php should be included to establish the connection
include('db_config.php');

// Fetch data for the pie chart
$query = "SELECT Blood_group, COUNT(*) as count FROM donate GROUP BY Blood_group";
$result = mysqli_query($conn, $query);

$blood_groups = [];
$counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $blood_groups[] = $row['Blood_group'];
    $counts[] = $row['count'];
}

// Convert data to JSON format for JavaScript
$blood_groups_json = json_encode($blood_groups);
$counts_json = json_encode($counts);

// Fetch data for the table
$query_table = "SELECT Name, Blood_group FROM donate";
$result_table = mysqli_query($conn, $query_table);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            height: 100vh;
            padding: 20px 0;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
          
            text-align: center;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #575757;
        }

        .dashboard {
            flex-grow: 1;
            padding: 20px;
        }

        .chart-container {
            margin-left: 10%;
            width: 50%;
       
        }

        /* Table styling */
        .table-container {
            margin-left: 15%;
            width: 80%;
            overflow-x: auto;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
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

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody tr:hover {
            background-color: #d1e7dd;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <img src="images/logo1.jpg" alt="Logo">
            </div>
            <div class="icons">
                <a href="#" class="icon"><i class="fas fa-sign-in-alt"></i></a>
                <a href="#" class="icon"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="graph.php">Blood Group</a></li>
                <li><a href="#">Add Blood Group</a></li>
                <li><a href="#">Available Blood</a></li>
                <li><a href="#">Donor List</a></li>
                <li><a href="#">Request for Blood</a></li>
            </ul>
        </div>
        <div class="dashboard">
            <!-- Section for the pie chart -->
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>

            <!-- Section for the table -->
            <div class="table-container">
                <h2>Donor List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Blood Group</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_table)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td><?php echo htmlspecialchars($row['Blood_group']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo $blood_groups_json; ?>,
                datasets: [{
                    label: 'Blood Donations by Group',
                    data: <?php echo $counts_json; ?>,
                    backgroundColor: [
                        '#4CAF50',
                        'yellow',
                        'red',
                        'orange',
                        'blue',
                        'orange'
                    ],
                    borderColor: [
                        '#4CAF50',
                        'yellow',
                        'red',
                        'orange',
                        'blue',
                        'orange'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>
