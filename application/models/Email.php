<?php
class Application_Model_Email extends Zend_Db_Table_Abstract
{
	protected $_name = 'email_templates';
	public $primary ="" , $modelStatic; 
	 
	
	
	public function init(){
		
   		$table_info = $this->info('primary');
		$this->primary = $table_info ['1'];
		$this->modelStatic = new Application_Model_Static();
 	}
	
	
	
 	
	/* 	Add / Update User Information 
	 *	@
	 *  Author  - zend
	 */
	 public function sendEmail($type = false ,$data = false){
		 
		  $config = array(   'auth' => 'login',
							'username' => 'info@algdash.com',
							'password' => 'test@123'
						);
		 
		 $transport = new Zend_Mail_Transport_Smtp('algdash.com', $config);
		 $userLogged = isLogged(true);
  		 $mail = new Zend_Mail();
		 $site_config = Zend_Registry::get("site_config");
		 
 		 $SenderName = ""; $SenderEmail = "";$ReceiverName = ""; $ReceiverEmail = "";
		 
		 $admin_info = $this->modelStatic->getAdapter()->select()->from("users")->where("user_id =1")->query()->fetch();
		 
  		 if(!$type){
			 return  (object) array("error"=>true , "success"=>false , "message"=>" Please Define Type of Email");
		}
		
		
 		 
 		switch($type){
			
			case  'reset_password' :  /* begin  : Reset Password Email */
				
				$template = $this->modelStatic->getTemplate('reset_password');
 				
				$ReceiverEmail = $data['user_email'];
				//prd($data);
				$ReceiverName =  $data['user_full_name'];
				
				
				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
				
				if($data['user_type']=="1" or $data['user_type']=="2"){
 					$resetlink = SITE_HTTP_URL."/admin/resetpassword?key=".$data['pass_resetkey'];
 					//$resetlinkhtml='<a href="'.$resetlink.'" >'.$resetlink.'</a>';
				
				}else{
					$resetlink = SITE_HTTP_URL."/user/resetpassword/key/".$data['pass_resetkey'];
	 				$resetlinkhtml='<a href="'.$resetlink.'" >'.$resetlink.'</a>';
				}
   				
				$MESSAGE = str_ireplace(array("{user_name}","{verification_link}","{website_link}" ), array($ReceiverName , $resetlink,APPLICATION_URL),$template['emailtemp_content']);
 				
   			break; /* end : Reset Password Email */
			
			
			
			case 'registration_email':/* begin : Registration Email */
				
  				 
				$template = $this->modelStatic->getTemplate('registration_email');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
				
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
				
  				$resetlink = SITE_HTTP_URL."/user/activate/key/".$data['pass_resetkey'];
 
  				//$resetlinkhtml='<a href="'.$resetlink.'" >'.$resetlink.'</a>';
 				$MESSAGE = str_ireplace(array("{user_name}","{verification_link}","{website_link}" ), array($ReceiverName,$resetlink,APPLICATION_URL),$template['emailtemp_content']);
 				
			break ;/* end : Registration Email */
			
			case 'seller_home_value_agent':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('seller_home_value_agent');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
 				$MESSAGE = str_ireplace(array("{user_name}","{page_name}","{verification_link}","{website_link}" ), array($ReceiverName,$data['page_name'],$resetlink,APPLICATION_URL),$template['emailtemp_content']);
			break ;/* end : Registration Email */
				case 'seller_home_value_custom_agent':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('seller_home_value_custom_agent');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
 				$MESSAGE = str_ireplace(array("{user_name}","{page_name}","{verification_link}","{website_link}" ), array($ReceiverName,$data['page_name'],$resetlink,APPLICATION_URL),$template['emailtemp_content']);
			break ;/* end : Registration Email */
			
			case 'homebuyer_mistakes_agent':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('homebuyer_mistakes_agent');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
 				$MESSAGE = str_ireplace(array("{user_name}","{page_name}","{verification_link}","{website_link}" ), array($ReceiverName,$data['page_name'],$resetlink,APPLICATION_URL),$template['emailtemp_content']);
			break ;/* end : Registration Email */
			
			case 'custom_homebuyer_mistakes_agent':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('custom_homebuyer_mistakes_agent');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
				$resetlink='';
 				$MESSAGE = str_ireplace(array("{user_name}","{page_name}","{verification_link}","{website_link}" ), array($ReceiverName,$data['page_name'],$resetlink,APPLICATION_URL),$template['emailtemp_content']);			
			break ;/* end : Registration Email */
			
			case 'buyer_list_of_homes_agent':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('buyer_list_of_homes_agent');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
 				$MESSAGE = str_ireplace(array("{user_name}","{page_name}","{verification_link}","{website_link}" ), array($ReceiverName,$data['page_name'],$resetlink,APPLICATION_URL),$template['emailtemp_content']);
				
			break ;/* end : Registration Email */
			
				case 'seller_home_value_more_agent':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('seller_home_value_more_agent');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
 				$MESSAGE = str_ireplace(array("{user_name}","{page_name}","{verification_link}","{website_link}" ), array($ReceiverName,$data['page_name'],$resetlink,APPLICATION_URL),$template['emailtemp_content']);
			break ;/* end : Registration Email */
 				
		
		
		/* client temp */
		case 'seller_home_value_client':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('seller_home_value_client');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_name'];
 				$SenderEmail = $data['sender_email']; 
				$SenderName = $data['sender_name']; 
 				$MESSAGE = str_ireplace(array("{name}","{email}","{phone}","{company_name}","{user_name}","{website_link}","{license_no}" ), array($SenderName,$data['sender_email'],$data['sender_phone'],$data['sender_company'],$data['user_name'],APPLICATION_URL,$data['license_no']),$template['emailtemp_content']);
				//prd($MESSAGE);
			break ;/* end : Registration Email */
			
			case 'homebuyer_mistakes_client':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('homebuyer_mistakes_client');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $data['sender_email']; 
				$SenderName = $data['sender_name']; 
				//prn($data);
 				$MESSAGE = str_ireplace(array("{name}","{email}","{phone}","{company_name}","{user_name}","{website_link}","{license_no}"), array($SenderName,$data['sender_email'],$data['sender_phone'],$data['sender_company'],$data['user_name'],APPLICATION_URL,$data['license_no']),$template['emailtemp_content']);
				//prd($MESSAGE);
			break ;/* end : Registration Email */
			case 'custom_homebuyer_mistakes_client':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('custom_homebuyer_mistakes_client');
			
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $data['sender_email']; 
				$SenderName = $data['sender_name']; 
				//prn($data);
 				$MESSAGE = str_ireplace(array("{name}","{email}","{phone}","{company_name}","{user_name}","{website_link}","{license_no}"), array($SenderName,$data['sender_email'],$data['sender_phone'],$data['sender_company'],$data['user_full_name'],APPLICATION_URL,$data['license_no']),$template['emailtemp_content']);
				
			break ;/* end : Registration Email */
			
			case 'buyer_list_of_homes_client':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('buyer_list_of_homes_client');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $data['sender_email']; 
				$SenderName = $data['sender_name']; 
 			$MESSAGE = str_ireplace(array("{name}","{email}","{phone}","{company_name}","{user_name}","{website_link}","{license_no}"), array($SenderName,$data['sender_email'],$data['sender_phone'],$data['sender_company'],$data['user_name'],APPLICATION_URL,$data['license_no']),$template['emailtemp_content']);
			break ;/* end : Registration Email */
			
				case 'seller_home_value_more_client':/* begin : Registration Email */
				$template = $this->modelStatic->getTemplate('seller_home_value_more_client');
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
 				$SenderEmail = $data['sender_email']; 
				$SenderName = $data['sender_name']; 
 				$MESSAGE = str_ireplace(array("{name}","{email}","{phone}","{company_name}","{user_name}","{website_link}","{license_no}"), array($SenderName,$data['sender_email'],$data['sender_phone'],$data['sender_company'],$data['user_name'],APPLICATION_URL,$data['license_no']),$template['emailtemp_content']);
			break ;/* end : Registration Email */
 				
		/*client temp */
		
		
			
			case 'social_registration_email':/* begin : Registration Email */
				
  				 
				$template = $this->modelStatic->getTemplate('social_registration_email');
 				
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
				
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
				
  				$resetlink = SITE_HTTP_URL."/user/activate/key/".$data['pass_resetkey'];
 
  				//$resetlinkhtml='<a href="'.$resetlink.'" >'.$resetlink.'</a>';
 				$MESSAGE = str_ireplace(array("{user_name}","{website_link}" ), array($ReceiverName,APPLICATION_URL),$template['emailtemp_content']);
 				
			break ;/* end : Registration Email */
			
		
			
 			/* Email For Verification of new Email Address */
			case 'email_verification': /* begin :  email_verification */
 				 
				$template = $this->modelStatic->getTemplate('email_verification');
 				
				$ReceiverEmail = $data['user_email'];
				$ReceiverName = $data['user_full_name'];
				
 				$SenderEmail = $site_config['register_mail_id']; 
				$SenderName = $site_config['site_title']; 
				
   				$resetlink = SITE_HTTP_URL."/user/verifyemail/key/".$data['user_email_key'];
 				 
 				//$resetlinkhtml='<a href="'.$resetlink.'" >'.$resetlink.'</a>';
  				
				$MESSAGE = str_ireplace(array("{user_name}","{verification_link}","{website_link}" ), array($ReceiverName,$resetlink,APPLICATION_URL),$template['emailtemp_content']);
   				
  			break ;/* end : email_verification*/
			
			
			case 'contact_us':{
				
 	 			$template = $this->modelStatic->getTemplate("contact_us_user");
				
	 			
 				$sender_email = $data['guest_email'];
				$sender_name = $data['guest_name'];
				$sender_phone = $data['guest_phone'];
				$message = $data['guest_message'];
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject']; 
		 
				$mail_content = str_ireplace(
										array( "{SITE_TITLE}" ,"{site_admin}","{guest_name}","{sender_email}","{sender_phone}","{sender_message}","{website_link}" ), 
										array(	$site_config['site_title'] ,$site_config['site_title'] ,$sender_name,$sender_email,$sender_phone,$message,APPLICATION_URL),
										$template['emailtemp_content']
									);
									
				 
				
				$mail = new Zend_Mail();
				$mail->setBodyHtml($mail_content)
				->setFrom($site_config["register_mail_id"], $site_config['site_title'])
				->addTo($sender_email , $sender_name)
				->setSubject($subject);
		
				
					$mail->send() ;
				
				
				
				
				/* Mail To Admin  */		
				$template =$this->modelStatic->getTemplate("contact_us_admin");
						
				$mail_content = str_ireplace(
											array( "{SITE_TITLE}" ,"{site_admin}","{guest_name}","{guest_email}","{guest_phone}","{guest_message}","{website_link}" ), 
											array(	$site_config['site_title'] ,$site_config['site_title'] ,$sender_name,$sender_email,$sender_phone,$message,APPLICATION_URL),
										$template['emailtemp_content']
									);
				
			
			 
 				$mail = new Zend_Mail();
				$mail->setBodyHtml($mail_content)
					->setFrom($site_config["register_mail_id"], $site_config['site_title'])
					->addTo($site_config['register_mail_id'],$admin_info['user_first_name']." ".$admin_info['user_last_name'])
					->setSubject($subject);
				 
				
				if( $mail->send()){ 
				
				return true;} else {
						
					return false;}
				 
 				
			}
			break;
			
 			default:return  (object)array("error"=>true , "success"=>false , "message"=>" Please Define Proper Type for  Email");
		}
		 
		 //$SenderEmail='agentleadgenesis.com@alg.algdash.com';
		 $mail->setBodyHtml($MESSAGE)
			 ->setFrom($SenderEmail, $SenderName)
			 ->addTo($ReceiverEmail,$ReceiverName)
			 ->setSubject($template['emailtemp_subject']);
			 	
   		
		//$this->sendMessageWithSparkPost($template['emailtemp_subject'],$SenderName,$SenderEmail,$ReceiverName,$ReceiverEmail,$MESSAGE);
		
	// $n= $mail->send();	
		//$n=$mail->send($transport);
		//prd($n);]
		//	$apiKey='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';
//			//$apiKey='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';
//			require_once(ROOT_PATH.'/private/sendgrid/vendor/autoload.php');
//			$SenderName=str_replace(" ","",$SenderName);
//			
//				$sendgrid = new SendGrid($apiKey); 
//				$email= new SendGrid\Email(); 
//				$email->addTo($ReceiverEmail,$ReceiverName);
//				$email->setFrom($SenderName,$SenderEmail)
//				->setReplyTo($SenderEmail)
//				->setSubject($template['emailtemp_subject'])
//				->setHtml($MESSAGE);

				

				//$res=$sendgrid->send($email);

					try {

    //echo 'if'; die;
//prd($SenderEmail);
 // $res=$sendgrid->send($email);

  
  // $n= $mail->send();
  
 		$res=$this->mailcurl($ReceiverEmail,$SenderEmail,$template['emailtemp_subject'],$MESSAGE);
    return (object)array("error"=>false , "success"=>true , "message"=>" Mail Successfully Sent");

   } catch (Exception $e){

   

   // prd($e->getmessage());

    return (object)array("error"=>true , "success"=>false , "message"=>$e->getmessage()); 

    //die;

    //$sent = false;

   }

				//prd($res);

			return (object)array("error"=>false , "success"=>true , "message"=>" Mail Successfully Sent");
  		 
	 }
	 
	 
	 
	 public function mailcurl($ReceiverEmail,$SenderEmail,$sub,$MESSAGE){
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
			"api_key" => "api-2F7BDF08A5D411E793E5F23C91C88F4E",
			"sender" => "".$SenderEmail."",
			"to" => array(
			0 => "".$ReceiverEmail.""
			),
			"subject" => "".$sub."",
			"html_body" => "".$MESSAGE."",
			"text_body" => "Message"
			)));
			$result = curl_exec($curl);
			return $result;		 
	 }
 	

}