<?php
// Handle POST request to insert a new loan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $Eid = $_POST["Eid"];
    $LoanAmount = $_POST["LoanAmount"];
    $LoanDate = $_POST["LoanDate"];

    // Connect to the database
    $connection = new mysqli("localhost", "root", "", "employee");

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Insert the new loan into the loan table
    $stmt = $connection->prepare("INSERT INTO loan (Eid, LoanAmount, Date) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $Eid, $LoanAmount, $LoanDate);

    if ($stmt->execute()) {
        // Redirect to the main page with a success message
        header("Location: /employee/index.php?msg=loan_created");
    } else {
        // Show an error message
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
    exit;
}
?>

<!-- HTML Form to add new loan -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Loan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Loan</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="Eid" class="form-label">Employee ID</label>
            <input type="number" class="form-control" id="Eid" name="Eid" required>
        </div>
        <div class="mb-3">
            <label for="LoanAmount" class="form-label">Loan Amount</label>
            <input type="number" step="0.01" class="form-control" id="LoanAmount" name="LoanAmount" required>
        </div>
        <div class="mb-3">
            <label for="LoanDate" class="form-label">Loan Date</label>
            <input type="date" class="form-control" id="LoanDate" name="LoanDate">
        </div>
        <button type="submit" class="btn btn-success">Add Loan</button>
        <a href="/employee/index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
