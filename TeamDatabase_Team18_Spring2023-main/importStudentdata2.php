<?php

// Include database connection and header files
require_once(__DIR__."/testfunction.php");
require_once(__DIR__ . "/connect.php");
require_once(__DIR__ . "/user_header.php");

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
function gradeToPercentage($grade){
    switch($grade){
        case 'A': return mt_rand(90, 99);
        case 'A-': return mt_rand(85, 89);
        case 'B+': return mt_rand(80, 84);
        case 'B': return mt_rand(75, 79);
        case 'B-': return mt_rand(70, 74);
        case 'C+': return mt_rand(65, 69);
        case 'C': return mt_rand(60, 64);
        case 'C-': return mt_rand(55, 59);
        case 'D+': return mt_rand(50, 54);
        case 'D': return mt_rand(45, 49);
        default: return mt_rand(1, 44);
    }
}


// Import data from CSV file

$csvData = array();

if (isset($_POST['importSubmit'])) {
    // .display the $mime_types variable
    $mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $mime_types)) {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            // ...

            // Store the CSV data in an array
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            fgetcsv($csvFile); // Skip the first line (header)
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                $csvData[] = array(
                    // ...
                    
                        'studentID' => isset($line[0]) ? $line[0] : '',
                        'sectionNum' => isset($line[1]) ? $line[1] : '',
                        'semester' => isset($line[2]) ? $line[2] : '',
                        'courseID' => isset($line[3]) ? $line[3] : '',
                        'year' => isset($line[4]) ? $line[4] : '',
                        'obtainGrade' => isset($line[5]) ? $line[5] : ''
                    
                    
                );
            }
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



// SELECT registration_t.studentID, section_t.sectionNum, section_t.semester, section_t.courseID, section_t.year, student_course_performance_t.obtainGrade,
// CASE
//     WHEN student_course_performance_t.obtainGrade = 'A' THEN 90
//     WHEN student_course_performance_t.obtainGrade = 'A-' THEN 85
//     WHEN student_course_performance_t.obtainGrade = 'B+' THEN  80 
//     WHEN student_course_performance_t.obtainGrade = 'B' THEN  75 
//     WHEN student_course_performance_t.obtainGrade = 'B-' THEN  70 
//     WHEN student_course_performance_t.obtainGrade = 'C+' THEN  65
//     WHEN student_course_performance_t.obtainGrade = 'C' THEN  60 
//     WHEN student_course_performance_t.obtainGrade = 'C-' THEN  55 
//     WHEN student_course_performance_t.obtainGrade = 'D+' THEN  50 
//     WHEN student_course_performance_t.obtainGrade = 'D' THEN 45 
//     ELSE 43
// END as co
// FROM registration_t
// JOIN section_t ON registration_t.sectionID = section_t.sectionID
// JOIN student_course_performance_t ON registration_t.registrationID = student_course_performance_t.registrationID;







$sections = array();
$result = $con->query("SELECT distinct registration_t.studentID, section_t.sectionNum, section_t.semester, section_t.courseID, section_t.year, student_course_performance_t.obtainGrade
FROM registration_t
JOIN section_t ON registration_t.sectionID = section_t.sectionID
JOIN student_course_performance_t ON registration_t.registrationID = student_course_performance_t.registrationID;");


if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        foreach($csvData as $csvRow){
            if($row['studentID'] == $csvRow['studentID'] && $row['sectionNum'] == $csvRow['sectionNum'] && $row['semester'] == $csvRow['semester'] && $row['courseID'] == $csvRow['courseID'] && $row['year'] == $csvRow['year'] && $row['obtainGrade'] == $csvRow['obtainGrade']){
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
                    'obtainGrade' => $row['obtainGrade']
                );
            }
        }
    }
}




?>

<!DOCTYPE html>
  <!-- Coding by CodingLab | www.codinglabweb.com -->
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
<div style="margin-top: 150px;" class="container">
        <h2>Student Course Performance</h2>
         <!-- Display status message -->
    <?php if(!empty($statusMsg)){ ?>
        <div class="col-xs-12">
            <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
            <?php } ?>

    <!-- Import link -->
    <div class="col-md-12 head">
        <!-- <div class="float-right">
            <a href="javascript:void(0);" class=" btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> </a>
        </div> -->
        <div class="float-right">
            <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
        </div>

        <!-- CSV file upload form -->
        <!-- <div class="col-md-12" id="importFrm" >
          
            <form action="importStudentdata2.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-dark" name="importSubmit" value="IMPORT">
            </form>
        </div> -->
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
                        <th>studentID</th>
                        <th>sectionNum</th>
                        <th>semester</th>
                        <th>courseID</th>
                        <th>year</th>
                        <th>obtainGrade</th>
                        <th>co</th>
                      
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
                                <td><?php echo gradeToPercentage($student['obtainGrade']); ?>%</td>

                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

</div>

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


