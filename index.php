<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container m-5">
        <h2>List of Employees</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['msg'])): ?>
            <?php
            $alertType = "info";
            $message = "";

            switch ($_GET['msg']) {
                case "deleted":
                    $alertType = "success";
                    $message = "Employee deleted successfully.";
                    break;
                case "loan_created":
                    $alertType = "success";
                    $message = "Loan added successfully.";
                    break;
                case "loan_updated":
                    $alertType = "success";
                    $message = "Loan updated successfully.";
                    break;
                case "error":
                    $alertType = "danger";
                    $message = "An error occurred while processing your request.";
                    break;
                case "not_found":
                    $alertType = "warning";
                    $message = "Employee not found.";
                    break;
                case "invalid":
                    $alertType = "danger";
                    $message = "Invalid request.";
                    break;
                default:
                    $alertType = "info";
                    $message = "Action completed.";
            }
            ?>
            <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <a class="btn btn-primary" href="/employee/createemployee.php" role="button">New Employee</a>
        <br><br>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Eid</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Salary</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>DeptCode</th>
                    <th>Total Loan Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "employee";

                $connection = new mysqli($servername, $username, $password, $database);

                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }

                // SQL query to join employeeinfo with loan to get total loan per employee
                $sql = "
                    SELECT e.*, 
                           COALESCE(SUM(l.LoanAmount), 0) AS TotalLoanAmount
                    FROM employeeinfo e
                    LEFT JOIN loan l ON e.Eid = l.Eid
                    GROUP BY e.Eid
                ";

                $result = $connection->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$row['Eid']}</td>
                        <td>{$row['Name']}</td>
                        <td>{$row['Position']}</td>
                        <td>{$row['Salary']}</td>
                        <td>{$row['Age']}</td>
                        <td>{$row['Address']}</td>
                        <td>{$row['DeptCode']}</td>
                        <td>{$row['TotalLoanAmount']}</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/employee/editemployee.php?Eid={$row['Eid']}'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/employee/deleteemployee.php?Eid={$row['Eid']}' onclick=\"return confirm('Are you sure you want to delete this employee and all related loan records?');\">Delete</a>
                            <a class='btn btn-warning btn-sm' href='/employee/createloan.php?Eid={$row['Eid']}'>Add Loan</a>
                            <a class='btn btn-info btn-sm' href='/employee/editloan.php?Eid={$row['Eid']}'>Edit Loan</a>
                            <a class='btn btn-success btn-sm' href='/employee/viewemployee.php?Eid={$row['Eid']}'>View</a>

                        </td>
                    </tr>
                    ";
                }

                $connection->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
