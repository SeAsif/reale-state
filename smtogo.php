<?PHP

// Built by LucyBot. www.lucybot.com
$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json"
));
curl_setopt($curl, CURLOPT_URL,
  "https://api.smtp2go.com/v3/email/send"
);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
  "api_key" => "api-86734C50A4DB11E79CD3F23C91C88F4E",
  "sender" => "info@algdash.com",
  "to" => array(
    0 => "techdemo22@gmail.com"
  ),
  "subject" => "Test Email",
  "html_body" => "<p>Hello 2243</p>",
  "text_body" => "Message"
)));
$result = curl_exec($curl);
echo $result;
?>