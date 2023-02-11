<?php
session_start();
include "db.php";
if (isset($_POST["email"])) {
    $email = $_POST['email'];
    $emailValidation = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
    
    if(empty($email)){
        echo "
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>PLease Fill this field..!</b>
			</div>
		";
		exit();
    }else{
        if(!preg_match($emailValidation,$email)){
		echo htmlspecialchars("
			<div class='alert alert-warning'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>this $email is not valid..!</b>
			</div>
		");
		exit();
	}
        $sql = "SELECT email_id FROM email_info WHERE email = ? LIMIT 1" ;
		$stmt = $conn->prepare($sql); 
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result(); 
        //$check_query = mysqli_query($con,$result);
		$x= $result->fetch_assoc();
        $count_email = mysqli_num_rows($x);
        if($count_email > 0){
            echo "
                <div class='alert alert-danger'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>Email Address is already available</b>
                </div>
            ";
            exit();
        }else{
            
            $sql = "INSERT INTO `email_info` 
            (`email_id`, `email`)
            VALUES (?, ?)";
			
			$stmt = $conn->prepare($sql); 
            $stmt->bind_param("ss",NULL,$email);
            $stmt->execute();
           //$result = $stmt->get_result();
            $run_query = mysqli_query($con,$sql);
                echo "<div class='alert alert-success'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>Thanks for subscribing</b>
                </div>";
                
                
            }

        
    }
    
}
?>