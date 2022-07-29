<?php
class IndexController extends Zend_Controller_Action
{
	public function init(){	
	$this->modelStatic = new Application_Model_Static();
	//mail("techdemo22@gmail.com","hye","hye");
	//	prd("fg");
	/*	$modelSlider = new Application_Model_Email();
		
		$data=array(
			'user_email'=>'web-q2jsp@mail-tester.com',
				'user_full_name'=>'xddfgfh',
		
		);
		$modelSlider->sendEmail('registration_email',$data);
		prd("vb");*/
		
		$this->_redirect(SITE_HTTP_URL.'/login');
		
  	}

	public function testingAction(){
		
	}
	public function pricingAction(){
		
	}
	
	  public function addipAction()
    { 
		$lp_id = $this->_getParam('lp_id');
		$data=array(
			'pi_ip_address'=>$_SERVER['REMOTE_ADDR'],
			'pi_lp_id'=>$lp_id,
			'pi_added'=>date("Y-m-d H:i:s")
		);
		$this->modelSuper->Super_Insert("popup_ips",$data);
		exit;
		
	}
	
		
 	public function indexAction(){	
		
		global $objSession ; 
	
		//sendsms("+919529581566","hello"); prd("vbvbm");
  		$this->view->pageHeading = "Home";
 		$modelSlider = new Application_Model_Slider();
		$this->view->page_slug="Home";
		$images = $modelSlider->fetchImages();
		$auth = Zend_Auth::getInstance(); 
		$this->view->slider_images = $images ;
 		$form = new Application_Form_User();
		$form->login_front();
 		
		
		/*If You login by the form*/
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
  			
			
			if ($form->isValid($this->getRequest()->getPost()))
			{ // Form Valid
				
				$received_data  = $form->getValues();
  				/* Zend_Auth Setup Code */
				$authAdapter = new Zend_Auth_Adapter_DbTable($this->_getParam('db'), 'users', 'user_email', 'user_password'," ? AND (user_type='user' or user_type='business') and  user_status = '1' " /*, 'MD5(CONCAT(?, password_salt))'*/ );
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
						 $this->_redirect('profile');
					}
					 
				}
				else
				{ // Auth Not Valid
					Zend_Auth::getInstance()->clearIdentity();
					$objSession->errorMsg = "Email or password is invalid.";
					
  				}			
			}
 			$objSession->errorMsg = "Email or password is invalid.";
			$this->redirect("index");
 		} // End Post Form
		
		$this->view->form = $form;
 
  	}
	
	
	public function testAction(){
		
		$imagePlugin = new Application_Plugin_Image();
		
		prd($imagePlugin->universal_unlink("zend.jpg",ROOT_PATH."/zend"));
		
	}
	
}


