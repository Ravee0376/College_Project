
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


<div style="display:flex;justify-content:space-around" class="row2">
  <button onclick="ploAnalysisWithDepartmentAverage()" style="width:300px;margin-left:0px;" class="School-wise">PLO Analysis with Department Average</button>
  <button  onclick="ploAnalysisWithProgramAverage()" style="width:300px;" class="Department-wise">PLO Analysis with Program Average</button>
  <button onclick="ploAnalysisWithSchoolAverage()" style="width:300px;" class="Program-wise">PLO Analysis with School Average</button>
</div>

<div style="display:flex;justify-content:center;" class="row3" style="margin-top:20px;"> 
 <div id="Autumn" style="width: 65%; height: 500px; display:inline-block;margin-top:23px;"></div>
 
</div>
</div>  


 
        
    
         

  

<?php
if (isset($_SESSION['ID'])) {
   $studentID = $_SESSION['ID'];
 }
 
?>

<!-- Analysis with Department Average -->
<script>
    function ploAnalysisWithDepartmentAverage(){
    <?php

    $sql="SELECT plo.ploNum AS ploNum, 
    AVG((ans.markObtained/q.markPerQuestion)*100) AS percent
    FROM registration_t AS r, answer_t AS ans, question_t AS q, 
    co_t AS co, plo_t AS plo
    WHERE r.registrationID=ans.registrationID 
    AND ans.examID=q.examID AND ans.answerNum=q.questionNum AND q.coNum=co.coNum 
    AND q.courseID=co.courseID AND co.ploID=plo.ploID 
    AND r.studentID='$studentID'
    GROUP BY plo.ploNum,r.studentID";

    $result=mysqli_query($con,$sql);

    // $sql2="SELECT plo.ploNum AS ploNum, AVG((ans.markObtained/q.markPerQuestion)*100) 
    // AS percent
    // FROM registration_t AS r, answer_t AS ans, question_t AS q, 
    // co_t AS co, plo_t AS plo, student_t AS s WHERE r.studentID=s.studentID 
    // AND r.registrationID=ans.registrationID AND ans.examID=q.examID
    // AND ans.answerNum=q.questionNum 
    // AND q.coNum=co.coNum AND q.courseID=co.courseID AND co.ploID=plo.ploID
    // AND s.departmentID=(SELECT s.departmentID FROM student_t AS s 
    // WHERE s.studentID='$studentID')
    // GROUP BY plo.ploNum";

    $sql2 = "
SELECT plo.ploNum AS ploNum, AVG((ans.markObtained / q.markPerQuestion) * 100) AS percent
FROM registration_t AS r
JOIN student_t AS s ON r.studentID = s.studentID
JOIN answer_t AS ans ON r.registrationID = ans.registrationID
JOIN question_t AS q ON ans.examID = q.examID AND ans.answerNum = q.questionNum
JOIN co_t AS co ON q.coNum = co.coNum AND q.courseID = co.courseID
JOIN plo_t AS plo ON co.ploID = plo.ploID
WHERE s.departmentID = (
  SELECT s.departmentID
  FROM student_t AS s
  WHERE s.studentID = '$studentID'
)
GROUP BY plo.ploNum
";

    $result2=mysqli_query($con,$sql2);

    ?>



google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(drawAutumnChart);

function drawAutumnChart() {
  var data = google.visualization.arrayToDataTable([
    ["ploNum", "Individual", "Dept Average"],

    <?php
      while ($data = mysqli_fetch_array($result)) {
        $data2 = mysqli_fetch_array($result2);
        $ploNum = "PLO" . $data["ploNum"];
        $percent = $data["percent"];
        $percent2 = $data2["percent"];
    ?>

    ["<?php echo $ploNum; ?>", <?php echo $percent; ?>, <?php echo $percent2; ?>],

    <?php
      }
    ?>
  ]);

  var options = {
    title: "PLO Analysis with Department Average",
    legend: { position: "bottom" },
    series: {
      0: { color: "#7f8c8d" }, // Gray
      1: { color: "#2c3e50" }, // Dark blue-gray
    },
    vAxis: {
      minValue: 0,
      format: "0",
      textStyle: {
        fontSize: 12,
      },
      title: "Percentage",
    },
    hAxis: {
      textStyle: {
        fontSize: 12,
      },
      title: "PLO Number",
    },
  };

  var chart = new google.visualization.ColumnChart(document.getElementById("Autumn"));
  chart.draw(data, options);
}

   
  //   google.charts.load('current', {'packages':['bar']});
  //   google.charts.setOnLoadCallback(drawAutumnChart);

  //     function drawAutumnChart() {
  //       var data = google.visualization.arrayToDataTable([
  //         ['ploNum','Individual','Dept Average'],
          
  //         <?php
  //           while($data=mysqli_fetch_array($result)){
  //             $data2=mysqli_fetch_array($result2);
  //             $ploNum="PLO".$data['ploNum'];
  //             $percent=$data['percent'];
  //             $percent2=$data2['percent'];
  //          ?>

  //          ['<?php echo $ploNum;?>',<?php echo $percent;?>,<?php echo $percent2;?>],   
  //          <?php   
  //           }
  //          ?> 
  //       ]);

  //       var options = {
  //         chart: {
  //           title: 'PLO Analysis with Department Average',
  //         },
  //         bars: 'vertical' // Required for Material Bar Charts.
  //       };

  //       var chart = new google.charts.Bar(document.getElementById('Autumn'));
  //       chart.draw(data, google.charts.Bar.convertOptions(options));
  //     }




  //     google.charts.load("current", { packages: ["corechart"] });
  // google.charts.setOnLoadCallback(drawAutumnChart);

  // function drawAutumnChart() {
  //   var data = google.visualization.arrayToDataTable([
  //     ["ploNum", "Individual", "Dept Average"],

  //     <?php
  //       while ($data = mysqli_fetch_array($result)) {
  //         $data2 = mysqli_fetch_array($result2);
  //         $ploNum = "PLO" . $data["ploNum"];
  //         $percent = $data["percent"];
  //         $percent2 = $data2["percent"];
  //     ?>

  //     ["<?php echo $ploNum; ?>", <?php echo $percent; ?>, <?php echo $percent2; ?>],

  //     <?php
  //       }
  //     ?>
  //   ]);

  //   var options = {
  //     title: "PLO Analysis with Department Average",
  //     curveType: "function",
  //     lineWidth: 4,
  //     pointSize: 6,
  //     series: {
  //       0: { color: "#e2431e" },
  //       1: { color: "#f1ca3a" },
  //     },
  //     legend: { position: "bottom" },
  //   };

  //   var chart = new google.visualization.LineChart(document.getElementById("Autumn"));
  //   chart.draw(data, options);
  // }
    
}
</script> 


