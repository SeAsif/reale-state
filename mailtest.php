<!DOCTYPE html>
<html>
<head>
<title>Acess To Register</title>
</head>
<body>
<?php

$to = "r.ssr100@gmail.com";
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

   /* $headers = 'From: agentleadgenesis.com<info@algdash.com>' . "\n" ;
    $headers .='Reply-To: '. $to . "\n" ;
    $headers .='X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/html;  charset=utf-8\n";  */

mail($to,$subject,$message,$header);
?>
</body>
</html>