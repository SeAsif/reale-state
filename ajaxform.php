<?php
$conn = mysql_connect("localhost","melanio_hjsdz","k4Kz~)=X+oWy");
mysql_select_db("melanio_sdgjnfx",$conn);

$action=$_POST["action"];
$emailid=$_POST["emailid"];
$show=mysql_query("Select * from user_order_status where customer_email='$emailid'");
$row=mysql_fetch_array($show);
echo $row[2];
?>