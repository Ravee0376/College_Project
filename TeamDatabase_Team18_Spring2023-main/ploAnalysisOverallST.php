
<?php
require_once(__DIR__."/testfunction.php");
require_once(__DIR__."/connect.php");
require_once(__DIR__."/user_header.php");
//echo pre($_SESSION);

if (isset($_POST['submit'])) {
    $studentID = $_POST['studentID'];
} elseif (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
}

// Fetch student data

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="path/to/your/script.js"></script>
    

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js">
      
    </script>  
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
                <a href="coanalysisST.php" target="_self">
                    <i class='bx bx-bar-chart-alt-2 icon'></i>
                    <span class="text nav-text">Co analysis</span>
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

<div class="background">

      <div style="height:80px;"class="row1">
        <form method="POST">
      
        </form>
        </div>

      <div style="display:flex;justify-content:space-around" class="row2">
        <button onclick="overallPlo()" style="width:300px;margin-left:0px;" class="School-wise">Overall PLO</button>
        <button onclick="coWisePlo()" style="width:300px;" class="Department-wise">Course Wise Co</button>
      </div>
      
    
     <div style="display:flex;justify-content:center;" class="row3" style="margin-top:20px;"> 
     
       <div id="Autumn" style="width: 65%; height: 500px; display:inline-block;margin-top:23px;"></div>
       <div  id="chart-container"></div>
       

       
     </div>
    
</div>    


<!-- Overall plo -->
<script>
    function overallPlo(){
    <?php

    $sql="SELECT plo.ploNum AS ploNum, 
    AVG((ans.markObtained/q.markPerQuestion)*100) AS percent
    FROM registration_t AS r, answer_t AS ans, question_t AS q, 
    co_t AS co, plo_t AS plo
    WHERE r.registrationID=ans.registrationID 
    AND ans.examID=q.examID
    AND ans.answerNum=q.questionNum AND q.coNum=co.coNum 
    AND q.courseID=co.courseID AND co.ploID=plo.ploID 
    AND r.studentID='$studentID'
    GROUP BY plo.ploNum,r.studentID";

    $result=mysqli_query($con,$sql);
    ?>
    
    google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(drawAutumnChart);

function drawAutumnChart() {
  var data = google.visualization.arrayToDataTable([
    ["ploNum", "PLO Percentage"],

    <?php
      while ($data = mysqli_fetch_array($result)) {
        $ploNum = "PLO" . $data["ploNum"];
        $percent = $data["percent"];
    ?>

    ["<?php echo $ploNum; ?>", <?php echo $percent; ?>],

    <?php
      }
    ?>
  ]);

  var options = {
    title: "Overall PLO Analysis",
    legend: { position: "bottom" },
    series: {
      0: { color: "#C9C0BB" }, // Blue
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


<!-- Co wise plo -->
<script>
function coWisePlo() {
  <?php
  $sqlCOs = "SELECT DISTINCT coNum FROM co_t ORDER BY coNum";
  $resultCOs = mysqli_query($con, $sqlCOs);

  $coNumbers = array();
  while ($data = mysqli_fetch_array($resultCOs)) {
    array_push($coNumbers, $data['coNum']);
  }

  $sql = "SELECT co.coNum AS coNum,
    AVG((ans.markObtained/q.markPerQuestion)*100) AS percent
    FROM registration_t AS r
    JOIN student_course_performance_t AS scp ON r.registrationID = scp.registrationID
    JOIN section_t AS s ON r.sectionID = s.sectionID
    JOIN answer_t AS ans ON r.registrationID = ans.registrationID
    JOIN question_t AS q ON ans.questionID = q.questionID
    JOIN co_t AS co ON q.coNum = co.coNum
    WHERE r.studentID = '$studentID'
    GROUP BY co.coNum";

  $result = mysqli_query($con, $sql);

  $coData = array();

  while ($data = mysqli_fetch_array($result)) {
    $coData[$data['coNum']] = $data['percent'];
  }
  ?>

  var coNumbers = <?php echo json_encode($coNumbers); ?>;
  var coData = <?php echo json_encode($coData); ?>;

  var co = [];
  var percent = [];

  for (var i = 0; i < coNumbers.length; i++) {
    co.push("CO" + coNumbers[i]);
    percent.push(parseFloat(coData[coNumbers[i]]) || 0);
  }

  document.getElementById("chart-container").innerHTML = '';
  document.getElementById("chart-container").innerHTML = '<canvas style="background-color:white;height:500px;width:400px;" id="myChart"></canvas>';

  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'radar',
    data: {
      labels: co,
      datasets: [{
        label: 'CO Achieved',
        data: percent,
        fill: true,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgb(54, 162, 235)',
        pointBackgroundColor: 'rgb(54, 162, 235)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgb(54, 162, 235)'
      }]
    },
    options: {
      elements: {
        line: {
          borderWidth: 3
        }
      }
    }
  });

}


</script>

<!-- course wise plo -->

<script>
function courseWisePlo(){


    }
</script>

</body>

</html>