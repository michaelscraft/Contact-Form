<?php
session_start();
ob_start();


if(isset($_POST["submitted"]) && !empty($_POST["submitted"]) && $_POST["submitted"] == 1)
{
	//Распределяйка
	$to_email          = "michael@roshchyn.com";
	$from_fullname     = isset($_POST['fullname']) ? trim(strip_tags($_POST['fullname'])) : '';
    
    $from_email        = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
    
    $e_message      = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
    
	$security_code     = isset($_POST['captcha_code']) ? trim(strip_tags($_POST['captcha_code'])) : '';
	
	
	$message_body = nl2br("Dear Michael,\n
	you have a new message ".$_SERVER['HTTP_HOST']." from ".date('d-m-Y').".\n
	
	Name: ".$from_fullname."\n
    E-mail: ".$from_email."\n
    Message: ".$e_message."\n
	Спасибо!\n\n");
	
	//Получайка е-мейла
    $headers  = "From: $from_fullname <$from_email>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "Message-ID: <".time().rand(1,1000)."@".$_SERVER['SERVER_NAME'].">". "\r\n";      
	
	//Еще болше проверяйки заполненых полей
	if($from_fullname == "")
	{
		echo '<br clear="all"><div class="info" align="left">You forgot to enter your name.</div>';
	}
	elseif($from_email == "")
	{
		echo '<br clear="all"><div class="info" align="left">You forgot to enter your e-mail address.</div>';
	}
	elseif(!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,6})$/", $from_email))
	{
		echo '<br clear="all"><div class="info" align="left">Sorry, your e-mail address is incorrect.</div>';
	}
    elseif($e_message == "")
	{
		echo '<br clear="all"><div class="info" align="left">You forgot to enter your message.</div>';
	}
	elseif($security_code == "")
	{
		echo '<br clear="all"><div class="info" align="left">You forgot to enter security code.</div>';
	}
	elseif(!isset($_SESSION['captcha_code']))
	{
		echo '<br clear="all"><div class="info" align="left">Sorry, wrong security code.</div>';
	}
	else
	{
		if(empty($_SESSION['captcha_code']) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0)
		{
			//PS: Вставить это >>>  strcmp() <<< сделать код чувствительным.
			echo '<br clear="all"><div class="info" align="left">Security code is invalid.</div>';
		}
		else
		{
			 if(@mail($to_email, $email_subject, $message_body, $headers))
			 {
				//Ура >>все ок<< отправляйка работает!
                  ?>
                 <script>
                 $('div#contact_me_form').slideUp("slow", function() {				   
						$(this).before('<div align="left" class="success"><?php echo "$from_fullname" ?>,thank you for your message. I will reply to you as soon as see your message.</div>');
                 });
				  
                                              </script>
			<?php
             
             } 
			 else 
			 {
				 //Буууу  >>все плохо<< отправляйка сломалась!
				  echo "<br clear='all'><div align='left' class='info'>Something went wrong <br>Please try again later or e-mail me directly to michael@roshchyn.com</div>";
			 }
		}
	}
}

?>