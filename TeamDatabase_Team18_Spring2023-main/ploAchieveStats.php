
     
<?php

require_once(__DIR__."/testfunction.php");
require_once(__DIR__."/connect.php");
require_once(__DIR__."/user_header.php");
$_SESSION['dashboard_referrer'] = $_SERVER['PHP_SELF'];
echo $_SESSION['dashboard_referrer'];





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
    
    <!----======== CSS ======== -->
    <link rel="stylesheet" href="styleplo.css">
    <!-- <link rel="stylesheet" href="style3.css"> -->
    
    
    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    
    <!--<title>Dashboard Sidebar Menu</title>--> 
</head>
<body>
    <nav class="sidebar close">
        <!-- <input type="checkbox" id="nav-check"> -->

        <header>
            <div class="image-text">
                <span class="image">
                    <!--<img src="logo.png" alt="">-->
                </span>

                <div class="text logo-text">
                    <span class="name">Student</span>
                    <span class="name">Dashboard</span>
                    <!-- <span class="profession">Web developer</span> -->
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <!-- <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Search...">
                </li> -->

                <ul class="menu-links">
                <li class="nav-link">
                    <?php
                        $dashboardLink = isset($_SESSION['dashboard_referrer']) && strrpos($_SESSION['dashboard_referrer'], 'estudent_dashboard.php') !== false ? 'estudent_dashboard.php' : 'employee_dashboard.php';

                        
                        
                        
                        // isset($_SESSION['dashboard_referrer']) ? (strpos($_SESSION['dashboard_referrer'], 'estudent_dashboard.php') == true ? 'estudent_dashboard.php' : 'employee_dashboard.php') : 'ploAchieveStats.php';
                       
                        echo '<a href="' . $dashboardLink . '">';
                    ?>
                    <!-- <a href="employee_dashboard.php" target="_self"> -->
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="ploComparisonStudent.php" target="_self">
                            <i class='bx bx-bar-chart-alt-2 icon' ></i>
                            <span class="text nav-text">PLO Comparison(Student)</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="ploComparisonCourse.php" target="_self">
                            <i class='bx bx-bar-chart-alt-2 icon' ></i>
                            <span class="text nav-text">PLO Comparison(Course)</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="ploAnalysisDepartmentProgramSchoolAverage.php" target="_self">
                            <i class='bx bx-bar-chart-alt-2 icon' ></i>
                            <span class="text nav-text">PLO Comparison(Student)</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="ploComparisonProgram.php" target="_self">
                            <i class='bx bx-bar-chart-alt-2 icon' ></i>
                            <span class="text nav-text">PLO Comparison(Program)</span>
                        </a>
                    </li>
                    <li>
                        <a href="ploComparisonSchool.php" target="_self">
                            <i class='bx bx-bar-chart-alt-2 icon' ></i>
                            <span class="text nav-text">PLO Comparison(School)</span>
                        </a>
                    </li>
                    <li>
                        <a href="ploComparisonDepartment.php" target="_self">
                            <i class='bx bx-bar-chart-alt-2 icon' ></i>
                            <span class="text nav-text">PLO Comparison(Department)</span>
                        </a>
                    </li>
                    

                </ul>
            
          
         
          





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
        
      <button>click to view</button>
      </div>
    </section>

   

    <script>
        const body = document.querySelector('body'),
      sidebar = body.querySelector('nav'),
      toggle = body.querySelector(".toggle"),
      searchBtn = body.querySelector(".search-box"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");


toggle.addEventListener("click" , () =>{
    sidebar.classList.toggle("close");
})

searchBtn.addEventListener("click" , () =>{
    sidebar.classList.remove("close");
})

modeSwitch.addEventListener("click" , () =>{
    body.classList.toggle("dark");
    
    if(body.classList.contains("dark")){
        modeText.innerText = "Dark mode";
    }else{
        modeText.innerText = "Light mode";
        
    }
});
    </script>

</body>
</html>