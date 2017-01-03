<?php 

session_start();

//If you reduce the width and height of the captcha here then you have to change it in the css file as well
$image_width = 150;
$image_height = 29;
$characters_on_image = 6; //Number of characters to display on the captcha image
$font = 'font/courier_new/courier_new.ttf';

//The characters that can be used in the CAPTCHA code. Avoid confusing characters (l 1 and i for example)
$possible_letters = '1234567890';
$random_dots = 0;
$random_lines = 0;
$captcha_text_color="FFFFFF";    //Text color
$captcha_noice_color = "7fbf4d"; //Text color

$code = '';
$i = 0;
while ($i < $characters_on_image) 
{ 
	$code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
	$i++;
}
$font_size = $image_height * 0.75;
$image = @imagecreate($image_width, $image_height);


/*Setting the background, text and noise colours here */
$background_color = imagecolorallocatealpha($image, 255, 255, 255, 127);

$arr_text_color = RGBA_HEX($captcha_text_color);
$text_color = imagecolorallocate($image, $arr_text_color['red'], 
$arr_text_color['green'], $arr_text_color['blue']);
$arr_noice_color = RGBA_HEX($captcha_noice_color);
$image_noise_color = imagecolorallocate($image, $arr_noice_color['red'], 
$arr_noice_color['green'], $arr_noice_color['blue']);

/*This generates the dots randomly strings in background */
for( $i=0; $i<$random_dots; $i++ ) 
{
	imagefilledellipse($image, mt_rand(0,$image_width),
 	mt_rand(0,$image_height), 2, 3, $image_noise_color);
}

/*This generates lines randomly strings in background of image */
for( $i=0; $i<$random_lines; $i++ ) 
{
	imageline($image, mt_rand(0,$image_width), mt_rand(0,$image_height),
 	mt_rand(0,$image_width), mt_rand(0,$image_height), $image_noise_color);
}

/*This creates a text box and add 6 letters code in it */
$textbox = imagettfbbox($font_size, 0, $font, $code); 
$x = ($image_width - $textbox[4])/2;
$y = ($image_height - $textbox[5])/2;
imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code);

/* Show captcha image in the page html page */
header('Content-Type: image/png');// defining the image type to be shown in browser window
imagepng($image);//showing the image
imagedestroy($image);//destroying the image instance
$_SESSION['captcha_code'] = $code;

function RGBA_HEX ($hexstr)
{
	$int = hexdec($hexstr);
	return array("red" => 0xFF & ($int >> 0x10),"green" => 0xFF & ($int >> 0x8),"blue" => 0xFF & $int);
}
?>