<!-- Analysis with Program Average -->
<script>
function ploAnalysisWithProgramAverage(){
    <?php

   

    $sql = "
SELECT plo.ploNum AS ploNum, AVG((ans.markObtained / q.markPerQuestion) * 100) AS percent
FROM registration_t AS r
JOIN answer_t AS ans ON r.registrationID = ans.registrationID
JOIN question_t AS q ON ans.examID = q.examID AND ans.answerNum = q.questionNum
JOIN co_t AS co ON q.coNum = co.coNum AND q.courseID = co.courseID
JOIN plo_t AS plo ON co.ploID = plo.ploID
WHERE r.studentID = '$studentID'
GROUP BY plo.ploNum, r.studentID
";

    $result=mysqli_query($con,$sql);

    

    // Fetch data for the program average
$sql2 = "
SELECT plo.ploNum AS ploNum, AVG((ans.markObtained / q.markPerQuestion) * 100) AS percent
FROM registration_t AS r
JOIN student_t AS s ON r.studentID = s.studentID
JOIN program_t AS p ON s.programID = p.programID
JOIN answer_t AS ans ON r.registrationID = ans.registrationID
JOIN question_t AS q ON ans.examID = q.examID AND ans.answerNum = q.questionNum
JOIN co_t AS co ON q.coNum = co.coNum AND q.courseID = co.courseID
JOIN plo_t AS plo ON co.ploID = plo.ploID
WHERE s.programID = (
  SELECT s.programID
  FROM student_t AS s
  WHERE s.studentID = '$studentID'
)
GROUP BY plo.ploNum, r.studentID
";


    $result2=mysqli_query($con,$sql2);

    ?>
    google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(drawAutumnChart);

function drawAutumnChart() {
  var data = google.visualization.arrayToDataTable([
    ["ploNum", "Individual", "Program Average"],

    <?php
      while ($data = mysqli_fetch_array($result)) {
        $data2 = mysqli_fetch_array($result2);
        $ploNum = "PLO" . $data["ploNum"];
        $percent = $data["percent"];
        $percent2 = $data2["percent"];
    ?>

    ["<?php echo $ploNum; ?>", <?php echo $percent; ?>, <?php echo $percent2; ?>],

    <?php
      }
    ?>
  ]);

  var options = {
    title: "PLO Analysis with Program Average",
    legend: { position: "bottom" },
    backgroundColor: "#f9f9f9",
    chartArea: {
      backgroundColor: "#f9f9f9",
    },
    series: {
      0: { color: "#BCC6CC" }, // Bright pink
      1: { color: "#4C787E" }, // Lime green
    },
    vAxis: {
      minValue: 0,
      format: "0",
      textStyle: {
        fontSize: 12,
      },
      title: "Percentage",
      gridlines: {
        color: "#e5e5e5", // Light gray gridlines
      },
    },
    hAxis: {
      textStyle: {
        fontSize: 12,
      },
      title: "PLO Number",
      gridlines: {
        color: "#e5e5e5", // Light gray gridlines
      },
    },
    animation: {
      startup: true,
      duration: 1000,
      easing: "inAndOut",
    },
  };

  var chart = new google.visualization.ColumnChart(document.getElementById("Autumn"));
  chart.draw(data, options);
}

    


 
    }
