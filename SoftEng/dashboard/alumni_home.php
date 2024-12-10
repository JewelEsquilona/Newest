<?php
session_start();
include '../connection.php';
include 'user_privileges.php';

// Check if user is logged in and has the 'Alumni' role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Alumni') {
    header('Location: ../index.php');
    exit();
}
$alumniId = $_SESSION['alumni_id'];
$alumniQuery = "SELECT * FROM `2024-2025` WHERE Alumni_ID_Number = :alumni_id";
$statement = $con->prepare($alumniQuery);
$statement->bindParam(':alumni_id', $alumniId);
$statement->execute();
$alumniData = $statement->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni List</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="container mt-5">
        <h1>Alumni List</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Number</th>
                    <th>Name</th>
                    <th>College</th>
                    <th>Department</th>
                    <th>Year Graduated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumni as $row): ?>
                    <tr>
                        <td><?= $row['Alumni_ID_Number'] ?></td>
                        <td><?= $row['Student_Number'] ?></td>
                        <td><?= $row['First_Name'] . ' ' . $row['Last_Name'] ?></td>
                        <td><?= $row['College'] ?></td>
                        <td><?= $row['Department'] ?></td>
                        <td><?= $row['Year_Graduated'] ?></td>
                        <td>
                            <a href="alumni_edit.php?id=<?= $row['Alumni_ID_Number'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>
</html>
