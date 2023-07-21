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


// Check if user is logged in and get the ID of the student
if(isset($_SESSION['ID']) && isset($_SESSION['userType']) && $_SESSION['userType'] == 'student'){
    $studentID = $_SESSION['ID'];
}
else{
    header('location:login.php');
}


        // Fetch the data to display in the HTML table
        $sql = "SELECT r.studentID, s.sectionNum, s.semester, s.courseID, s.year, scp.obtainGrade
        FROM student_course_performance_t scp
        JOIN registration_t r ON scp.registrationID = r.registrationID
        JOIN section_t s ON r.sectionID = s.sectionID
        WHERE r.studentID='$studentID'";

        $result = $con->query($sql);

       
    


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


