<?php
session_start();
include '../connection.php';
include 'user_privileges.php';

// Check if user is logged in and has the 'Alumni' role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Alumni') {
    header('Location: ../index.php');
    exit();
}

$alumniId = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentNumber = $_POST['student_number'];
    $lastName = $_POST['last_name'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $college = $_POST['college'];
    $department = $_POST['department'];
    $section = $_POST['section'];
    $yearGraduated = $_POST['year_graduated'];
    $contactNumber = $_POST['contact_number'];
    $personalEmail = $_POST['personal_email'];
    $employment = $_POST['employment'];
    $employmentStatus = $_POST['employment_status'];
    $presentOccupation = $_POST['present_occupation'];
    $nameOfEmployer = $_POST['name_of_employer'];
    $addressOfEmployer = $_POST['address_of_employer'];
    $numberOfYearsInPresentEmployer = $_POST['number_of_years_in_present_employer'];
    $typeOfEmployer = $_POST['type_of_employer'];
    $majorLineOfBusiness = $_POST['major_line_of_business'];

    // Update the 2024-2025 table
    $updateQuery = "UPDATE `2024-2025` SET
        Student_Number = :student_number,
        Last_Name = :last_name,
        First_Name = :first_name,
        Middle_Name = :middle_name,
        College = :college,
        Department = :department,
        Section = :section,
        Year_Graduated = :year_graduated,
        Contact_Number = :contact_number,
        Personal_Email = :personal_email
    WHERE Alumni_ID_Number = :alumni_id";

    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bindParam(':student_number', $studentNumber);
    $updateStmt->bindParam(':last_name', $lastName);
    $updateStmt->bindParam(':first_name', $firstName);
    $updateStmt->bindParam(':middle_name', $middleName);
    $updateStmt->bindParam(':college', $college);
    $updateStmt->bindParam(':department', $department);
    $updateStmt->bindParam(':section', $section);
    $updateStmt->bindParam(':year_graduated', $yearGraduated);
    $updateStmt->bindParam(':contact_number', $contactNumber);
    $updateStmt->bindParam(':personal_email', $personalEmail);
    $updateStmt->bindParam(':alumni_id', $alumniId);

    if ($updateStmt->execute()) {
        // Update the 2024-2025_ed table
        $updateEdQuery = "UPDATE `2024-2025_ed` SET
            Employment = :employment,
            Employment_Status = :employment_status,
            Present_Occupation = :present_occupation,
            Name_of_Employer = :name_of_employer,
            Address_of_Employer = :address_of_employer,
            Number_of_Years_in_Present_Employer = :number_of_years_in_present_employer,
            Type_of_Employer = :type_of_employer,
            Major_Line_of_Business = :major_line_of_business
        WHERE Alumni_ID_Number = :alumni_id";

        $updateEdStmt = $con->prepare($updateEdQuery);
        $updateEdStmt->bindParam(':employment', $employment);
        $updateEdStmt->bindParam(':employment_status', $employmentStatus);
        $updateEdStmt->bindParam(':present_occupation', $presentOccupation);
        $updateEdStmt->bindParam(':name_of_employer', $nameOfEmployer);
        $updateEdStmt->bindParam(':address_of_employer', $addressOfEmployer);
        $updateEdStmt->bindParam(':number_of_years_in_present_employer', $numberOfYearsInPresentEmployer);
        $updateEdStmt->bindParam(':type_of_employer', $typeOfEmployer);
        $updateEdStmt->bindParam(':major_line_of_business', $majorLineOfBusiness);
        $updateEdStmt->bindParam(':alumni_id', $alumniId);

        if ($updateEdStmt->execute()) {
            echo "<script>alert('Alumni data updated successfully!'); window.location.href = 'alumni_list.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error updating alumni data. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Error updating alumni data. Please try again.');</script>";
    }
}

// Fetch alumni data
$alumniId = $_SESSION['alumni_id'];
$alumniQuery = "SELECT * FROM `2024-2025` WHERE Alumni_ID_Number = :alumni_id";
$statement = $con->prepare($alumniQuery);
$statement->bindParam(':alumni_id', $alumniId);
$statement->execute();
$alumniData = $statement->fetch(PDO::FETCH_ASSOC);

$employmentQuery = "SELECT * FROM `2024-2025_ed` WHERE Alumni_ID_Number = :alumni_id";
$statement = $con->prepare($employmentQuery);
$statement->bindParam(':alumni_id', $alumniId);
$statement->execute();
$employmentData = $statement->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alumni</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/reg.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="container form-container mt-5">
        <header class="mb-4">Edit Alumni</header>
        <form action="" method="POST" class="form">
            <div class="mb-3">
                <label for="student-number" class="form-label">Student Number</label>
                <input type="text" id="student-number" name="student_number" class="form-control" value="<?= $alumniData['Student_Number'] ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $alumniData['Last_Name'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $alumniData['First_Name'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= $alumniData['Middle_Name'] ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="college" class="form-label">College</label>
                    <input type="text" class="form-control" id="college" name="college" value="<?= $alumniData['College'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" class="form-control" id="department" name="department" value="<?= $alumniData['Department'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="section" class="form-label">Section</label>
                    <input type="text" class="form-control" id="section" name="section" value="<?= $alumniData['Section'] ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="year_graduated" class="form-label">Year Graduated</label>
                    <input type="text" class="form-control" id="year_graduated" name="year_graduated" value="<?= $alumniData['Year_Graduated'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?= $alumniData['Contact_Number'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="personal_email" class="form-label">Personal Email</label>
                    <input type="email" class="form-control" id="personal_email" name="personal_email" value="<?= $alumniData['Personal_Email'] ?>" required>
                </div>
            </div>

            <div class="mb-3 row align-items-center">
                <div class="col-md-6">
                    <label for="employment" class="form-label">Employment</label>
                    <select id="employment" name="employment" class="form-control" required onchange="toggleEmploymentFields()">
                        <option value="">Select Employment</option>
                        <option value="Employed" <?= $edData['Employment'] === 'Employed' ? 'selected' : '' ?>>Employed</option>
                        <option value="Self-employed" <?= $edData['Employment'] === 'Self-employed' ? 'selected' : '' ?>>Self-employed</option>
                        <option value="Actively looking for a job" <?= $edData['Employment'] === 'Actively looking for a job' ? 'selected' : '' ?>>Actively Looking for a Job</option>
                        <option value="Never been employed" <?= $edData['Employment'] === 'Never been employed' ? 'selected' : '' ?>>Never Been Employed</option>
                    </select>
                </div>
                <div class="col-md-6" id="employment-status-container" style="display: <?= $edData['Employment'] === 'Employed' ? 'block' : 'none' ?>;">
                    <label for="employment_status" class="form-label">Employment Status</label>
                    <select id="employment_status" name="employment_status" class="form-control" required>
                        <option value="">Select Employment Status</option>
                        <option value="Regular/Permanent" <?= $edData['Employment_Status'] === 'Regular/Permanent' ? 'selected' : '' ?>>Regular/Permanent</option>
                        <option value="Casual" <?= $edData['Employment_Status'] === 'Casual' ? 'selected' : '' ?>>Casual</option>
                        <option value="Contractual" <?= $edData['Employment_Status'] === 'Contractual' ? 'selected' : '' ?>>Contractual</option>
                        <option value="Temporary" <?= $edData['Employment_Status'] === 'Temporary' ? 'selected' : '' ?>>Temporary</option>
                        <option value="Part-time (seeking full-time)" <?= $edData['Employment_Status'] === 'Part-time (seeking full-time)' ? 'selected' : '' ?>>Part-time (seeking full-time)</option>
                        <option value="Part-time (but not seeking full-time)" <?= $edData['Employment_Status'] === 'Part-time (but not seeking full-time)' ? 'selected' : '' ?>>Part-time (but not seeking full-time)</option>
                        <option value="Other" <?= $edData['Employment_Status'] === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
            </div>

            <div id="employmentFields" style="display: <?= $edData['Employment'] === 'Employed' ? 'block' : 'none' ?>;">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="present_occupation" class="form-label">Present Occupation</label>
                        <input type="text" class="form-control" id="present_occupation" name="present_occupation" value="<?= $edData['Present_Occupation'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="name_of_employer" class="form-label">Name of Employer</label>
                        <input type="text" class="form-control" id="name_of_employer" name="name_of_employer" value="<?= $edData['Name_of_Employer'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="address_of_employer" class="form-label">Address of Employer</label>
                        <input type="text" class="form-control" id="address_of_employer" name="address_of_employer" value="<?= $edData['Address_of_Employer'] ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="number_of_years_in_present_employer" class="form-label">Years in Present Employer</label>
                        <input type="number" class="form-control" id="number_of_years_in_present_employer" name="number_of_years_in_present_employer" value="<?= $edData['Number_of_Years_in_Present_Employer'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="type_of_employer" class="form-label">Type of Employer</label>
                        <input type="text" class="form-control" id="type_of_employer" name="type_of_employer" value="<?= $edData['Type_of_Employer'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="major_line_of_business" class="form-label">Major Line of Business</label>
                        <input type="text" class="form-control" id="major_line_of_business" name="major_line_of_business" value="<?= $edData['Major_Line_of_Business'] ?>">
                    </div>
                </div>
            </div>

            <div class="button-container d-flex justify-content-between mt-4">
                <a href="alumni_list.php" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <script>
        function toggleEmploymentFields() {
            const employmentStatus = document.getElementById('employment').value;
            const employmentStatusContainer = document.getElementById('employment-status-container');
            const employmentFields = document.getElementById('employmentFields');

            if (employmentStatus === 'Employed') {
                employmentStatusContainer.style.display = 'block';
                employmentFields.style.display = 'block';
            } else {
                employmentStatusContainer.style.display = 'none';
                employmentFields.style.display = 'none';
            }
        }
    </script>
</body>
</html>
