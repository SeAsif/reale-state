<!DOCTYPE html>
<html>
<head>
<title>Acess To Register</title>
</head>
<body>
<?php
$conn = mysql_connect("localhost","melanio_hjsdz","k4Kz~)=X+oWy");
mysql_select_db("melanio_sdgjnfx",$conn);

$inputData = json_decode(file_get_contents('php://input')); 
$o_id = $inputData->{'order'}->{'id'}; 
$p_id = $inputData->{'product'}->{'id'}; 
$p_name = $inputData->{'product'}->{'name'}; 
$p_price = $inputData->{'product'}->{'price'}; 
$first_name = $inputData->{'customer'}->{'first_name'}; 
$last_name = $inputData->{'customer'}->{'last_name'}; 
$email = $inputData->{'customer'}->{'email'}; 
$phone_number = $inputData->{'customer'}->{'phone_number'}; 

$result = mysql_query("INSERT INTO user_order_status (order_id,product_id,product_name,product_price,customer_fname,customer_lname,customer_email, customer_phone) VALUES ('$o_id','$p_id','".$p_name."','".$p_price."','".$first_name."','".$last_name."','".$email."','".$phone_number."')");

$to = $email;
//$to = "r.ssr100@gmail.com";
$subject = "Welcome to Agent Lead Genesis!";

$message = "
<html>
<head>
<title>Welcome to Agent Lead Genesis!</title>
</head>
<body>

<p>Hi " . $first_name . ",</p>

<p>Welcome to Agent Lead Genesis! You are now steps away from gaining access to more buyer and seller leads than ever.</p>

<p>If you haven't registered via the INSTANT ACCESS button directly after your purchase, please <a href='http://algdash.com/register'>click here</a> to register.</p>

<p>After you register make sure you check your email for your confirmation link. This will allow you to login and access your Agent Lead Genesis Dashboard.</p> 

<p>Product Details:-</p>
<table>
	<tr>
		<th>Order ID</th>
		<td>".$o_id."</td>
	</tr>
	<tr>
		<th>Product Name</th>
		<td>".$p_name."</td>
	</tr>
	<tr>
		<th>Product Price</th>
		<td>".$p_price."</td>
	</tr>
	<tr>
		<th>Email</th>
		<td>".$email."</td>
	</tr>
	<tr>
		<th>Phone Number</th>
		<td>".$phone_number."</td>
	</tr>
</table>

<p>If you have any questions or need help along the way, please contact us at <a href='support@agentleadgenesis.com'>support@agentleadgenesis.com</a> or click the support tab inside your Agent Lead Genesis Dashboard.</p> 

<p>Thank you for choosing Agent Lead Genesis. We are happy to have you with us!!</p>

</body>
</html>";

$header = "From: agentleadgenesis.com\nMIME-Version: 1.0\nContent-Type: text/html; charset=utf-8\n";

  /*  $headers = 'From: agentleadgenesis.com info@agentleadgenesis.com' . "\r\n" ;
    $headers .='Reply-To: '. $to . "\r\n" ;
    $headers .='X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";  */

mail($to,$subject,$message,$header);
mail("userjdr1@gmail.com","New entry in algdash user order by usertesting",$message,$header);
?>
</body>
</html>