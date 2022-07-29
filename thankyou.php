<!DOCTYPE html>
<html>
<head>
<title>Acess To Register</title>
</head>
<body>
<?php
$conn = mysql_connect("localhost","melanio_hjsdz","k4Kz~)=X+oWy");
mysql_select_db("melanio_sdgjnfx",$conn);
$id = $_GET['nhfhdsfhdlodfcgvbufdsfjhgbzxcewrehdshfucushufdhs'];
$res = mysql_query("SELECT order_id FROM active_order_status WHERE `order_id`=".$id);
if(mysql_num_rows($res)==0) {
$result = mysql_query("INSERT INTO active_order_status (order_id) VALUES ($id )");
if($result) {
    mail("userjdr1@gmail.com","New order algdash","New entry in algdash active order by thankyou");
$uid = uniqid();
echo "<script>window.location.href = 'http://www.algdash.com/register?wqeesgdytdtvbjhjfghdghahjapoipoieiiyugxyztxydcs=$id&hdbADSDhgygsfrdADSDughkbOOYYERADZXCBBNBJHI=$uid '</script>";
}
else
{
 echo "There is some error. Please try after some time.";
}
}
else
{
echo "<script>window.location.href = 'http://www.algdash.com/register?wqeesgdytdtvbjhjfghdghahjapoipoieiiyugxyztxydcs=$id&hdbADSDhgygsfrdADSDughkbOOYYERADZXCBBNBJHI=$uid'</script>";
}
?>
</body>
</html>