<?php

// Include database connection and header files
require_once(__DIR__."/testfunction.php");
require_once(__DIR__ . "/connect.php");
require_once(__DIR__ . "/user_header.php");
if (isset($_POST['submit'])) {
    $isPageReloaded = $_POST['isPageReloaded'];
    if ($isPageReloaded === "false") {
        $studentID = $_POST['studentID'];
        // Display the student's information
    } else {
        // Do nothing or display a message asking the user to submit the form again
    }
}$grade_mapping = [
    "A" => 90,
    "A-" => 85,
    "B+" => 80,
    "B" => 75,
    "B-" => 70,
    "C+" => 65,
    "C" => 60,
    "C-" => 55,
    "D+" => 50,
    "D" => 45,
    "F" => 43,
];
$grade_point_mapping = [
    "A" => 4.0,
    "A-" => 3.7,
    "B+" => 3.3,
    "B" => 3.0,
    "B-" => 2.7,
    "C+" => 2.3,
    "C" => 2.0,
    "C-" => 1.7,
    "D+" => 1.3,
    "D" => 1.0,
    "F" => 0.0,
];
// Get status message
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'success':
            $statusType = 'alert-success';
            $statusMsg = 'Data has been imported successfully.';
            break;
        case 'error':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}

// Import data from CSV file
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
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                $facultyID = $_SESSION['ID'];

                $studentID = $line[0];
                $sectionNum = $line[1];
                $semester = isset($line[2]) ? $line[2] : '';
                $courseID = isset($line[3]) ? $line[3] : '';
                $year = isset($line[4]) ? $line[4] : '';
                $obtainGrade = isset($line[5]) ? $line[5] : '';
 
                   // Check whether section already exists in the database with the same sectionNum, semester, courseID, and year
                   $prevQuery = "SELECT sectionID FROM section_t WHERE sectionNum = '$sectionNum' AND semester = '$semester' AND courseID = '$courseID' AND  facultyID ='$facultyID' AND year = '$year'";
                   $prevResult = $con->query($prevQuery);
   
                   if ($prevResult->num_rows > 0) {
                       // Get sectionID
                       $row = $prevResult->fetch_assoc();
                       $sectionID = $row['sectionID'];
                   } else {
                       // Insert section data into section_t table
                       $con->query("INSERT INTO section_t (sectionNum, semester, courseID,facultyID , year) VALUES ('$sectionNum', '$semester', '$courseID', '$facultyID','$year')");
   
                       // Get sectionID
                       $sectionID = $con->insert_id;
                   }
                // Insert registration data into registration_t table
                $con->query("INSERT INTO registration_t (sectionID, studentID) VALUES ('".$sectionID."', '".$studentID."')");
         // Get registrationID
                $registrationID = $con->insert_id;

                $markObtained = $grade_mapping[$obtainGrade];
                $totalMarksObtained = $grade_mapping[$obtainGrade];
                $gradePoint = $grade_point_mapping[$obtainGrade];

              // Insert student course performance data into student_course_performance_t table
              $con->query("INSERT INTO student_course_performance_t (registrationID, totalMarksObtained, gradePoint, obtainGrade) VALUES ('$registrationID', '$totalMarksObtained', '$gradePoint', '$obtainGrade')");

            //insert into question_t
            $markPerQuestion = 100;
            
            $coNum =random_int(1, 4);
            $coNum2=$coNum;
            // Insert data into question_t
            $difficultyLevel=random_int(1, 5);
            $questionNum = random_int(1, 4);
            $answerNum = $questionNum ; 
            $markPerQuestion = 100;
            $examID=random_int(10, 16);
            $examID2=$examID;

            $con->query("INSERT INTO question_t (markPerQuestion,questionNum,difficultyLevel,examID,courseID ,coNum) VALUES ('$markPerQuestion','$questionNum', '$difficultyLevel','$examID','$courseID','$coNum')");
            $questionID = $con->insert_id;

            $sql = "SELECT programID FROM program_t WHERE programID BETWEEN 9 AND 13 ORDER BY RAND() LIMIT 1";
            $result = $con->query($sql);
            $row = $result->fetch_assoc();
            $programID = $row['programID'];
            


            
            // Determine the common value for ploNum and poNum
            // $ploNum = mt_rand(1, 6);
            $ploNum = random_int(1, 6);
            $poNum = $ploNum;
            // Insert data into plo_t and get the ploID
$con->query("INSERT INTO plo_t (ploNum, programID) VALUES ('$ploNum', '$programID')");
$ploID = $con->insert_id;

// Insert data into po_t and get the poID
$con->query("INSERT INTO po_t (poNum, programID) VALUES ('$poNum', '$programID')");
$poID = $con->insert_id;

            // Insert coNum into co_t table