</script>

<!-- Analysis with School Average -->
<script>
function ploAnalysisWithSchoolAverage(){

    <?php


$sql = "
SELECT plo.ploNum AS ploNum, AVG((ans.markObtained / q.markPerQuestion) * 100) AS percent
FROM registration_t AS r
JOIN answer_t AS ans ON r.registrationID = ans.registrationID
JOIN question_t AS q ON ans.examID = q.examID AND ans.answerNum = q.questionNum
JOIN co_t AS co ON q.coNum = co.coNum AND q.courseID = co.courseID
JOIN plo_t AS plo ON co.ploID = plo.ploID
WHERE r.studentID = '$studentID'
GROUP BY plo.ploNum, r.studentID
";

$result=mysqli_query($con,$sql);




$sql2 = "
SELECT plo.ploNum AS ploNum, AVG((ans.markObtained / q.markPerQuestion) * 100) AS percent
FROM registration_t AS r
JOIN student_t AS s ON r.studentID = s.studentID
JOIN department_t AS d ON s.departmentID = d.departmentID
JOIN answer_t AS ans ON r.registrationID = ans.registrationID
JOIN question_t AS q ON ans.examID = q.examID AND ans.answerNum = q.questionNum
JOIN co_t AS co ON q.coNum = co.coNum AND q.courseID = co.courseID
JOIN plo_t AS plo ON co.ploID = plo.ploID
WHERE d.schoolID = (
  SELECT d.schoolID
  FROM student_t AS s
  JOIN department_t AS d ON s.departmentID = d.departmentID
  WHERE s.studentID = '$studentID'
)
GROUP BY plo.ploNum, r.studentID
";

$result2=mysqli_query($con,$sql2);

?>
google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(drawAutumnChart);

function drawAutumnChart() {
  var data = google.visualization.arrayToDataTable([
    ["ploNum", "Individual", "School Average"],

    <?php
      while ($data = mysqli_fetch_array($result)) {
        $data2 = mysqli_fetch_array($result2);
        $ploNum = "PLO" . $data["ploNum"];
        $percent = $data["percent"];
        $percent2 = $data2["percent"];
    ?>

    ["<?php echo $ploNum; ?>", <?php echo $percent; ?>, <?php echo $percent2; ?>],

    <?php
      }
    ?>
  ]);

  var options = {
    title: "PLO Analysis with School Average",
    legend: { position: "bottom" },
    series: {
      0: { color: "#BCC6CC" }, // Bright pink
      1: { color: "#4C787E" }, // Lime green
    },
    vAxis: {
      minValue: 0,
      format: "0",
      textStyle: {
        fontSize: 12,
      },
      title: "Percentage",
    },
    hAxis: {
      textStyle: {
        fontSize: 12,
      },
      title: "PLO Number",
    },
  };

  var chart = new google.visualization.ColumnChart(document.getElementById("Autumn"));
  chart.draw(data, options);
}







    }
</script>

</body>

</html>