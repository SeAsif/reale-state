<?php
class CustomhomevaluationController extends Zend_Controller_Action
{
  	private $modelUser ,$modelContent; 
	 
	public function init(){
		
 		$this->modelStatic = new Application_Model_Static();
		$this->modelEmail = new Application_Model_Email();
		
		global $objSession;
			$lp_id = $this->_getParam('lp_id');
			$type = $this->_getParam('type');
			if($lp_id!=''){
			$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch',array('fields'=>array('lp_status','lp_pt_id','lp_pixel_code')));
			//prd($page_data);
				if(($page_data['lp_status']==0 && $type=='') || ($page_data['lp_pt_id']!=5))
				{
				$this->_redirect($_SERVER['HTTP_REFERER']);
				}
			}
 	}


	public function step1Action(){
		global $objSession; 
		//prd("vvbn");
 		$this->_helper->layout()->setLayout('landinglayout');
		$lp_id = $this->_getParam('lp_id');
		$this->view->lp_id=$lp_id;
		$pop_ip_data=array();
		if($lp_id!=''){
			
		  $join=array('users','user_id=lp_user_id','left',array('user_full_name','user_email','user_company_name','user_phone','user_lead_notify'));
			$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch',array('fields'=>array('lp_name','lp_pt_id','lp_url','lp_bg_image','lp_c_sent1','lp_c_sent2','lp_pixel_code')),array(0=>$join));
			
			$sent1_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="1"','fetch');
			$sent2_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="2"','fetch');
			$button_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="3"','fetch');
			$this->view->sent1_style=$sent1_style;
			$this->view->sent2_style=$sent2_style;
			$this->view->button_style=$button_style;
			$this->view->pixel_code=$page_data['lp_pixel_code'];
			$pt_id=$page_data['lp_pt_id'];
			$page_title1=$page_data['lp_c_sent1'];
			$page_title2=$page_data['lp_c_sent2'];
						$pop_ip_data=$this->modelStatic->Super_Get("popup_ips",'pi_lp_id="'.$lp_id.'" and pi_ip_address="'.$_SERVER['REMOTE_ADDR'].'" ');

		}
		else
		{
			$pt_id=5;
			$page_title1="DON'T LEAVE MONEY ON THE TABLE!";
			$page_title2="Big brand online estimators can be off by 10-20%. Get your TRUE home value here.";
		}
		$bg_image_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_background','pt_thumb','pt_type')));
		if(!empty($page_data) && $page_data['lp_bg_image']!='')
		{
					$this->view->bg_image=$page_data['lp_bg_image'];
		}
		else
		{
					$this->view->bg_image=$bg_image_data['pt_background'];
		}
		
	
		$this->view->title1=$page_title1;
		$this->view->title2=$page_title2;
		$this->view->pop_ip_data=$pop_ip_data;
		$this->view->share_image=$bg_image_data['pt_thumb'];
		$form = new Application_Form_Customhomevalue();
		$form->step1();
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
  			
			
			  if ($form->isValid($this->getRequest()->getPost())) {
                $received_data = $form->getValues();
                if ($received_data['homeval_Lat'] == '' || $received_data['homeval_Lng'] == '') {
                    $string                       = get_lat_long($received_data['pl_address']);
                    $new_str                      = explode(",", $string);
                    $received_data['homeval_Lat'] = $new_str[0];
                    $received_data['homeval_Lng'] = $new_str[1];
                }
                if ($received_data['homeval_Lat'] == '' || $received_data['homeval_Lng'] == '') {
                    $objSession->errorMsg = "Your location not found please try again!";
                     $this->_redirect($page_data['lp_url'] . '-1');
                }
                $received_data['pl_lp_id'] = $lp_id;
                $received_data['pl_added'] = date('Y-m-d H:i:s');
                if ($received_data['pl_lp_id'] != '') {
				
				
                 if((isset($_SESSION['inserted_id'])) && ($_SESSION['inserted_id']!='') &&(trim($_SESSION['homevalue']['homeval_address'])==trim($received_data['pl_address']))){
                    	
							unset($received_data['homeval_Lat']);
					unset($received_data['homeval_Lng']);
						$inserted_data = $this->modelStatic->Super_Insert("page_leads", $received_data,'pl_id="'.$_SESSION['inserted_id'].'"');
						$inserted_data->inserted_id=$_SESSION['inserted_id'] ;
						   /* Send Email to Agent */
                   			
					}
					else{
						
							$_SESSION['homevalue']['homeval_Lat']=$received_data['homeval_Lat'];
					$_SESSION['homevalue']['homeval_Lng']=$received_data['homeval_Lng'];
					$_SESSION['homevalue']['homeval_address']=$received_data['pl_address'];
						unset($received_data['homeval_Lat']);
					unset($received_data['homeval_Lng']);
					$inserted_data = $this->modelStatic->Super_Insert("page_leads", $received_data);
					
					 $email_data = array(
                        'user_email' => $page_data['user_email'],
						'page_name'=>$page_data['lp_url'],
                        'user_full_name' => $page_data['user_full_name']
                    );
                    		$this->modelEmail->sendEmail('seller_home_value_custom_agent', $email_data);
                  			  if ($page_data['user_lead_notify'] == 1) {
                        $url = SITE_HTTP_URL . '/user/login';
                        $msg = "You have received a (" . $bg_image_data['pt_type'] . " lead) from your (" . $page_data['lp_url'] . ") landing page. \n
 Log into the ALG system ASAP and follow up:" . $url;
                        sendsms($page_data['user_phone'], $msg);
                    }
					}
                    //prd($inserted_data);
                    $_SESSION['inserted_id'] = $inserted_data->inserted_id;
					//prd("cvcvn");
					$this->_redirect($page_data['lp_url'] . '-2');
                    //prd("xcxc");
                }
               // prd($received_data);
                
            } else {
                $objSession->errorMsg = "Please check the information again!";
            }
 			
 		} // End Post Form
		
