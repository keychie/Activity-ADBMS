<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "employee";
$connection = new mysqli($servername, $username, $password, $database);

$name = "";
$position = "";
$salary = "";
$age = "";
$address = "";
$deptCode = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST["name"]);
    $position = trim($_POST["position"]);
    $salary = trim($_POST["salary"]);
    $age = trim($_POST["age"]);
    $address = trim($_POST["address"]);
    $deptCode = trim($_POST["deptCode"]);

    do {
        if (empty($name) || empty($position) || empty($salary) || empty($age) || empty($address) || empty($deptCode)) {
            $errorMessage = "All fields are required.";
            break;
        }

        $stmt = $connection->prepare("INSERT INTO employeeinfo (Name, Position, Salary, Age, Address, DeptCode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiis", $name, $position, $salary, $age, $address, $deptCode);

        if (!$stmt->execute()) {
            $errorMessage = "Database error: " . $stmt->error;
            break;
        }

        $name = $position = $salary = $age = $address = $deptCode = "";
        $successMessage = "Employee added successfully.";

        header("Location: /employee/index.php?msg=added");
        exit;

    } while (false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h2>Add New Employee</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong><?= $errorMessage ?></strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($name) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Position</label>
            <input type="text" class="form-control" name="position" value="<?= htmlspecialchars($position) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Salary</label>
            <input type="number" class="form-control" name="salary" value="<?= htmlspecialchars($salary) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" class="form-control" name="age" value="<?= htmlspecialchars($age) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($address) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">DeptCode</label>
            <input type="text" class="form-control" name="deptCode" value="<?= htmlspecialchars($deptCode) ?>">
        </div>

        <?php if (!empty($successMessage)): ?>
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong><?= $successMessage ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-outline-primary">Submit</button>
            <a class="btn btn-outline-secondary" href="/employee/index.php">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
