<?php

// Include database connection and header files
require_once(__DIR__."/testfunction.php");
require_once(__DIR__ . "/connect.php");
require_once(__DIR__ . "/user_header.php");
$result = false;
$grade_mapping = [
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



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["studentID"]) && $_POST["sectionNum"] && isset($_POST["semester"]) && isset($_POST["courseID"]) && isset($_POST["year"]) && isset($_POST["obtainGrade"])){

        $facultyID = $_SESSION['ID'];
        $studentID = $_POST["studentID"];
        $sectionNum = $_POST["sectionNum"];
        $semester = $_POST["semester"];
        $courseID = $_POST["courseID"];
        $year = $_POST["year"];
        $obtainGrade = $_POST["obtainGrade"];

        // Check if section already exists, if not insert it
        $sql = "INSERT INTO section_t (sectionNum, semester, courseID, facultyID, year)
                SELECT * FROM (SELECT '$sectionNum', '$semester', '$courseID','$facultyID', '$year') AS tmp
                WHERE NOT EXISTS (
                    SELECT sectionID FROM section_t WHERE sectionNum='$sectionNum' AND semester='$semester' AND courseID='$courseID' AND year='$year'
                ) LIMIT 1";

        if ($con->query($sql) === TRUE) {
            echo "New section added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        // Get the sectionID for the inserted section
        $sql = "SELECT sectionID FROM section_t WHERE sectionNum='$sectionNum' AND semester='$semester' AND courseID='$courseID' AND year='$year'";
        $result = $con->query($sql);
        $row = $result->fetch_assoc();
        $sectionID = $row['sectionID'];

        // Insert into registration_t table
        $sql = "INSERT INTO registration_t (sectionID, studentID) VALUES ('$sectionID', '$studentID')";

        if ($con->query($sql) === TRUE) {
            echo "New registration added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }

        // Get the registrationID for the inserted registration
        $sql = "SELECT registrationID FROM registration_t WHERE sectionID='$sectionID' AND studentID='$studentID'";
        $result = $con->query($sql);
        $row = $result->fetch_assoc();
        $registrationID = $row['registrationID'];

        // Insert into student_course_performance_t table
        // $sql = "INSERT INTO student_course_performance_t (registrationID, obtainGrade) VALUES ('$registrationID', '$obtainGrade')";

        // if ($con->query($sql) === TRUE) {
        //     echo "New student_course_performance added successfully.";
        // } else {
        //     echo "Error: " . $sql . "<br>" . $con->error;
        // }
        $markObtained = $grade_mapping[$obtainGrade];
        $totalMarksObtained = $grade_mapping[$obtainGrade];
        $gradePoint = $grade_point_mapping[$obtainGrade];


        $sql = "INSERT INTO student_course_performance_t (registrationID, totalMarksObtained, gradePoint, obtainGrade) VALUES ('$registrationID', '$totalMarksObtained', '$gradePoint', '$obtainGrade')";

        if ($con->query($sql) === TRUE) {
            echo "New student_course_performance added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
        

// Insert data into question_t
        $markPerQuestion = 100;
  // ...

// Increment coNum value each time a new question is inserted
// $coNum = isset($_SESSION['coNum']) ? ($_SESSION['coNum'] % 4) + 1 : 1;
// $_SESSION['coNum'] = $coNum;
$coNum =random_int(1, 4);
$coNum2=$coNum;
// Insert data into question_t
$difficultyLevel=random_int(1, 5);
$questionNum = random_int(1, 4);
$answerNum = $questionNum ; 
$markPerQuestion = 100;
$examID=random_int(10, 16);
$examID2=$examID;
$sql = "INSERT INTO question_t (markPerQuestion,questionNum,difficultyLevel,examID,courseID ,coNum) VALUES ('$markPerQuestion','$questionNum', '$difficultyLevel','$examID','$courseID','$coNum')";

if ($con->query($sql) === TRUE) {
    echo "New question added successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

// Get the questionID for the inserted question
$questionID = $con->insert_id;


// Select a random programID from program_t table
$sql = "SELECT programID FROM program_t WHERE programID BETWEEN 9 AND 13 ORDER BY RAND() LIMIT 1";
$result = $con->query($sql);
$row = $result->fetch_assoc();
$programID = $row['programID'];

// Determine the common value for ploNum and poNum
// $ploNum = mt_rand(1, 6);
 $ploNum = random_int(1, 6);
$poNum = $ploNum;
// Insert a new row into the plo_t table with the obtained programID
$sql = "INSERT INTO plo_t (ploNum,programID) VALUES ('$ploNum','$programID')";
if ($con->query($sql) === TRUE) {
    $ploID = $con->insert_id;
    echo "New ploID added to plo_t table successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

// Insert a new row into the po_t table with the obtained programID
$sql = "INSERT INTO po_t (poNum,programID) VALUES ('$ploNum','$programID')";
if ($con->query($sql) === TRUE) {
    $poID = $con->insert_id;
    echo "New poID added to po_t table successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}


// Insert coNum into co_t table
$sql = "INSERT INTO co_t (coNum, courseID, ploID, poID) VALUES ('$coNum2', ' $courseID' ,'$ploID', '$poID')";

if ($con->query($sql) === TRUE) {
    echo "New coNum added to co_t table successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

// ...inserting backlog data


$sql = "INSERT INTO backlog_data (studentID, sectionNum, semester, courseID, year, obtainGrade, f_employeeID, timeStamp) 
        VALUES ('$studentID', '$sectionNum', '$semester', '$courseID', '$year', '$obtainGrade', '$facultyID', CURRENT_TIMESTAMP)";


if (mysqli_query($con, $sql)) {
    echo "Backlog Data inserted successfully";
} else {
    echo "Error inserting data: " . mysqli_error($con);
}





// Insert data into answer_t

$sql = "INSERT INTO answer_t (answerNum,markObtained, registrationID, questionID,examID) VALUES ('$answerNum','$markObtained', '$registrationID', '$questionID','$examID')";

if ($con->query($sql) === TRUE) {
    echo "New answer added successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}




        // Fetch the data to display in the HTML table
        $sql = "SELECT r.studentID, s.sectionNum, s.semester, s.courseID, s.year, scp.obtainGrade
        FROM student_course_performance_t scp
        JOIN registration_t r ON scp.registrationID = r.registrationID
        JOIN section_t s ON r.sectionID = s.sectionID
        WHERE r.studentID='$studentID'";

        $result = $con->query($sql);

       
    }
}

$con->close();



?>


<!DOCTYPE html>
 
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
        
    
        <div class="col-xs-12">
            

                <form action="menualform.php" method="post">
                <div class="form-group">
                    <label for="input1"><h4>studentID:</h4></label>
                    <input type="text" class="form-control" id="input1" placeholder="Enter studentID" name="studentID" >
                </div>
                <div class="form-group">
                    <label for="input2"><h4>enrolledSection:</h4></label>
                    <input type="text" class="form-control" id="input2" placeholder="Enter sectionNum" name="sectionNum">
                </div>
                <div class="form-group">
                    <label for="input3"><h4>enrolledSemester:</h4></label>
                    <input type="text" class="form-control" id="input3" placeholder="Enter semester" name="semester">
                </div>
                <div class="form-group">
                    <label for="input4"><h4>courseID:</h4></label>
                    <input type="text" class="form-control" id="input4" placeholder="Enter courseID" name="courseID">
                </div>
                <div class="form-group">
                    <label for="input5"><h4>educationalYear:</h4></label>
                    <input type="text" class="form-control" id="input5" placeholder="Enter year" name="year">
                </div>
                <div class="form-group">
                    <label for="input6"><h4>obtainGrade:</h4></label>
                    <input type="text" class="form-control" id="input6" placeholder="Enter obtainGrade" name="obtainGrade">
                </div>
                <button type="submit" class="btn btn-primary" value="submit2">Submit</button>
                </form>




                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Section Number</th>
                                    <th>Semester</th>
                                    <th>Course ID</th>
                                    <th>Year</th>
                                    <th>Obtain Grade</th>
                                    <th>Co</th>
                                    <th>Co1</th>
                                    <th>Co2</th>
                                    <th>Co3</th>
                                    <th>Co4</th>
                                
                                </tr>
                            </thead>
                        


                            <?php 
                            if($result !== false){
                                if($result->num_rows > 0){

                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                            <td>" . $row['studentID'] . "</td>
                                            <td>" . $row['sectionNum'] . "</td>
                                            <td>" . $row['semester'] . "</td>
                                            <td>" . $row['courseID'] . "</td>
                                            <td>" . $row['year'] . "</td>
                                            <td>" . $row['obtainGrade'] . "</td>
                                            <td>" . $grade_mapping[$row['obtainGrade']] . "%</td>
                                            <td>" . $grade_mapping[$row['obtainGrade']] . "%</td>
                                            <td>" . $grade_mapping[$row['obtainGrade']] . "%</td>
                                            <td>" . $grade_mapping[$row['obtainGrade']] . "%</td>
                                            <td>" . $grade_mapping[$row['obtainGrade']] . "%</td>
                                            
                                           
                                        </tr>";
                                    }
                                }
                            }

                                
                            ?>

                            
                            
                        </table>
                    </div>
  
            </div>





   
        
            
            
        </div>
       
    </div>
        
         

    



  


<!-- Overall plo -->
<script>
   
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


