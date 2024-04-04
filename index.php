<?php
session_start();
include('includes/config.php');
date_default_timezone_set("Asia/Colombo");

//if ('POST' === $_SERVER['REQUEST_METHOD']) {
if ('POST' === filter_input(INPUT_SERVER, 'REQUEST_METHOD')) {
    if (isset(filter_input_array(INPUT_POST)['login'])) {
        $username = filter_input_array(INPUT_POST)['username'];
        $password = md5(filter_input_array(INPUT_POST)['password']);

        $stmt = $DbConnect->prepare("SELECT UserId, FirstName, LastName, DesignationName, RoleId FROM vw_user WHERE IsDeleted='0' && CurrentStatus='1' && UserName=? && Password=?");
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $stmt->bind_result($userid, $FirstName, $LastName, $DesignationName, $RoleId);
        $rs = $stmt->fetch();

        if ($rs) {
            $userip = $_SERVER['REMOTE_ADDR']; //gives web server IP
            $logtime = date('Y/m/d H:i:s', time());
//            $exec = exec("hostname"); //the "hostname" is a valid command in both windows and linux
//            $hostname = trim($exec); //remove any spaces before and after
//            $userip = gethostbyname($hostname); //resolves the hostname using local hosts resolver or DNS            

            $FullName = $FirstName . " " . $LastName;

            $sql = "insert into userlog(userName,UserFullName,UserDesignation,userIp,loginTime) values('$username','$FullName','$DesignationName','$userip','$logtime')";
            mysqli_query($dbconn, $sql);

            $_SESSION['LogUserFullName'] = $FullName;
            $_SESSION['LogUserName'] = $username;
            $_SESSION['LogUserRoleId'] = $RoleId;
            $_SESSION['LogUserId'] = $userid;

            header("location:dashboard-index.php");
        } else {
            echo "<script>alert('Invalid Username or Password');</script>";
        }
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>R E M I S</title>

        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-social.css">
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <link rel="stylesheet" href="css/fileinput.min.css">
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <div class="login-page bk-img" style="background-color: burlywood">
            <div class="form-content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3" style="margin-top:2%; margin-bottom:1%">
                            <h1 class="text-center text-bold mt-4x" style="font-family: inherit; font-size: xx-large; color: green; font-weight: bold">R E M I S</h1>
                            <h2 class="text-center text-bold mt-1x" style="font-family: inherit; font-size: x-large; color: brown; font-weight: bold">Land Management Information System</h2>
                            <br>
                            <div class="wrap-indicator row pt-2x pb-3x bk-light">
                                <div class="col-md-8 col-md-offset-2">
                                    <form action="" class="mt" method="post">
                                        <label for="username" class="text-uppercase text-sm" style="font-family: inherit; font-size: inherit; color: green;">User Name</label>
                                        <input type="text" placeholder="Enter Your Username" name="username" class="form-control mb" style="font-size: medium;">                                      

                                        <label for="password" class="text-uppercase text-sm" style="font-family: inherit; font-size: inherit; color: green;">Password</label>
                                        <input type="password" placeholder="Enter Your Password" name="password" class="form-control mb" style="font-size: medium;">

                                        <input type="submit" name="login" value="L O G I N" class="btn btn-primary btn-block" style="font-size: large;">
                                    </form>
                                </div>
                            </div>
                            <h1 class="text-center text-bold mt-2x" style="font-family: inherit; font-size: x-large; color: brown; font-weight: bold">Department of Railways</h1>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap-select.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/fileinput.js"></script>
        <script src="js/chartData.js"></script>
        <script src="js/main.js"></script>

    </body>
</html>
