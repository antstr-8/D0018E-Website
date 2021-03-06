<?php

  session_start();

  require_once "php/config.php";
  $tempQuantity = 0;
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
      if($_SERVER["REQUEST_METHOD"] == "POST"){

        $prodid = $_POST['prodid'];
        $quantity = $_POST['quantity'];

        $sql2 = "SELECT quantity FROM cart WHERE custid = :custid AND prodid = :prodid";

        if($stmt2 = $pdo->prepare($sql2)){
          $stmt2->bindParam(":custid", $_SESSION['id'], PDO::PARAM_STR);
          $stmt2->bindParam(":prodid", $prodid, PDO::PARAM_STR);

          if($stmt2->execute()){
              if($stmt2->rowCount() >= 1){



                $res = $stmt2->fetch();
                $quantity = $res['quantity'] + $quantity;
                if($res['quantity'] > 0){
                  $tempQuantity = 1;

                }
                $sql = "UPDATE cart SET quantity = :quantity
                WHERE prodid = :prodid and custid = :custid";
              }
              else{
                $sql = "INSERT INTO cart (custid, prodid, quantity)
                VALUES (:custid, :prodid, :quantity)";
              }
            }
          }
            if($stmt = $pdo->prepare($sql)){
              $stmt->bindParam(":custid", $_SESSION['id'], PDO::PARAM_STR);
              $stmt->bindParam(":prodid", $prodid , PDO::PARAM_STR);
              $stmt->bindParam(":quantity", $quantity , PDO::PARAM_STR);

              if($stmt->execute()){

              }

          }
      }
  }

 ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>

  <!--HEADER STARTS HERE-->
  <div class="header">
    <div class="logBox">
    <?php
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      echo "Welcome <a href='php/profile.php'>" . $_SESSION["username"] . "</a>";
      echo '<style type="text/css">
              .login {
                display:none;
              }
            </style>';
      if($_SESSION["admin"] == 1){
        echo '<style type="text/css">
                .admin {
                  display: inline;
                }
              </style>';
      }
    }

    else{
      echo '<style type="text/css">
              .logout {
                display:none;
              }
            </style>';
    }
    ?>
     <a class="login" href="php/login.php">Login</a>
     <a class="login" href="php/register.php">Sign up</a>
     <a class="logout" href="php/cart.php">Cart</a>
     <a class="logout" href="php/logout.php">Sign out</a>
     <a class="admin" href="php/admin.php">Admin</a>
   </div>
  </div>

  <!--HEADER ENDS HERE-->
  <div class="content">
      <h1>HOMEPAGE</h1>
      <div class="products">
          <?php
            $stmt = $pdo->prepare("SELECT id,name,description FROM prodcat");
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach($result as $row){
              $stht = $pdo->prepare("SELECT id,color,stock,url,price FROM prodinfo WHERE prodid=$row[id]");
              $stht->execute();
              $res = $stht->fetchAll();
              foreach($res as $row2){ ?>


                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class='product_row'>
                  <!--<div class='pictures'>-->
                    <img class="pictures" src="<?php echo $row2['url'];?>" style='max-width: 100%; max-height: 100%;'>
                <!-- echo "testbild"-->
                   <!--</div>-->
                   <div class='description'>
                     <?php echo $row['description']; ?>
                   </div>
                   <div class='price'>
                   <div class='artname'>
                     <a href="php/productPage.php?id=<?php echo $row2['id'];?>">
                       <?php echo $row['name']; ?>
                    </a>
                   </div>

                <?php echo "Price: " . $row2['price']; ?>
                <br>
                <?php echo "Stock: " . $row2['stock'];?>
                <br>
                <?php echo "Color: " . $row2['color'];?>
                </div>
                </div>
                  <input type="number" name="quantity" value="1" min="1" max="<?php echo $row2['stock'];?>">
                  <input type="hidden" name="prodid" value="<?php echo $row2['id'];?>">
                  <input type="submit" value="Add to cart">
                </form>
              <?php
            }
            }
           ?>
       </div>
  <p><?php $tempQuantity; ?></p>

</div>
</body>
</html>
