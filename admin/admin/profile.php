<?php

session_start();
include("./includes/db.php");
if (isset($_POST['re_password'])) {
  $email = $_SESSION['admin_email'];
  $old_pass = $_POST['old_pass'];
  $op = crypt($old_pass, 'st');
  $new_pass = $_POST['new_pass'];
  $re_pass = $_POST['re_pass'];

  $password_query = mysqli_prepare($con, "SELECT * FROM admin_info WHERE admin_email = ?");
  mysqli_stmt_bind_param($password_query, "s", $email);
  mysqli_stmt_execute($password_query);
  $password_result = mysqli_stmt_get_result($password_query);
  $password_row = mysqli_fetch_array($password_result);
  $database_password = $password_row['admin_password'];

  if ($database_password == $op) {
    if ($new_pass == $re_pass) {
      $pass = crypt($re_pass, 'st');
      $update_pwd = mysqli_prepare($con, "UPDATE admin_info SET admin_password = ? WHERE admin_id = 6");
      mysqli_stmt_bind_param($update_pwd, "s", $pass);
      mysqli_stmt_execute($update_pwd);
      echo "<script>alert('Update Successfully'); </script>";
    } else {
      echo "<script>alert('Your new and Retype Password do not match'); </script>";
    }
  } else {
    echo "<script>alert('Your old password is incorrect'); </script>";
  }
}

include "sidenav.php";
include "topheader.php";
?>
   <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Edit Profile</h4>
                  <p class="card-category">Complete your profile</p>
                </div>
                <div class="card-body">
                  <form method="post" action="profile.php">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">
                            <?php  if (isset($_SESSION['admin_name'])) : ?>?><?php echo $_SESSION['admin_name']; ?>
             <?php endif ?>
                          
                        </label>
                          <input type="text" class="form-control" disabled="">
                        </div>
                      </div>
                     <div class="col-md-4">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">enter old password</label>
                          <input type="text" class="form-control" name="old_pass" id="npwd">
                        </div>
                      </div>
                    
                  
                      <div class="col-md-4">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">Change Password Here</label>
                          <input type="text" class="form-control" name="new_pass" id="npwd">
                        </div>
                      </div>
                     
                      <div class="col-md-4">
                        <div class="form-group bmd-form-group">
                          <label class="bmd-label-floating">confirm Password Here</label>
                          <input type="text" class="form-control" name="re_pass" id="npwd">
                        </div>
                      </div>
               
                    <button class="btn btn-primary pull-right" type="submit" name="re_password">Update Profile</button>
                   
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
         
          </div>
        </div>
      </div>
      <?php
include "footer.php";
?>