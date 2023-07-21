<?php
// Include database configuration file
// require_once(__DIR__ . "/testfunction.php");
//   require_once(__DIR__ . "/connect.php");
//   require_once(__DIR__ . "/user_header.php");

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    // Validate whether selected file is CSV formatted or not
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $mime_types)){
        
        // If the file is uploaded successfully
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line (header)
            fgetcsv($csvFile);

            // Loop through the file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                $studentID = $line[0];
                $sectionNum = $line[1];
                $semester = isset($line[2]) ? $line[2] : '';
                $courseID = isset($line[3]) ? $line[3] : '';
                $year = isset($line[4]) ? $line[4] : '';
                $obtainGrade = isset($line[5]) ? $line[5] : '';
                
                
                // Check whether member already exists in the database with the same ID
                $prevQuery = "SELECT ID FROM members WHERE id = '".$line[0]."'";
                $prevResult = $db->query($prevQuery);

                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $db->query("UPDATE section_t SET Name = '".$Name."', CourseID = '".$CourseID."', Section = '".$Section."', Mid = '".$Mid."',  Final = '".$Final."', Project = '".$Project."', Total = '".$Total."', CoTotal = '".$CoTotal."', Grade = '".$Grade."' WHERE Id = '".$Id."'");
                }else{
                    // Insert member data in the database
                    $db->query("INSERT INTO section_t (Id, Name, CourseID, Section, Mid, Final, Project, Total, CoTotal, Grade) VALUES ('".$Id."', '".$Name."', '".$CourseID."', '".$Section."', '".$Mid."', '".$Final."', '".$Project."', '".$Total."', '".$CoTotal."', '".$Grade."')"); //NOW(), NOW())"
                }
            }
            
            // Close opened CSV file
            fclose($csvFile);

            $qstring = '?status=success';
        } else {
            $qstring = '?status=error';
        }
    } else {
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: index.php".$qstring);    
?>
