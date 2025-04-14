<?php
if (!isset($_GET["Eid"]) || !is_numeric($_GET["Eid"])) {
    header("Location: index.php?msg=invalid");
    exit;
}

$Eid = $_GET["Eid"];
$servername = "localhost";
$username = "root";
$password = "";
$database = "employee";

// Connect to DB
$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch employee info + department description
$empSql = "
    SELECT e.*, d.DeptDescription
    FROM employeeinfo e
    LEFT JOIN departmentinfo d ON e.DeptCode = d.DeptCode
    WHERE e.Eid = ?
";
$empStmt = $connection->prepare($empSql);
$empStmt->bind_param("i", $Eid);
$empStmt->execute();
$empResult = $empStmt->get_result();

if ($empResult->num_rows === 0) {
    echo "Employee not found.";
    exit;
}

$employee = $empResult->fetch_assoc();

// Fetch loans for this employee
$loanSql = "SELECT * FROM loan WHERE Eid = ?";
$loanStmt = $connection->prepare($loanSql);
$loanStmt->bind_param("i", $Eid);
$loanStmt->execute();
$loanResult = $loanStmt->get_result();

$totalLoan = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Employee Details</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← Back to List</a>

    <table class="table table-bordered">
        <tr><th>EID</th><td><?= $employee['Eid'] ?></td></tr>
        <tr><th>Name</th><td><?= $employee['Name'] ?></td></tr>
        <tr><th>Position</th><td><?= $employee['Position'] ?></td></tr>
        <tr><th>Salary</th><td><?= $employee['Salary'] ?></td></tr>
        <tr><th>Age</th><td><?= $employee['Age'] ?></td></tr>
        <tr><th>Address</th><td><?= $employee['Address'] ?></td></tr>
        <tr><th>Department Code</th><td><?= $employee['DeptCode'] ?></td></tr>
        <tr><th>Department Description</th><td><?= $employee['DeptDescription'] ?? 'N/A' ?></td></tr>
    </table>

    <h4 class="mt-4">Loan History</h4>
    <?php if ($loanResult->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Loan Amount</th>
                    <th>Loan Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loan = $loanResult->fetch_assoc()): ?>
                    <?php $totalLoan += $loan['LoanAmount']; ?>
                    <tr>
                        <td><?= $loan['Eid'] ?></td>
                        <td><?= $loan['LoanAmount'] ?></td>
                        <td><?= $loan['Date'] ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr class="table-info">
                    <td colspan="2"><strong>Total Loan Amount:</strong></td>
                    <td><strong><?= $totalLoan ?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>No loans found for this employee.</p>
    <?php endif; ?>

</div>
</body>
</html>
