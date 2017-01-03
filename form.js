//Перезагружайка Капча
function refresh_aptcha()
{
	return document.getElementById("captcha_code").value="",document.getElementById("captcha_code").focus(),document.images['captchaimg'].src = document.images['captchaimg'].src.substring(0,document.images['captchaimg'].src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}



//Нажимайка
function submit_form()
{
	//уууу Объявляйка переменных
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var fullname = $("#fullname").val();
	var email = $("#email").val();
    var message = $("#message").val();
	var captcha_code = $("#captcha_code").val();
	
	if( fullname == "" ) //Подтверждайка имя
	{
		$("#response_brought").html('<br clear="all"><div class="info" align="left">You forgot to enter your name.</div>');
		$("#fullname").focus();
	}
	else if( email == "" ) //Подтверждайка мэил
	{
		$("#response_brought").html('<br clear="all"><div class="info" align="left">You forgot to enter your e-mail address.</div>');
		$("#email").focus();
	}
	else if(reg.test(email) == false) //Подтверждайка верного мэйла
	{
		$("#response_brought").html('<br clear="all"><div class="info" align="left">Sorry, your e-mail address is incorrect.</div>');
		$("#email").focus();
	}
    else if( message == "" ) //Подтверждайка откуда?
	{
		$("#response_brought").html('<br clear="all"><div class="info" align="left">You forgot to enter your message.</div>');
		$("#message").focus();
	}
	
	else if( captcha_code == "" ) //Подтверждайка кода
	{
		$("#response_brought").html('<br clear="all"><div class="info" align="left">You forgot to enter the security code.</div>');
		$("#captcha_code").focus();
	}
	else
	{
		var dataString = {'fullname':fullname, 'email':email, 'message':message, 'captcha_code':captcha_code, 'submitted':'1'};
        
		$.post( 'contact_form.php', dataString, function(response) 
		{
		  //Проверяйка отправки сообщеньки
			var response_brought = response.indexOf('Congrats');
			if( response_brought != -1 )
			{
				//Очищайка формы
				$("#fullname").val('');
                $("#email").val('');
                $("#message").val('');
				$("#captcha_code").val('');
				
				//Сообщенька, -- все Ок--
				$("#response_brought").html(response);
				refresh_aptcha();
				
				//Удаляйка сообщенька, -- все ок --
				setTimeout(function() {
					$("#response_brought").html('');
				},10000);
			}  
			else  
			{
				//Плохая сообщенька -- не отправлена --
				 $("#response_brought").html(response);
			}
		});
	}
}