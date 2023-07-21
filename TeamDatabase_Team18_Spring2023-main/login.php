

<?php
 require_once(__DIR__."/testfunction.php");
 require_once(__DIR__."/connect.php");
session_start();
$invalid=0;
if($_SERVER['REQUEST_METHOD']=='POST'){

    echo pre();

    $ID=$_POST['ID'];
    $password=$_POST['password'];

    if(strlen($ID) <= 5){
        // $sql="SELECT * from employee_t where employeeID='$ID' and password='$password'";
        $sql="SELECT * from vctt where employeeID='$ID' and password='$password'";
        var_dump($_POST);
        $result=mysqli_query($con,$sql);
        if($result){
            $num=mysqli_num_rows($result);
            if($num>0){
                $invalid=0;
                $_SESSION['ID']=$ID;
                $_SESSION['userType']='faculty';
                header('location:employee_dashboard.php');
                // header('location:estudent_dashboard.php');
            }
        }
    }else{
        var_dump($_POST);
        $sql="SELECT * from student_t where studentID='$ID' and password='$password'";
        $result=mysqli_query($con,$sql);
        if($result){
            $num=mysqli_num_rows($result);
            if($num>0){
                $invalid=0;
                $_SESSION['ID']=$ID;
                $_SESSION['userType']='student';
                header('location:estudent_dashboard.php');
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <title>Login page</title>
    
    <style>


    </style>

  </head>
  <body background="project_image/varsity2.jpg" class="body_deg">
 <?php
 if($invalid==1){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong></strong> Invalid credentials!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
  }
  ?>

  <center>
        <div  class="form_deg">
        <center class="title_deg">
          Login Form
          <h4>
          </h4>
        </center>  
        <form action="login.php" method="post" class="login_form"> 
        <div>
          <label class="label_deg">User ID</label>
          <input type="text" name="ID" placeholder="Enter Your ID">
        </div> 
        <div>
          <label class="label_deg">Password</label>
          <input type="Password" name="password" placeholder="Enter Your Password">
        </div>
        <div>
          <input class="btn btn-primary" type="submit" name="submit" value="Login">
          <button style="margin-top: 10px; padding:2px; margin-left:5px; color:aqua; "  class="btn btn-dark" type="button" name="button" value="signup"> <a href="signup.php"><h4>Sign Up</h4></a></button>
        </div>
        <div>
        </div>
      </div>
 </center>

</body>
</html>