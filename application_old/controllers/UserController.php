<?php
class UserController extends Zend_Controller_Action
{
  	private $modelUser ,$modelContent; 
	 
	public function init(){
 		$this->modelUser = new Application_Model_User();
		$this->modelStatic = new Application_Model_Static();
 	}


	public function indexAction(){
 		$this->_redirect('user/login');
 	}
	
	public function samcartnotifyAction(){
 		
		mail("techdemo22@gmail.com","samcart_notify","hye");
		//mail("techdemo22@gmail.com","samcart_notify2",$_REQUEST);
		//mail("techdemo22@gmail.com","samcart_notify3",$_POST);
		exit;
 	}
	
	 
 	public function loginAction(){
 		
		global $objSession; 
 		$this->view->pageHeading = " Sign in";

		$auth = Zend_Auth::getInstance(); 
		
		if ($auth->hasIdentity()){
            $objSession->infoMsg ='It seems you are already logged into the system ';
			
            $this->_redirect('profile');
        }
		
 		$form = new Application_Form_User();
		$form->login_front();
 		
		/*If You login by the form*/
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
  			
			//prd($posted_data);
			if ($form->isValid($this->getRequest()->getPost()))
			{ // Form Valid
				
				$received_data  = $form->getValues();
				$received_data['user_password']=$received_data['user_password1'];
				unset($received_data['user_password1']);
				$email_data=$this->modelStatic->Super_get("users",'user_email="'.$received_data['user_email'].'"','fetch');
				//prd($email_data);
  				/* Zend_Auth Setup Code */
				$authAdapter = new Zend_Auth_Adapter_DbTable($this->_getParam('db'), 'users', 'user_email', 'user_password'," ? AND (user_type!='admin') and  user_status = '1' " /*, 'MD5(CONCAT(?, password_salt))'*/ );
  				// Set the input credential values
 				$authAdapter->setIdentity($received_data['user_email']);
				$authAdapter->setCredential(md5($received_data['user_password']));
				$result = $auth->authenticate($authAdapter);// Perform the authentication query, saving the result				
				 
				if($result->isValid()){ // IF Auth Get the Record 
 					$data = $authAdapter->getResultRowObject(null); //Now get a result row without user_password set is here
 
 					$auth->getStorage()->write($data); //Now seession set is here
				
					if(isset($_GET['url'])){	
						 $this->_redirect(urldecode($_GET['url']));
					}else{
						if(trim($data->user_company_name)!='')
						 $this->_redirect('profile/home');
						 else
						  $this->_redirect('profile');
					}
					 
				}
				else
				{ // Auth Not Valid
					Zend_Auth::getInstance()->clearIdentity();
					if($email_data['user_email_verified']==0 && $email_data['user_status']==0)
					{
						$objSession->errorMsg = "Please verify your email address first";
					}
					else if($email_data['user_email_verified']==1 && $email_data['user_status']==0)
					{
						$objSession->errorMsg = "Your account is blocked by admin";
					}
					else
					{
					$objSession->errorMsg = "Email or password is invalid";
					}
					
  				}			
			}
 				if($email_data['user_email_verified']==0 && $email_data['user_status']==0)
					{
						$objSession->errorMsg = "Please verify your email address first";
					}
					else if($email_data['user_email_verified']==1 && $email_data['user_status']==0)
					{
						$objSession->errorMsg = "Your account is blocked by admin";
					}
					else
					{
					$objSession->errorMsg = "Invalid email or password";
					}
			$this->redirect("login");
 		} // End Post Form
		
