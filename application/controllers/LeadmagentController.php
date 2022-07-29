<?php
class LeadmagentController extends Zend_Controller_Action
{
  	private $modelUser ,$modelContent; 
	 
	public function init(){
		//prd("cvcvn");
 		$this->modelStatic = new Application_Model_Static();
		$this->modelEmail = new Application_Model_Email();
			global $objSession;
			$lp_id = $this->_getParam('lp_id');
			$type = $this->_getParam('type');
			if($lp_id!=''){
			$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch',array('fields'=>array('lp_status','lp_pt_id')));
			//prd($page_data);
				if(($page_data['lp_status']==0 && $type=='') || ($page_data['lp_pt_id']!=1))
				{
				$this->_redirect($_SERVER['HTTP_REFERER']);
				}
			}
 	}


	public function step1Action(){
		global $objSession; 
 		$this->_helper->layout()->setLayout('landinglayout');
		$lp_id = $this->_getParam('lp_id');
		$this->view->lp_id=$lp_id;
		$pop_ip_data=array();
		if($lp_id!=''){
			$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch',array('fields'=>array('lp_name','lp_pt_id','lp_url','lp_pixel_code')));
			$pt_id=$page_data['lp_pt_id'];
			$page_title=$page_data['lp_name'];
			$pop_ip_data=$this->modelStatic->Super_Get("popup_ips",'pi_lp_id="'.$lp_id.'" and pi_ip_address="'.$_SERVER['REMOTE_ADDR'].'" ');

		}
		else
		{
			$pt_id=1;
			$page_title='SAN JOSE';
		}
		$bg_image_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_background','pt_thumb')));
		$this->view->bg_image=$bg_image_data['pt_background'];
		$this->view->title=$page_title;
		$this->view->pixel_code=$page_data['lp_pixel_code'];
		$this->view->pop_ip_data=$pop_ip_data;
		$this->view->share_image=$bg_image_data['pt_thumb'];
		$form = new Application_Form_Leadmagent();
		$form->step1();
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
  			
			
			if ($form->isValid($this->getRequest()->getPost()))
			{
				$received_data  = $form->getValues();
				$this->_redirect($page_data['lp_url'].'-2');
			}
			else
			{
				$objSession->errorMsg="Please check the information again!";
			}
 			
 		} // End Post Form
		
		$this->view->form=$form;
 	}
	
	public function step2Action(){
		
		global $objSession; 
		$lp_id = $this->_getParam('lp_id');

		if($lp_id!=''){
			$join=array('users','user_id=lp_user_id','left',array('user_full_name','user_email','user_company_name','user_phone','user_lead_notify','user_license_notify',
					'user_license_number'));
			$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch',array('fields'=>array('lp_name','lp_pt_id','lp_url')),array(0=>$join));
			$pt_id=$page_data['lp_pt_id'];
			$page_title=$page_data['lp_name'];
		}
		else
		{
			$pt_id=1;
			$page_title='SAN JOSE';
		}
 		$this->_helper->layout()->setLayout('landinglayout');
		$bg_image_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_background','pt_type')));
		$this->view->bg_image=$bg_image_data['pt_background'];
		$this->view->title=$page_title;
		$form = new Application_Form_Leadmagent();
		$form->step2();
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
  			
			
			if ($form->isValid($this->getRequest()->getPost()))
			{
				$received_data  = $form->getValues();
				//prd($received_data);
				$received_data['pl_lp_id']=$lp_id;
				$received_data['pl_added']=date('Y-m-d H:i:s');
				if($received_data['pl_lp_id']!=''){
				if((isset($_SESSION['inserted_id'])) && ($_SESSION['inserted_id']!='')){
                    	$inserted_data = $this->modelStatic->Super_Insert("page_leads", $received_data,'pl_id="'.$_SESSION['inserted_id'].'"');
						$inserted_data->inserted_id=$_SESSION['inserted_id'] ;
					}
					else{
					$inserted_data = $this->modelStatic->Super_Insert("page_leads", $received_data);}
				if($page_data['user_license_notify']==1)
				$license='License Number '.$page_data['user_license_number'].'';
				else
				$license='';
				/* Send Email to user */
				$email_data=array(
				
					'user_email'=>$received_data['pl_email'],
					'user_name'=>$received_data['pl_name'],
					'sender_email'=>$page_data['user_email'],
					'sender_name'=>$page_data['user_full_name'],
					'sender_phone'=>$page_data['user_phone'],
					'sender_company'=>$page_data['user_company_name'],
					'license_no'=>$license
				);
				$this->modelEmail->sendEmail('homebuyer_mistakes_client',$email_data);
				/* Send Email to user */
				
				/* Send Email to Agent */
				$email_data=array(
				
					'user_email'=>$page_data['user_email'],
					'user_full_name'=>$page_data['user_full_name'],
					'page_name'=>$page_data['lp_url']
				);
				$this->modelEmail->sendEmail('homebuyer_mistakes_agent',$email_data);
				if($page_data['user_lead_notify']==1){
					$url=SITE_HTTP_URL.'/user/login';
					$msg="You have received a (".$bg_image_data['pt_type']." lead) from your (".$page_data['lp_url'].") landing page. \n
 Log into the ALG system ASAP and follow up:".$url;
					sendsms($page_data['user_phone'],$msg);
				}
				/* Send Email to Agent */
				$_SESSION['inserted_id']=$inserted_data->inserted_id;
				$this->_redirect($page_data['lp_url'].'-3');
				}
			}
			else
			{
				$objSession->errorMsg="Please check the information again!";
			}
 			
 		} // End Post Form
		
		$this->view->form=$form;
 	}
	
	public function step3Action(){
		global $objSession; 
		$this->_helper->layout()->setLayout('landinglayout');
		if($lp_id!=''){
			$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch',array('fields'=>array('lp_name','lp_pt_id','lp_url')));
			$pt_id=$page_data['lp_pt_id'];
			$page_title=$page_data['lp_name'];
		}
		else
		{
			$pt_id=1;
			$page_title='SAN JOSE';
		}
		$lp_id = $this->_getParam('lp_id');
		if(!isset($_SESSION['inserted_id']))
		{
			$this->_redirect($page_data['lp_url'].'-1');
		}
		
		$bg_image_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_background')));
		$this->view->bg_image=$bg_image_data['pt_background'];
	
 		unset($_SESSION['inserted_id']);
 	}
	
	
}

