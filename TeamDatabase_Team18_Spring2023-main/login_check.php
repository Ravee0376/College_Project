<?php
session_start();

$invalid = 0;

if (isset($_SESSION['ID']) && isset($_SESSION['userType'])) {
    // User is already logged in
    $ID = $_SESSION['ID'];
    $userType = $_SESSION['userType'];
} else {
    // User is not logged in
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $HOSTNAME = 'localhost';
        $USERNAME = 'root';
        $PASSWORD = '';
        $DATABASE = 'spms';

        $con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

        if (!$con) {
            die(mysqli_error($con));
        }

        $userType = $_POST['userType'];
        $ID = $_POST['ID'];
        $password = $_POST['password'];

        if ($userType == 'faculty') {
            $sql = "SELECT * from employee_t where employeeID='$ID' and password='$password'";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $num = mysqli_num_rows($result);
                if ($num > 0) {
                    $invalid = 0;
                    $_SESSION['ID'] = $ID;
                    $_SESSION['userType'] = 'faculty';
                } else {
                    $invalid = 1;
                }
            }
        } elseif ($userType == 'student') {
            $sql = "SELECT * from student_t where studentID='$ID' and password='$password'";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $num = mysqli_num_rows($result);
                if ($num > 0) {
                    $invalid = 0;
                    $_SESSION['ID'] = $ID;
                    $_SESSION['userType'] = 'student';
                } else {
                    $invalid = 1;
                }
            }
        } else {
            $invalid = 1;
        }
    } else {
        // Redirect to login page if no POST request made
        header('location: login.php');
    }
}

if ($invalid == 1) {
    // Invalid credentials
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong></strong> Invalid credentials!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
} else {
    // Redirect user to appropriate dashboard page
    if ($userType == 'faculty') {
        header('location: employee_dashboard.php');
    } elseif ($userType == 'student') {
        header('location: student_dashboard.php');
    }
}
?>