		$this->view->form = $form;
		
	}
		
	
	
	/* Register User  */
	public function registerAction(){
 		global $objSession;
		$this->view->pageHeading="Sign Up";
		$form = new  Application_Form_User();
		$form->register();	
		if($this->getRequest()->isPost()){/* begin : isPost() */			
			$posted_data = $this->getRequest()->getPost();
			
 			if($form->isValid($posted_data)){ /* Begin : isValid()  */
 				
				$this->modelUser->getAdapter()->beginTransaction();
				
				 $data = $form->getValues();
				 
				 $data['user_password']=md5($data['user_password']);	
				 $data['user_type']='user';			 
				 $data['user_created']=date('Y-m-d H:i:s');
				 
				 $isInserted = $this->modelUser->add($data);
					
				if(is_object($isInserted)){
					 if($isInserted->success){
						 $this->modelUser->getAdapter()->commit();
						 $objSession->successMsg = " Registration Successfully Done . You will receive an activation email on your registered email to activate your account ";
							$this->_redirect('login');
					 }
					 
					  $this->modelUser->getAdapter()->rollBack();

					 if($isInserted->error){
						 
						 if(isset($isInserted->exception)){/* Genrate Message related to the current Exception  */
						
						 }
						 
						 $objSession->errorMsg = $isInserted->message;							 
					 }
					
				}else{
					$objSession->errorMsg = " Please Check Information again ";
				}
 				
			}/* end : isValid()  */
			else{/* begin : else isValid() */
				$objSession->errorMsg = " Please Check Information Again..! ";
 			}/* end : else isValid() */
			$this->_redirect('login');
 		}/* end : isPost() */
		
		
		$this->view->form = $form;
	}

  	
	
	
	 
	
	/*Social media sign up*/
	
	/* 	Forgot Password Send Reset Key to User Email Address 
	 *	@
	 *  Author  - zend
	 */
  	public function forgotpasswordAction(){
		
 		global $objSession;	
		
		$this->view->pageHeading="Forgot Password";
		$form = new  Application_Form_User();
		$superModel = new Application_Model_SuperModel();
 		$form->forgotPassword();
		if($this->getRequest()->getPost()){
		  
			$posted_data  =  $this->getRequest()->getPost();
			
			if($form->isValid($posted_data)){
 				$received_data = $form->getValues();
				$isuser=$superModel->Super_Get("users","user_email='".$received_data['user_email']."' and user_status='1' and user_email_verified='1'");
				if(empty($isuser))
				{
					$objSession->errorMsg="Your email is not verified . Please verify your email address first.";	
					$this->redirect('login');
				}
 				$isSend = $this->modelUser->resetPassword($received_data['user_email']);
 				if($isSend){
					$objSession->successMsg = " Mail has been sent to your account ..! ";
					$this->_redirect('login');
				}
				else{
					$objSession->errorMsg = " Please Check Information Again..! ";
				}
			}else{
				$objSession->errorMsg = " Please Check Information Again..! ";
  			}
		  
		}
		
		$this->view->form = $form;
		//$this->_redirect('');
	}
	
	
	
	/* 	Handle Email Link and Reset the Password 
	 *	@
	 *  Author  - zend
	 */
	public function resetpasswordAction(){
		 
		 global $objSession;
		 
		 $this->view->pageHeading = "Reset Password";
  		
		 $form = new Application_Form_User();
		 $form->resetPassword1();
		 
 		 $key = $this->_getParam('key');
		 
		 if(empty($key)){
 			 $objSession->errorMsg = "Invalid Request for Reset Password ";
			 $this->_helper->getHelper("Redirector")->gotoRoute(array(),"login");
		 }
		 
 		 $user_info = $this->modelUser->get(array("key"=>"$key"));
		 
		 if(!$user_info){
			 $objSession->errorMsg = "Invalid Request for Password Reset , Please try again .";
			 $this->_redirect("login");
		 }
		 
 
 		 if($this->getRequest()->getPost()){
			 
			 $posted_data  = $this->getRequest()->getPost();
			 
			 if($form->isValid($posted_data)){
				 
				$data_to_update = $form->getValues() ;
				
				$data_to_update['pass_resetkey']="";
				$data_to_update['user_reset_status']="0";
				
				$data_to_update['user_password'] = md5($data_to_update['user_password']);
				
				$ischeck = $this->modelUser->add($data_to_update,$user_info['user_id']);
				
				//prd($ischeck );
				
				if($ischeck){
					$objSession->successMsg = "Password has been successfully changed";
					$this->_redirect('login');
					
				}
						
			 }else{
					$objSession->errorMsg = " Please Check Information Again..! ";
 			 }/* end : Else isValid() */
 		 
		 }/* end  : isPost()  */
		 
		 
		 
		 $this->view->form = $form;
		 
	 }
	 
	 
	 
	 
	 
	 /* Email Varification and Account Activation 
	 *	@
	 *  Author  - zend
	 */
	 public function activateAction(){
		
 		global $objSession;
		
		$this->view->pageHeading = "Active Account";
		
 		$key = $this->_getParam('key');
		 
		$user_info = $this->modelUser->get(array("key"=>"$key"));
		 
		 if(!$user_info){
			 $objSession->errorMsg = "Invalid Request for Account Activation ";
			$this->_redirect('login');
		 }
		 
 		 $this->modelUser->add(array('pass_resetkey'=>'',"user_reset_status"=>"0",'user_email_verified'=>'1','user_status'=>"1"),$user_info['user_id']);
		 
		 $objSession->successMsg = "Your account has been successfully activated, please login";
		$this->_redirect('login');
	 
	}
	
	 
	 
	
	public function changepasswordAction(){
		
		global $objSession;
		
 		if(!$this->view->user){
			$objSession->infoMsg = "Please Login First to make Changes";
			$this->_redirect("login");
		}
 			 		
		$this->view->pageHeading = "Change Password";
		
		$form = new Application_Form_User();
		$form->changePassword();
		
 		
		if($this->getRequest()->getPost()){
			
			
			
			$posted_data = $this->getRequest()->getPost();
			
			 
				
			if($form->isValid($posted_data)){
				
				$checkOldPassword = $this->modelUser->get(array("where"=>" user_password='".md5($posted_data['user_old_password'])."' and user_id=".$this->view->user->user_id));
			
				if($checkOldPassword){
					
 	 				if($posted_data['user_password'] == $posted_data['user_rpassword']){
					
						//prd($posted_data);
						
						 
				
  						$ischeck = $this->modelUser->add($form->getValues(), $checkOldPassword['user_id']);
						
						if($ischeck){
							$objSession->successMsg = " Password change Successfuly Done ..! ";
							$this->_redirect('user/changepassword');
							
						}
						else{
							$objSession->errorMsg = " Please Check Information Again..! ";
						}
						 
					}else{
						
 						$form->user_password->setErrors(array('Password Mismatch'));
						$form->user_rpassword->setErrors(array('Password Mismatch'));
						$objSession->errorMsg = " Please type the same password.!";
						$this->render('changepassword');
					}
			}else{
				$form->user_old_password->setErrors(array('Old Password is not match '));
				$objSession->errorMsg = " This Old Password is not match.!";
				$this->render('changepassword');
			}
			
			}else{
				$objSession->errorMsg = " Please Check Information Again..! ";
 				$this->render('changepassword');
			}
		}
		
		$this->view->form = $form;
	
	}
	
	
	/* Send Verification Email */
	public function sendverificationAction(){
 		global $objSession;
		
		$modelEmail = new Application_Model_Email();
		
  		$data_form_values = (array) $this->view->user ;
   		if($this->view->user->user_email_verified!="1"){
  			$user_email_key = md5("ASDFUITYU"."!@#$%^$%&(*_+".time());
			$data_to_update = array("user_email_verified"=>"0","user_email_key"=>$user_email_key);
			$this->modelUser->update($data_to_update, 'user_id = '.$this->view->user->user_id);
			$data_form_values['user_email_key'] = $user_email_key ;
			$modelEmail->sendEmail('email_verification',$data_form_values);
 			$objSession->successMsg = " Email Successfully Send to your email address , please follow the verification link to verify the email address ";
 		}else{
			$objSession->infoMsg = "Your Email Address is already verified..";
		}
  		$this->_redirect("profile");
	}
	
	
	
	/* Email Varification  
	 *	@
	 *  Author  - zend
	 */
	 public function verifyemailAction(){
 	
		global $objSession;
 	
		$key = $this->_getParam('key');
		 


		if(empty($key)){
			$objSession->errorMsg = "Please Check Verifications link again";
			 $this->_redirect("login");	
		}
		
 		$user_info = $this->modelUser->get(array("where"=>"user_email_key='".$key."'"));
		 
 		 if(!$user_info){
			 $objSession->errorMsg = "Invalid Request for Account Activation ";
			 $this->_redirect("profile");
		 }
		 
		 $this->modelUser->update(array('user_email_verified'=>'1',"user_email_key"=>""),"user_id=".$user_info['user_id']);
		 
		 $objSession->successMsg = "Your Email Address is successfully verified";
		 $this->_redirect("profile");
 	}
	
  	
	
 	
	/* 	** Private Method for Handling the Uploaded Image 
	 *	@
	 *  Author  - zend
	 */
	private function upload_user_image(){
		
 		$adapter = new Zend_File_Transfer_Adapter_Http();
 		
		$video = $adapter->getFileInfo('user_image');
		
   		$video_extension = $video['user_image']['name'];
		
 		$extension = explode('.',$video['user_image']['name']); 
		
 		$extension = array_pop($extension);
		
  		$name_for_video = md5(rand(1,999)."@#$%@#&^#$@".time()).".".$extension;
		
  		rename(ROOT_PATH .'/images/profile/'.$video_extension ,  ROOT_PATH .'/images/profile/'.$name_for_video);
		
		return $name_for_video ;
  	}
	
	
	/* 	Ajax Call For Checking the Email Existance for the user email 
	 *	@
	 *  Author  - zend
	 */
	public function checkemailAction(){

 		$email_address = strtolower($this->_getParam('user_email'));
		
		$exclude = strtolower($this->_getParam('exclude'));
		
		$user_id = false ;
		if(!empty($exclude)){
			 $user = $this->view->user;
			 $user_id =$user->user_id;
			
		}

		$email = $this->modelUser->checkEmail($email_address,$user_id);
		
		$rev = $this->_getParam("rev");
		
		if(!empty($rev)){
 			if($email)
				echo json_encode("true");
 			else
				echo json_encode("`$email_address` is not registered , please enter valid email address ");
 			exit();
 		}
		
 
		if($email)
			echo json_encode("`$email_address` already exists. Please try a different email address. Questions? Contact us at support@agentleadgenesis.com");
		else
			echo json_encode("true");
		exit();
	}
	
	
	
	
	
	/* 	Ajax Call For Checking the Old Password for the Logged User 
	 *	@
	 *  Author  - zend
	 */
	public function checkpasswordAction(){
		
		$auth = Zend_Auth::getInstance();
		
		if($auth->hasIdentity()){
			
			$user_password = md5($this->_getParam('user_old_password'));
 			$user = $this->modelUser->get(array('where'=>"user_password='".$user_password."' and user_id=".$this->view->user->user_id));
			
			if(!$user){
				echo json_encode("Old Password Mismatch , Please Enter Correct old password");
			}else{
				echo json_encode("true");	
			}
		}else{
			echo json_encode("Please Login For Make Changes..");
		}
				
 		exit();
	}
	
	
	
	
	/* 	Logout Action 
	 *	@ *  Author  - zend
	
	 */
  	public function logoutAction(){ 
 	    global $objSession;	
		
		$auth = Zend_Auth::getInstance();
 	
		if($this->view->user){
			
			$user =  $this->view->user;
			
			if($user->user_login_type!="normal"){
				
				 
				if($user->user_oauth_provider=="facebook"){
					
 					$facebook = new Facebook(array(
						'appId' => Zend_Registry::get("keys")->facebook->appId ,
						'secret' =>Zend_Registry::get("keys")->facebook->secret ,
						'cookie' => true
					));
					
					$auth->clearIdentity();
								
					$logout_url = $facebook->getLogoutUrl(array( 'next' => APPLICATION_URL."/login"));
					
					header("Location:".$logout_url);
					 		
					$objSession->successMsg = "You are now logged out. ..! ";
					
					exit();
				
 				}
 			}
			
			$auth->clearIdentity();
 		
			$objSession->successMsg = "You are now logged out. ..! ";
			
			$this->_redirect('user/login');
 				 
		}
			
			$objSession->successMsg = "You are now logged out. ..! ";

			$this->_redirect('user/login');
	}
  	
}

