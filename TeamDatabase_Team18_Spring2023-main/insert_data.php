<?php

require_once(__DIR__."/testfunction.php");
require_once(__DIR__ . "/connect.php");
require_once(__DIR__ . "/user_header.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_POST['studentID'];
    $sectionNum = $_POST['sectionNum'];
    $semester = $_POST['semester'];
    $courseID = $_POST['courseID'];
    $year = $_POST['year'];
    $obtainGrade = $_POST['obtainGrade'];

    // Insert data into section_t
    $sectionInsert = $con->prepare("INSERT INTO section_t (sectionNum, semester, courseID, year) VALUES (?, ?, ?, ?)");
    $sectionInsert->bind_param("ssss", $sectionNum, $semester, $courseID, $year);
    $sectionInsert->execute();
    $sectionID = $con->insert_id;

    // Insert data into registration_t
    $registrationInsert = $con->prepare("INSERT INTO registration_t (sectionID, studentID) VALUES (?, ?)");
    $registrationInsert->bind_param("ss", $sectionID, $studentID);
    $registrationInsert->execute();
    $registrationID = $con->insert_id;

    // Insert data into student_course_performance_t
    $scpInsert = $con->prepare("INSERT INTO student_course_performance_t (registrationID, obtainGrade) VALUES (?, ?)");
    $scpInsert->bind_param("ss", $registrationID, $obtainGrade);
    $scpInsert->execute();

    // Redirect back to the original page
    header("Location: estudent_dashboard.php");
    exit();
}
?>
