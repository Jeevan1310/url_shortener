<?php 
session_start();
  require "php/config.php";
  $new_url = "";
  if(isset($_GET)){
    foreach($_GET as $key=>$val){
      $u = mysqli_real_escape_string($conn, $key);
      $new_url = str_replace('/', '', $u);
    }
      $sql = mysqli_query($conn, "SELECT full_url FROM url WHERE shorten_url = '{$new_url}'");
      if(mysqli_num_rows($sql) > 0){
        $sql2 = mysqli_query($conn, "UPDATE url SET clicks = clicks + 1 WHERE shorten_url = '{$new_url}'");
        if($sql2){
            $full_url = mysqli_fetch_assoc($sql);
            header("Location:".$full_url['full_url']);
          }
      }
  }
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>URL Shortener</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <!-- Iconsout Link for Icons -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
</head>
<body>

<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-dark ">
        <div class="container shadow-sm p-2 mb-2 bg-white rounded">
          <!-- Brand -->
          <a class="navbar-brand" href="./index.php">
            Url Shortener
          </a>
          <!-- Toggler -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Collapse -->
          <div class="collapse navbar-collapse" id="navbarCollapse">
            <!-- Nav -->
            <ul class="navbar-nav mx-auto">
              <li class="nav-item ">
                <!-- Toggle 
                <a class="nav-link" href="index.php">Home</a>-->
              </li>
              <li class="nav-item position-static">
                <!-- Toggle 
                <a class="nav-link" href="CarGrid.php">Cars</a>-->
              </li>
              <?php if(isset($_SESSION['activeuser'])){ ?>
              <li class="nav-item">
                <!-- Toggle -->
                <a class="nav-link" href=""></a>
              </li>
              <?php }?>
            </ul>
            <!-- Nav -->
            <ul class="navbar-nav flex-row">
            <?php if(!isset($_SESSION["activeuser"])){ ?>              
              <li class="nav-item ml-lg-n2 mr-sm-3">
                <a class="nav-link" href="login.php">
                    Login              
                </a>
              </li>
              <li class="nav-item ml-lg-n2">
                <a class="nav-link" href="register.php">
                    Register             
                </a>
              </li>
              <?php }  else {?>
              <li class="nav-item ml-lg-n4">
                <a class="nav-link" href="profile.php">My Account
                    <i class="fas fa-user"></i>                
                </a>
              </li>
              <li class="nav-item ml-lg-n2 ml-3">
                <a class="nav-link" href="logout.php">
                    Logout                
                </a>
              </li>
              <?php }?> 
            </ul>
          </div>
        </div>
    </nav>
                 
  
  <div class="wrapper">
    <form action="#" autocomplete="off">
      <input type="text" spellcheck="false" name="full_url" placeholder="Enter or paste a long url" required>
      <i class="url-icon uil uil-link"></i>
      <?php if(!isset($_SESSION["activeuser"])){ ?>
      <a href="login.php"><button>Account</button></a>
      <?php }  else {?>
      <button>Shorten</button>
      <?php }?> 
    </form>
    <?php if(isset($_SESSION['activeuser'])){ ?>
    <?php
      $user_id = $_SESSION['activeuser'][0];
      $sql2 = mysqli_query($conn, "SELECT * FROM url where user_id=$user_id ORDER BY id DESC ");
      if(mysqli_num_rows($sql2) > 0){;
        ?>
          <div class="statistics">
          
            <?php
              $sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM url where user_id=$user_id");
              $res = mysqli_fetch_assoc($sql3);

              $sql4 = mysqli_query($conn, "SELECT clicks FROM url where user_id=$user_id");
              $total = 0;
              while($count = mysqli_fetch_assoc($sql4)){
                $total = $count['clicks'] + $total;
              }
            ?>
            
            <span>Total Links: <span><?php echo end($res) ?></span> & Total Clicks: <span><?php echo $total ?></span></span>
            <a href="php/delete.php?delete_user_id=<?php echo $user_id ?>">Clear All</a>
            
        </div>
        
        <div class="urls-area">
          <div class="title">
            <li>Shorten URL</li>
            <li>Original URL</li>
            <li>Clicks</li>
            <li>Action</li>
          </div>
          <?php
            while($row = mysqli_fetch_assoc($sql2)){
              ?>
                <div class="data">
                <li>
                  <a href="<?php echo $row['shorten_url'] ?>" target="_blank">
                  <?php
                    if($domain.strlen($row['shorten_url']) > 50){
                      echo $domain.substr($row['shorten_url'], 0, 50) . '...';
                    }else{
                      echo $domain.$row['shorten_url'];
                    }
                  ?>
                  </a>
                </li> 
                <li>
                  <?php
                    if(strlen($row['full_url']) > 60){
                      echo substr($row['full_url'], 0, 60) . '...';
                    }else{
                      echo $row['full_url'];
                    }
                  ?>
                </li> 
              </li>
                <li><?php echo $row['clicks'] ?></li>
                <li><a href="php/delete.php?id=<?php echo $row['shorten_url']?>&user_id=<?php echo $row['user_id']?>">Delete</a></li>
              </div>
              <?php
            }
          ?>
      </div>
        <?php
      }
    ?>
    <?php }?>
  </div>

  <div class="blur-effect"></div>
  <div class="popup-box">
  <div class="info-box">Your short link is ready. You can also edit your short link now but can't edit once you saved it.</div>
  <form action="#" autocomplete="off">
    <label>Edit your shorten url</label>
    <input type="text" class="shorten-url" spellcheck="false" required>
    <i class="copy-icon uil uil-copy-alt"></i>
    <button>Save</button>
  </form>
  </div>

  <script src="script.js"></script>

</body>
</html>

    


