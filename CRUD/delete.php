<?php
/**
 * Delete equipment handler
 */
require_once("auth_check.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = mysqli_prepare($mysqli, "DELETE FROM alat WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: index.php?msg=deleted");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>