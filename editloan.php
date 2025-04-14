<?php
// Fetch existing loan details based on LoanID
if (isset($_GET["Eid"]) && is_numeric($_GET["Eid"])) {
    $Eid = $_GET["Eid"];

    // Database connection
    $connection = new mysqli("localhost", "root", "", "employee");

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Fetch the loan details
    $stmt = $connection->prepare("SELECT * FROM loan WHERE Eid = ?");
    $stmt->bind_param("i", $Eid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Loan not found.");
    }

    // Get the loan data
    $loan = $result->fetch_assoc();

    $stmt->close();
    $connection->close();
} else {
    die("Invalid Eid.");
}

// Handle form submission to update loan details
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $LoanAmount = $_POST["LoanAmount"];
    $Date = $_POST["Date"];

    // Connect to the database
    $connection = new mysqli("localhost", "root", "", "employee");

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Update the loan details in the database
    $stmt = $connection->prepare("UPDATE loan SET LoanAmount = ?, Date = ? WHERE Eid = ?");
    $stmt->bind_param("dsi", $LoanAmount, $Date, $Eid);

    if ($stmt->execute()) {
        header("Location: /employee/index.php?msg=loan_updated");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
    exit;
}
?>

<!-- HTML Form to Edit Loan -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Loan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Loan</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="LoanAmount" class="form-label">Loan Amount</label>
            <input type="number" step="0.01" class="form-control" id="LoanAmount" name="LoanAmount" value="<?= htmlspecialchars($loan['LoanAmount']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="LoanDate" class="form-label">Loan Date</label>
            <input type="date" class="form-control" id="Date" name="Date" value="<?= htmlspecialchars($loan['Date']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Update Loan</button>
        <a href="/employee/index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
