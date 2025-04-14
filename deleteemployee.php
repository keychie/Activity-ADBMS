<?php
if (isset($_GET["Eid"]) && is_numeric($_GET["Eid"])) {
    $Eid = $_GET["Eid"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "employee";

    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Delete from loan table first (child)
    $deleteLoan = $connection->prepare("DELETE FROM loan WHERE Eid = ?");
    $deleteLoan->bind_param("i", $Eid);
    $deleteLoan->execute();
    $deleteLoan->close();

    // Delete from employee table (parent)
    $stmt = $connection->prepare("DELETE FROM employeeinfo WHERE Eid = ?");
    $stmt->bind_param("i", $Eid);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            header("Location: /employee/index.php?msg=deleted");
        } else {
            header("Location: /employee/index.php?msg=not_found");
        }
    } else {
        header("Location: /employee/index.php?msg=error");
    }

    $stmt->close();
    $connection->close();
} else {
    header("Location: /employee/index.php?msg=invalid");
}
exit;
