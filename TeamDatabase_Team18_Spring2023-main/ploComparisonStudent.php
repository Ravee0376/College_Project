








  
<?php
require_once(__DIR__."/testfunction.php");
require_once(__DIR__."/connect.php");
require_once(__DIR__."/user_header.php");

if (!empty($_SERVER['HTTP_REFERER'])) {
    $_SESSION['dashboard_referrer'] = $_SERVER['HTTP_REFERER'];
}

// Set the dashboard link based on the previous page URL
if (isset($_SESSION['dashboard_referrer']) && strpos($_SESSION['dashboard_referrer'], 'estudent_dashboard.php') !== false) {
  $dashboardLink = 'estudent_dashboard.php';
} else {
  $dashboardLink = 'employee_dashboard.php';
}

echo "dashboardLink = " . $dashboardLink; 

// current page
$dashboardLink = isset($_SESSION['dashboard_referrer']) && strpos($_SESSION['dashboard_referrer'], 'estudent_dashboard.php') !== false ? 'estudent_dashboard.php' : 'employee_dashboard.php';
echo '<a href="' . $dashboardLink . '">';
// Set the dashboard link based on the previous page URL
// if (isset($_SESSION['dashboard_referrer']) && strpos($_SESSION['dashboard_referrer'], 'estudent_dashboard.php') !== false) {
//   $dashboardLink = 'estudent_dashboard.php';
// } else {
//   $dashboardLink = 'employee_dashboard.php';
// }

//echo pre($_SESSION);
if (isset($_POST['submit'])) {
    $studentID = $_POST['studentID'];
} elseif (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
}
  
?>

<!DOCTYPE html>
  <!-- Coding by CodingLab | www.codinglabweb.com -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->

    <title>Employee Dashboard</title>
    <!--Google Font-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
     <!----======== CSS ======== -->
     <link rel="stylesheet" href="styleplo.css">
    <!-- <link rel="stylesheet" href="style3.css"> -->
    
    
    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
    <script type="text/javascript"></script>  


    <style>
        body{
            background-color:#155977;
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
                        <a href="ploAnalysisDepartmentProgramSchoolAverageST.php" target="_self">
                            <i class='bx bx-bar-chart-alt-2 icon' ></i>
                            <span class="text nav-text">PLO Analysis With Department/Program/School Average</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="ploAnalysisOverallST.php" target="_self">
                            <i class='bx bx-bell icon'></i>
                            <span class="text nav-text">PLO Analysis (Overall, CO Wise, Course Wise)</span>
                        </a>
                       
                    </li>
                    <li class="nav-link">
                        <a href="importStudentdata.php" target="_self">
                            <i class='bx bx-bell icon'></i>
                            <span class="text nav-text">Upload CSV file </span>
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





    <section class="home">
        <div class="text">Dashboard Sidebar</div>

        <div class="main-body">
      <h2>Dashboard</h2>
      <div class="promo_card">
        <h1> </h1>
     
      
      </div>
    </section>


    <div class="background">

<div style="display:flex;justify-content:center;" class="row1">
<form method="POST">

<input style="background-color:#6698FF;height:36px;border: 1px solid;cursor: pointer;border-radius: 5px;
           font-size: 14px;letter-spacing:2px;font-weight: bold;text-transform: uppercase;border: none;
           outline: none;text-align: center;color:white;" type="text" placeholder="Enter Student ID" name="studentID"/>

<select style="margin-left:10px;" name="year" class="select">
  <option disabled selected>Year</option>
  <option value="2020">2020</option>
  <option value="2021">2021</option>
  <option value="2022">2022</option>
</select>
  <input style="background:#00BFFF;border-radius:10px;border:none;outline:none;color:#fff;font-size:14px;letter-spacing:2px;
         text-transform:uppercase;cursor:pointer;font-weight:bold;margin-left:10px;height: 36px;width: 100px;"
         type="submit" name="submit" value="Submit"/>
</form>       
</div>  <!-- div row-1 ends here -->

  
   <!-- div row-2 -->
   <div style="height:50px;padding-left:43%;margin-top:15px;">
   <button onclick="view()" style="height: 46px;width: 100px;margin-left:40px;display:inline-block;border-radius: 10px;
   border: none;outline: none;background:#00BFFF;color: #fff;font-size: 14px;letter-spacing:2px;
   text-transform: uppercase;cursor:pointer;font-weight: bold;">view</button>
   </div> <!-- div row-2 ends here -->

   <div style="display:flex;justify-content:center;"class="row3" style="margin-top:5px;"> 
   <div id="curve_chart" style="width: 900px; height: 500px"></div>
   </div> <!-- div row-3 ends here -->

</div>  <!-- background div ends here -->


<?php
if(isset($_POST['submit'])){
$year=$_POST['year'];
$studentID=$_POST['studentID'];
}?>

<script>
function view(){

<?php

$sql="SELECT sec.semester AS semester, 
AVG((ans.markObtained/q.markPerQuestion)*100) AS percent
FROM section_t AS sec, plo_t AS plo, answer_t AS ans, question_t AS q, 
registration_t AS r, co_t AS co
WHERE r.sectionID=sec.sectionID AND r.registrationID=ans.registrationID 
AND ans.examID=q.examID
AND ans.answerNum=q.questionNum AND q.coNum=co.coNum 
AND q.courseID=co.courseID AND co.ploID=plo.ploID 
AND r.studentID='$studentID' AND sec.year='$year'
GROUP BY semester";

$result=mysqli_query($con,$sql);
?>

google.charts.load('current', {'packages':['corechart']});
 google.charts.setOnLoadCallback(drawChart);

 function drawChart() {
   var data = google.visualization.arrayToDataTable([
     ['Semester', 'PLO'],
     <?php
       while($data=mysqli_fetch_array($result)){
         $semester=$data['semester'];
         $PLO=$data['PLO'];
      ?>
      ['<?php echo $semester;?>',<?php echo $PLO;?>],   
      <?php   
       }
      ?> 
   ]);

   var options = {
     title: 'Company Performance',
     curveType: 'function',
     legend: { position: 'bottom' }
   };

   var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

   chart.draw(data, options);
 }


}
</script>

 
        
    
         

  

<?php
if (isset($_SESSION['ID'])) {
   $studentID = $_SESSION['ID'];
 }
 
?>




</body>

</html>
  