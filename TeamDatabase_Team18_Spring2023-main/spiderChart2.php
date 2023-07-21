
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
// if (isset($_POST['submit'])) {
//     $studentID = $_POST['studentID'];
// } elseif (isset($_SESSION['ID'])) {
//     $studentID = $_SESSION['ID'];
// }
  
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
    <!-- boo
    oos -->

    <!--Google Font-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">

     <!----======== CSS ======== -->
     <link rel="stylesheet" href="styleplo.css">
    <!-- <link rel="stylesheet" href="style3.css"> -->
    
    
    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <!-- ja chrt -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        <!-- <div class="main-body">
      <h2>Dashboard</h2>
      <div class="promo_card">
        <h1> </h1> -->
     
      
      </div>
    </section>



    <div class="background">


<!-- <div style="display:flex;justify-content:space-around" class="row2"> -->
<div style="display:flex;justify-content:center;margin-bottom:10px;">

      <button onclick="poView()" style="width:200px;" class="viewButton">view PO analysis</button>
      <button onclick="coView()" style="width:200px;" class="viewButton">View CO analysis</button>

</div>

<!-- <div style="display:flex;justify-content:center;" class="row3" style="margin-top:20px;"> 
 <div id="Autumn" style="width: 65%; height: 500px; display:inline-block;margin-top:23px;"></div> -->
 <div id="chart-container" style="display:flex;justify-content:center;margin-top:5px;height:500px;width:100%;" class="row3">
      <canvas style="background-color:white;height:500px;width:400px;" id="myChart"></canvas>
    </div> 
    <!-- <div id="Autumn" style="width: 65%; height: 500px; display:inline-block;margin-top:23px;"></div> -->
<!-- div row-3 ends here -->
 
</div>
</div>  


<?php
  if (isset($_POST['submit'])) {
    $studentID = $_POST['studentID'];
  } elseif (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
  }
  ?>



 
        
<script>
    function poView() {
      <?php
      $sql = "SELECT po.poNum AS poNum, 
   AVG((ans.markObtained/q.markPerQuestion)*100) AS percent
   FROM registration_t AS r, answer_t AS ans, question_t AS q, 
   co_t AS co, po_t AS po
   WHERE r.registrationID=ans.registrationID 
   AND ans.examID=q.examID
   AND ans.answerNum=q.questionNum AND q.coNum=co.coNum 
   AND q.courseID=co.courseID AND co.poID=po.poID 
   AND r.studentID='$studentID'
   GROUP BY po.poNum";

      $result = mysqli_query($con, $sql);

      $po = array();
      $percent = array();

      while ($data = mysqli_fetch_array($result)) {

        array_push($po, "PO " . $data['poNum']);
        array_push($percent, $data['percent']);
      }

      ?>


      var po = <?php echo json_encode($po); ?>;
      var percent = <?php echo json_encode($percent); ?>;

      for (var i = 0; i < percent.length; i++) {
        percent[i] = parseFloat(percent[i]);
      }

      document.getElementById("chart-container").innerHTML='';
      document.getElementById("chart-container").innerHTML='<canvas style="background-color:white;height:500px;width:400px;" id="myChart"></canvas>';

      const ctx = document.getElementById('myChart');

      new Chart(ctx, {
        type: 'radar',
        data: {
          labels: po,
          datasets: [{
            label: 'PO Achieved',
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

    function coView() {
      <?php
      $sql = "SELECT q.coNum, 
   AVG((ans.markObtained/q.markPerQuestion)*100) AS percent
   FROM registration_t AS r, answer_t AS ans, question_t AS q, 
   co_t AS co, po_t AS po
   WHERE r.registrationID=ans.registrationID 
   AND ans.examID=q.examID
   AND ans.answerNum=q.questionNum AND q.coNum=co.coNum
   AND r.studentID='$studentID'
   GROUP BY q.coNum";

      $result = mysqli_query($con, $sql);

      $co = array();
      $percent = array();

      while ($data = mysqli_fetch_array($result)) {

        array_push($co, "CO " . $data['coNum']);
        array_push($percent, $data['percent']);
      }

      ?>


      var co = <?php echo json_encode($co); ?>;
      var percent = <?php echo json_encode($percent); ?>;

      for (var i = 0; i < percent.length; i++) {
        percent[i] = parseFloat(percent[i]);
      }
      document.getElementById("chart-container").innerHTML='';
      document.getElementById("chart-container").innerHTML='<canvas style="background-color:white;height:500px;width:400px;" id="myChart"></canvas>';
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

         

  


</body>

</html>