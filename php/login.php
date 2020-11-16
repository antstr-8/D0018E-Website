<?php

  session_start();

  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      header("location: index.php");
      exit;
  }

  require_once "config.php";

  $username = $password = "";
  $username_err = $password_err = "";


  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
      $username_err = "Please enter username.";
    }
    else{
      $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
       $password_err = "Please enter your password.";
    }
    else{
      $password = trim($_POST["password"]);
    }



  if(empty($username_err) && empty($password_err)){
    $sql = "SELECT id, uname, psword FROM customer WHERE uname = :uname";

    if( $stmt = $pdo->prepare($sql)){
      $stmt->bindParam(":uname", $param_username, PDO::PARAM_STR);

      $param_username = trim($_POST["username"]);

      if($stmt->execute()){
        if($stmt->rowCount() == 1){
          if($row = $stmt->fetch()){
            $id = $row["id"];
            $username = $row["uname"];
            $hashed_password = $row["psword"];
            if($password === $hashed_password){
              session_start();

              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;
              header("location: welcome.php");
            }
            else{
              $password_err = "The password you entered was not valid.";
            }
          }
        }
        else{
          $username_err = "No account found with that username.";
        }
      }
      else{
        echo "Oops! Something went wrong. Please try again later.";
      }
        unset($stmt);
    }
  }
  unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div class="content">
      <h2>Login</h2>
      <p>Please fill in your credentials to login.</p>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
        method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error'
         : ''; ?>">

          <label>Username</label>
          <input type="text" name="username" class="form-control" value="<?php
           echo $username; ?>">
           <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
      </form>
    </div>
  </body>
</html>