$con->query("INSERT INTO co_t (coNum, courseID, ploID, poID) VALUES ('$coNum2', ' $courseID' ,'$ploID', '$poID')");



// Insert data into answer_t


$con->query("INSERT INTO answer_t (answerNum,markObtained, registrationID, questionID,examID) VALUES ('$answerNum','$markObtained', '$registrationID', '$questionID','$examID')");





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

// Retrieve data from database
// $sections = array();
// $result = $con->query("SELECT section_t.*, registration_t.studentID, student_course_performance_t.obtainGrade FROM section_t INNER JOIN registration_t ON section_t.sectionID = registration_t.sectionID INNER JOIN student_course_performance_t ON registration_t.registrationID = student_course_performance_t.registrationID ORDER BY section_t.sectionNum, section_t.semester, section_t.courseID, section_t.year");

// SELECT section_t.*, registration_t.studentID, student_course_performance_t.obtainGrade FROM section_t INNER JOIN registration_t ON section_t.sectionID = registration_t.sectionID INNER JOIN student_course_performance_t ON registration_t.registrationID = student_course_performance_t.registrationID ORDER BY section_t.sectionNum, section_t.semester, section_t.courseID, section_t.year




// right one
// SELECT   registration_t.studentID, section_t.sectionNum, section_t.semester, section_t.courseID, section_t.year, student_course_performance_t.obtainGrade
// FROM registration_t
// JOIN section_t ON registration_t.sectionID = section_t.sectionID
// JOIN student_course_performance_t ON registration_t.registrationID = student_course_performance_t.registrationID;



$sections = array();
$result = $con->query("SELECT registration_t.studentID, section_t.sectionNum, section_t.semester, section_t.courseID, section_t.year, student_course_performance_t.obtainGrade,
CASE
    WHEN student_course_performance_t.obtainGrade = 'A' THEN 90
    WHEN student_course_performance_t.obtainGrade = 'A-' THEN 85
    WHEN student_course_performance_t.obtainGrade = 'B+' THEN 80 
    WHEN student_course_performance_t.obtainGrade = 'B' THEN 75
    WHEN student_course_performance_t.obtainGrade = 'B-' THEN 70
    WHEN student_course_performance_t.obtainGrade = 'C+' THEN 65
    WHEN student_course_performance_t.obtainGrade = 'C' THEN 60
    WHEN student_course_performance_t.obtainGrade = 'C-' THEN 55
    WHEN student_course_performance_t.obtainGrade = 'D+' THEN 50
    WHEN student_course_performance_t.obtainGrade = 'D' THEN 45
    ELSE 43
END as co
FROM registration_t
JOIN section_t ON registration_t.sectionID = section_t.sectionID
JOIN student_course_performance_t ON registration_t.registrationID = student_course_performance_t.registrationID;");

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $sectionKey = $row['sectionNum'].'-'.$row['semester'].'-'.$row['courseID'].'-'.$row['year'];
        if(!isset($sections[$sectionKey])){
            $sections[$sectionKey] = array(
                'studentID' => $row['studentID'],
                'sectionNum' => $row['sectionNum'],
                'semester' => $row['semester'],
                'courseID' => $row['courseID'],
                'year' => $row['year'],
                'obtainGrade' => $row['obtainGrade'],
                'students' => array()
            );
        }

        $sections[$sectionKey]['students'][] = array(
            'studentID' => $row['studentID'],
            'sectionNum' => $row['sectionNum'],
            'semester' => $row['semester'],
            'courseID' => $row['courseID'],
            'year' => $row['year'],
            'obtainGrade' => $row['obtainGrade'],
            'co' => $row['co']
        );
    }
}


?>

<!-- <?php

//echo pre($_SESSION);

if (isset($_POST['submit'])) {
    $studentID = $_POST['studentID'];
} elseif (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
}

// Fetch student data

?> -->
<!DOCTYPE html>
  <!-- Coding by CodingLab | www.codinglabweb.com -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <title>Employee Dashboard</title>


   
   
    <!--Google Font-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- <link rel="stylesheet" href="style.css"> -->
     <!----======== CSS ======== -->
     <link rel="stylesheet" href="styleplo.css">
    <!-- <link rel="stylesheet" href="style3.css"> -->
    
    
    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
    <script type="text/javascript"></script>  


    <style>
        body{
            background-color:white;
            /* background-color:#155977; */
        }

        ::placeholder{
          color:white;
        }

        ::-ms-input-placeholder{
          color:white;
        }

        :-ms-input-placeholder{
          color:white;
        }

    </style>

  </head>

  <body>
    
  <nav class="sidebar close">

       

