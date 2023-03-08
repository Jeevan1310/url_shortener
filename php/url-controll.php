<?php
session_start();
extract($_REQUEST);

if(!isset($_SESSION['activeuser'])){
  header('location: login.php?flag=test');
  }
else {
    require "config.php";
    $user_id = $_SESSION['activeuser'][0];
    $full_url = mysqli_real_escape_string($conn, $_POST['full_url']);
    if(!empty($full_url) && filter_var($full_url, FILTER_VALIDATE_URL)){
        $ran_url = substr(md5(microtime()), rand(0, 26), 5);
        $sql = mysqli_query($conn, "SELECT * FROM url WHERE shorten_url = '{$ran_url}'");
        if(mysqli_num_rows($sql) > 0){
            echo "Something went wrong. Please generate again!";
        }else{
            $sql2 = mysqli_query($conn, "INSERT INTO url (user_id,full_url, shorten_url, clicks) 
                                         VALUES ($user_id,'{$full_url}', '{$ran_url}', '0')");
            if($sql2){
                $sql3 = mysqli_query($conn, "SELECT shorten_url FROM url WHERE shorten_url = '{$ran_url}'");
                if(mysqli_num_rows($sql3) > 0){
                    $shorten_url = mysqli_fetch_assoc($sql3);
                    echo $shorten_url['shorten_url'];
                }
            }
        }
    }else{
        echo "$full_url - This is not a valid URL!";
    }
}
?>