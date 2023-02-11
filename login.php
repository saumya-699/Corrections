<?php
include "db.php";

session_start();

#Login script is begin here
#If user given credential matches successfully with the data available in database then we will echo string login_success
#login_success string will go back to called Anonymous funtion $("#login").click() 

if(isset($_POST["email"]) && isset($_POST["password"])){
	$email = mysqli_real_escape_string($con,$_POST["email"]);
	$password = $_POST["password"];
	$sql = "SELECT * FROM user_info WHERE email = ? AND password = ?";
	$stmt = $con->prepare($sql); 
    $stmt->bind_param("ss", $email,$password);
    $stmt->execute();
    $result = $stmt->get_result(); 
	 $count= $result->num_rows;// get the mysqli result
     while( $row = $result->fetch_assoc()){
	//$run_query = mysqli_query($con,$sql);
	 //$count = mysqli_num_rows($run_query);
	  //
   // $row = mysqli_fetch_array($run_query);
		$_SESSION["uid"] = $row["user_id"];
		$_SESSION["name"] = $row["first_name"];
		$ip_add = getenv("REMOTE_ADDR");
		//we have created a cookie in login_form.php page so if that cookie is available means user is not login
	 }
	//if user record is available in database then $count will be equal to 1
	if($count == 1){
		   	
			if (isset($_COOKIE["product_list"])) {
				$p_list = stripcslashes($_COOKIE["product_list"]);
				//here we are decoding stored json product list cookie to normal array
				$product_list = json_decode($p_list,true);
				for ($i=0; $i < count($product_list); $i++) { 
					//After getting user id from database here we are checking user cart item if there is already product is listed or not
					$verify_cart = "SELECT id FROM cart WHERE user_id = ? AND p_id = ?";
					
					$stmt = $conn->prepare($verify_cart); 
                   $stmt->bind_param("ss",$_SESSION[uid],$product_list[$i] );
                   $stmt->execute();
				   $result = $stmt->get_result(); 
					//$result  = mysqli_query($con,$verify_cart);
					if(mysqli_num_rows($result) < 1){
						//if user is adding first time product into cart we will update user_id into database table with valid id
						$update_cart = "UPDATE cart SET user_id = ? WHERE ip_add = ? AND user_id = ?";
						
						$stmt = $conn->prepare($sql); 
                    $stmt->bind_param("sss",-1,$ip_add,$product_list[$i]);
                      $stmt->execute();
						//mysqli_query($con,$update_cart);
					}else{
						//if already that product is available into database table we will delete that record
						$delete_existing_product = "DELETE FROM cart WHERE user_id = ? AND ip_add = ? AND p_id = ?";
						//mysqli_query($con,$delete_existing_product);
						$stmt = $conn->prepare($sql); 
                     $stmt->bind_param("sss", -1,$ip_add,$product_list[$i]);
                     $stmt->execute();
						
						
						
						
						
					}
				}
				//here we are destroying user cookie
				setcookie("product_list","",strtotime("-1 day"), NULL, NULL, true, true);
				//if user is logging from after cart page we will send cart_login
				echo "cart_login";
				
				
				exit();
				
			}
			//if user is login from page we will send login_success
			echo "login_success";
			$BackToMyPage = $_SERVER['HTTP_REFERER'];
				if(!isset($BackToMyPage)) {
					self::$_headers->header('Location: '.$BackToMyPage);
					echo"<script type='text/javascript'>
					
					</script>";
				} else {
					echo "<script> location.href='index.php'; </script>" ;// default page
				} 
				
			
            exit;

		}else{
                $email = mysqli_real_escape_string($con,$_POST["email"]);
                $password = crypt($_POST["password"],'rl') ;
                $sql = "SELECT * FROM admin_info WHERE admin_email = ? AND admin_password = ?";
				$stmt = $con->prepare($sql); 
              $stmt->bind_param("ss",$email,$password);
                  $stmt->execute();
				  $result = $stmt->get_result();
                //$run_query = mysqli_query($con,$result);
                //$count = mysqli_num_rows($run_query);
				$count= $result->num_rows;

            //if user record is available in database then $count will be equal to 1
            if($count == 1){
              // $row = mysqli_fetch_array($run_query);
			//  $row= $result->fetch_all(MYSQLI_ASSOC);
			while ($row = $result->fetch_assoc()) {
                $_SESSION["uid"] = $row["admin_id"];
                $_SESSION["name"] = $row["admin_name"];
			$ip_add = getenv("REMOTE_ADDR");
			}
                //we have created a cookie in login_form.php page so if that cookie is available means user is not login


                    //if user is login from page we will send login_success
                    echo "login_success";

                    echo "<script> location.href='admin/add_products.php'; </script>";
                    exit;

                }else{
                    echo "<span style='color:red;'>Please register before login..!</span>";
                    exit();
                }
    
	
}
    
	
}

?>