<header>
    <div class="image-text">
        
    </div>

    <i class='bx bx-chevron-right toggle'></i>
</header>

<div class="menu-bar">
    <div class="menu">

      

        <ul class="menu-links">
            <li class="nav-link">
                <a href="estudent_dashboard.php">
                    <i class='bx bx-home-alt icon' ></i>
                    <span class="text nav-text">Dashboard</span>
                </a>
                 
            </li>
            
            <li class="nav-link">
                <a href="ploAnalysisOverallST.php" target="_self">
                    <i class='bx bx-bar-chart-alt-2 icon'></i>
                    <span class="text nav-text">PLO Analysis (Overall, CO Wise, Course Wise)</span>
                </a>
               
            </li>
            

        </ul>
    </div>

    <div class="bottom-content">
        <li class="">
            <a href="logout.php">
                <i class='bx bx-log-out icon' ></i>
                <span class="text nav-text">Logout</span>
            </a>
        </li>

        <li class="mode">
            <div class="sun-moon">
                <i class='bx bx-moon icon moon'></i>
                <i class='bx bx-sun icon sun'></i>
            </div>
            <span class="mode-text text">Dark mode</span>

            <div class="toggle-switch">
                <span class="switch"></span>
            </div>
        </li>
        
    </div>
</div>

</nav>





     
    <div style="margin-top: 100px;" class="container">
        <h2>Student Course Performance</h2>
         <!-- Display status message -->
    <?php if(!empty($statusMsg)){ ?>
        <div class="col-xs-12">
            <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
            <?php } ?>

    <!-- Import link -->
    <div class="col-md-12 head">
        <div class="float-right">
            <a href="javascript:void(0);" class="btn btn-danger" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
        </div>

        <!-- CSV file upload form -->
        <div class="col-md-12" id="importFrm" style="display: none;">
            <form action="importStudentdata.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-dark" name="importSubmit" value="IMPORT">
            </form>
        </div>
    </div>

    <!-- Display data -->
            <!-- Display data -->
<div class="row">
    <?php if(empty($sections)){ ?>
        <div class="col-xs-12">
            <div class="alert alert-info">No data found.</div>
        </div>
    <?php }else{ ?>
        <div class="col-xs-12">
            <!-- <h3>?php echo $sections[array_key_first($sections)]['studentID'].' - '.$sections[array_key_first($sections)]['sectionNum'].'- '.$sections[array_key_first($sections)]['semester'].' - '.$sections[array_key_first($sections)]['courseID'].' - '.$sections[array_key_first($sections)]['year'].'- '.$sections[array_key_first($sections)]['obtainGrade']; ?></h3> -->
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                    <input type="hidden" id="isPageReloaded" name="isPageReloaded" value="false">

                        <th>studentID</th>
                        <th>sectionNum</th>
                        <th>semester</th>
                        <th>courseID</th>
                        <th>year</th>
                        <th>obtainGrade</th>
                        <th>co</th>
                        <th>co1</th>
                        <th>co2</th>
                        <th>co3</th>
                        <th>co4</th>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sections as $section){ ?>
                        <?php foreach($section['students'] as $student){ ?>
                            <tr>
                                <td><?php echo $student['studentID']; ?></td>
                                <td><?php echo $student['sectionNum']; ?></td>
                                <td><?php echo $student['semester']; ?></td>
                                <td><?php echo $student['courseID']; ?></td>
                                <td><?php echo $student['year']; ?></td>
                                <td><?php echo $student['obtainGrade']; ?></td>
                                <td><?php echo $student['co']; ?>%</td>
                                <td><?php echo $student['co']; ?>%</td>
                                <td><?php echo $student['co']; ?>%</td>
                                <td><?php echo $student['co']; ?>%</td>
                                <td><?php echo $student['co']; ?>%</td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

</div>
</div>    


<!-- Overall plo -->
<script>
    window.onload = function() {
    if (performance.navigation.type === 1) { // If the page is reloaded
      document.getElementById("isPageReloaded").value = "true";
      document.getElementById("yourFormId").submit(); // Replace 'yourFormId' with the actual ID of your form
    }
  }
</script> 


<!-- Co wise plo -->
<script>
function coWisePlo(){
   
    }
</script>

<!-- course wise plo -->

<script>
function courseWisePlo(){


    }
</script>

</body>

</html>




















<!-- Bootstrap library -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

<script>
    function formToggle(ID){
        var element = document.getElementById(ID);
        if(element.style.display === "none"){
            element.style.display = "block";
        }else{
            element.style.display = "none";
        }
    }
</script>
</body>
</html>