		$this->view->form=$form;
 	}
	
		function _handle_uploaded_image($path)
	{
		
		
 		global $objSession; 
		
		$uploaded_image_names = array();
	 
		$adapter = new Zend_File_Transfer_Adapter_Http();
	
		$files = $adapter->getFileInfo();
  		 
		$uploaded_image_names = array();
		
		$new_name = false; 
		 
  		/*prd($adapter);*/
		foreach ($files as $file => $info) { /* Begin Foreach for handle multiple images */
		
  			$name_old = $adapter->getFileName($file);
			
			if(empty($name_old)){
				continue ;			
			}
			
			$file_title  = $adapter->getFileInfo($file);
			
			$file_title = $file_title[$file]['name']; 
				
  			$uploaded_image_extension = getFileExtension($name_old);
			
 			$file_title  = str_replace(".".$uploaded_image_extension,"",$file_title);
			
			$file_title = formatImageName($file_title);
  
 			$new_name = $file_title."-".time()."-".rand(1,100000).".".$uploaded_image_extension;
 			
 			$adapter->addFilter('Rename',array('target' => $path."/".$new_name));
		
			try{
				$adapter->receive($file);
			}
			catch(Zend_Exception $e){
				return (object) array('success'=>false,"error"=>true,'exception'=>true,'message'=>$e->getMessage(),'exception_code'=>$e->getCode()) ;
			}
			
			if($path!='CV_IMAGES_PATH'){
				$thumb_config = array("source_path"=>$path,"name"=> $new_name);
				Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("destination_path"=>$path."/100","size"=>100)));
			}
			
				 
 			//$uploaded_image_names[]=array('media_path'=>$new_name); => For Multiple Images
   		
		}/* End Foreach Loop for all images */
		
		
		return (object)array("success"=>true,'error'=>false,"message"=>"Image(s) Successfully Uploaded","media_path"=>$new_name) ;
 		
   	 
 	}
	public function getstylesAction(){
		global $objSession; 
		$this->_helper->layout->disableLayout();
 		$this->_helper->layout()->setLayout('simplelayout');
		if($_FILES['pt_background']['name']!='')
				{
					$is_uploaded = $this->_handle_uploaded_image(TEMPLATE_IMAGES);	
						$this->view->bg_image=$is_uploaded->media_path;
				}
				else
				{
					if((isset($_POST['lp_id'])) &&  $_POST['lp_id']!='')
					{
						$join=array('users','user_id=lp_user_id','left',array('user_full_name','user_email','user_company_name','user_phone','user_lead_notify'));
			$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$_POST['lp_id'].'"','fetch',array('fields'=>array('lp_name','lp_pt_id','lp_url','lp_bg_image','lp_c_sent1','lp_c_sent2')),array(0=>$join));
					$this->view->bg_image=$page_data['lp_bg_image'];
			
					}
					else
					{
						$bg_image_data=$this->modelStatic->Super_Get("page_templates",'pt_id="5"','fetch',array('fields'=>array('pt_background','pt_thumb','pt_type')));

						$this->view->bg_image=$bg_image_data['pt_background'];
						
					}
					
				}
				if(trim($_POST['lp_c_sent1'])=='')
				{
					$_POST['lp_c_sent1']="DON'T LEAVE MONEY ON THE TABLE!";
				}
				if(trim($_POST['lp_c_sent2'])=='')
				{
					$_POST['lp_c_sent2']="Big brand online estimators can be off by 10-20%. Get your TRUE home value here.";
				}
				
				if((isset($_POST['lps_font1'])) || (isset($_POST['lps_font_size1'])) ||(isset($_POST['lps_font_style1'])) ||(isset($_POST['lps_font_color1'])) )
				{
					$sent1=array(
					
						'lps_font'=>$_POST['lps_font1'],
						'lps_font_size'=>$_POST['lps_font_size1'],
						'lps_font_style'=>$_POST['lps_font_style1'],
						'lps_font_color'=>$_POST['lps_font_color1'],
					
					);
					$this->view->sent1_style=$sent1;
				}
				else
				{
					if((isset($_POST['lp_id'])) && $_POST['lp_id']!='')
					{
							$sent1_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$_POST['lp_id'].'" and lps_lp_element="1"','fetch');
							$this->view->sent1_style=$sent1_style;
					}
				}
				
				if((isset($_POST['lps_font2'])) || (isset($_POST['lps_font_size2'])) ||(isset($_POST['lps_font_style2'])) ||(isset($_POST['lps_font_color2'])) )
				{
					$sent2=array(
					
						'lps_font'=>$_POST['lps_font2'],
						'lps_font_size'=>$_POST['lps_font_size2'],
						'lps_font_style'=>$_POST['lps_font_style2'],
						'lps_font_color'=>$_POST['lps_font_color2'],
					
					);
					$this->view->sent2_style=$sent2;
				}
				else
				{
					if((isset($_POST['lp_id'])) && $_POST['lp_id']!='')
					{
							$sent2_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$_POST['lp_id'].'" and lps_lp_element="2"','fetch');
							$this->view->sent2_style=$sent2_style;
					}
				}
				
				
				if((isset($_POST['lps_font3'])) || (isset($_POST['lps_font_size3'])) ||(isset($_POST['lps_background3'])) ||(isset($_POST['lps_font_color3'])) )
				{
					$sent3=array(
					
						'lps_font'=>$_POST['lps_font3'],
						'lps_font_size'=>$_POST['lps_font_size3'],
						'lps_background'=>$_POST['lps_background3'],
						'lps_font_color'=>$_POST['lps_font_color3'],
					
					);
					$this->view->button_style=$sent3;
				}
				else
				{
					if((isset($_POST['lp_id'])) &&  $_POST['lp_id']!='')
					{
							$button_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$_POST['lp_id'].'" and lps_lp_element="3"','fetch');
							$this->view->button_style=$button_style;
					}
				}
				
				
		$this->view->title1=$_POST['lp_c_sent1'];
		$this->view->title2=$_POST['lp_c_sent2'];
	//	$this->view->share_image=$bg_image_data['pt_thumb'];
		$form = new Application_Form_Customhomevalue();
		$form->step1();
		
		$this->view->form=$form;
 	}
	
	public function step2Action(){
		global $objSession; 
		$lp_id = $this->_getParam('lp_id');
		if($lp_id!=''){
			$join=array('users','user_id=lp_user_id','left',array('user_full_name','user_email','user_company_name','user_phone','user_lead_notify','user_license_notify',
					'user_license_number'));
			$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch',array('fields'=>array('lp_name','lp_pt_id','lp_url','lp_bg_image','lp_c_sent1','lp_c_sent2',"lp_pixel_code")),array(0=>$join));
			$sent1_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="1"','fetch');
			$button_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="3"','fetch');
			$this->view->sent1_style=$sent1_style;
			$this->view->button_style=$button_style;
			$pt_id=$page_data['lp_pt_id'];
			$page_title1=$page_data['lp_c_sent1'];
			$page_title2=$page_data['lp_c_sent2'];
		}
		else
		{
			$pt_id=5;
			$page_title1="DON'T LEAVE MONEY ON THE TABLE!";
			$page_title2="Big brand online estimators can be off by 10-20%. Get your TRUE home value here.";
		}
		if(!isset($_SESSION['homevalue']['homeval_address']) || $_SESSION['homevalue']['homeval_address']=='')
		{
			$this->_redirect($page_data['lp_url'].'-1');
		}
		
 		$this->_helper->layout()->setLayout('landinglayout');
		$bg_image_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_background','pt_type')));
		if(!empty($page_data) && $page_data['lp_bg_image']!='')
		{
					$this->view->bg_image=$page_data['lp_bg_image'];
		}
		else
		{
					$this->view->bg_image=$bg_image_data['pt_background'];
		}
		
		$this->view->pixel_code=$page_data['lp_pixel_code'];
		$this->view->title1=$page_title1;
		$this->view->title2=$page_title2;
		$form = new Application_Form_Customhomevalue();
		$form->step2();
		if ($this->getRequest()->isPost()) { // Post Form Data
            $posted_data = $this->getRequest()->getPost();
            if ($form->isValid($this->getRequest()->getPost())) {
                $received_data               = $form->getValues();
                //prd($received_data);
                //prd($received_data);
                if ($_SESSION['inserted_id']!= '') {
                    $inserted_data = $this->modelStatic->Super_Insert("page_leads", $received_data,'pl_id="'.$_SESSION['inserted_id'].'"');
                    //prd($inserted_data);
                    /* Send Email to user */
					if($page_data['user_license_notify']==1)
					$license='License Number '.$page_data['user_license_number'].'';
					else
					$license='';
                    $email_data    = array(
                        'user_email' => $received_data['pl_email'],
                        'user_name' => $received_data['pl_name'],
                        'sender_email' => $page_data['user_email'],
                        'sender_name' => $page_data['user_full_name'],
                        'sender_phone' => $page_data['user_phone'],
                        'sender_company' => $page_data['user_company_name'],
						'license_no'=>$license
                    );
                    $this->modelEmail->sendEmail('seller_home_value_client', $email_data);
                    /* Send Email to user */
					//prd("cvbvbn");
                    $this->_redirect($page_data['lp_url'] . '-3');
                }
            } else {
                $objSession->errorMsg = "Please check the information again!";
            }
            
        } // End Post Form
		
		$this->view->form=$form;
 	}
	
	public function step3Action(){
		global $objSession; 
		//prd("xcvxc");
		$this->_helper->layout()->setLayout('landinglayout');
		$lp_id = $this->_getParam('lp_id');
		
		if($lp_id!=''){
$page_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch',array('fields'=>array('lp_name','lp_pt_id','lp_url','lp_bg_image','lp_c_sent1','lp_c_sent2','lp_pixel_code')));			$pt_id=$page_data['lp_pt_id'];
			$page_title1=$page_data['lp_c_sent1'];
			$page_title2=$page_data['lp_c_sent2'];
			$this->view->pixel_code=$page_data['lp_pixel_code'];
			$sent1_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="1"','fetch');
			$this->view->sent1_style=$sent1_style;
		}
		else
		{
			$pt_id=5;
			$page_title1="DON'T LEAVE MONEY ON THE TABLE!";
			$page_title2="Big brand online estimators can be off by 10-20%. Get your TRUE home value here.";
		}
		if(!isset($_SESSION['inserted_id']))
		{
			$this->_redirect($page_data['lp_url'].'-1');
		}
		
		$this->view->title1=$page_title1;
		$this->view->title2=$page_title2;
		$bg_image_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_background')));
			if(!empty($page_data) && $page_data['lp_bg_image']!='')
		{
					$this->view->bg_image=$page_data['lp_bg_image'];
		}
		else
		{
					$this->view->bg_image=$bg_image_data['pt_background'];
		}
 		unset($_SESSION['homevalue']);
		unset($_SESSION['inserted_id']);
 	}
	
	
}

