<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "employee";
$connection = new mysqli($servername, $username, $password, $database);

$Eid = "";
$Name = "";
$Position = "";
$Salary = "";
$Age = "";
$Address = "";
$DeptCode = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["Eid"])) {
        header("Location: /employee/index.php");
        exit;
    }

    $Eid = $_GET["Eid"];
    $sql = "SELECT * FROM employeeinfo WHERE Eid = $Eid";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: /employee/index.php");
        exit;
    }

    $Name = $row["Name"];
    $Position = $row["Position"];
    $Salary = $row["Salary"];
    $Age = $row["Age"];
    $Address = $row["Address"];
    $DeptCode = $row["DeptCode"];

} else {
    $Eid = $_POST["Eid"];
    $Name = $_POST["Name"];
    $Position = $_POST["Position"];
    $Salary = $_POST["Salary"];
    $Age = $_POST["Age"];
    $Address = $_POST["Address"];
    $DeptCode = $_POST["DeptCode"];

    do {
        if (empty($Name) || empty($Position) || empty($Salary) || empty($Age) || empty($Address) || empty($DeptCode)) {
            $errorMessage = "All fields are required.";
            break;
        }

        $sql = "UPDATE employeeinfo 
                SET Name = '$Name', Position = '$Position', Salary = '$Salary', Age = '$Age', Address = '$Address', DeptCode = '$DeptCode' 
                WHERE Eid = $Eid";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        $successMessage = "Employee updated successfully.";
        header("Location: /employee/index.php");
        exit;

    } while (false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Edit Employee</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
            ";
        }
        ?>

        <form method="post">
            <input type="hidden" name="Eid" value="<?php echo $Eid; ?>">

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="Name" value="<?php echo $Name; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Position</label>
                <input type="text" class="form-control" name="Position" value="<?php echo $Position; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Salary</label>
                <input type="number" class="form-control" name="Salary" value="<?php echo $Salary; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Age</label>
                <input type="number" class="form-control" name="Age" value="<?php echo $Age; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="Address" value="<?php echo $Address; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">DeptCode</label>
                <input type="text" class="form-control" name="DeptCode" value="<?php echo $DeptCode; ?>">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="/employee/index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
