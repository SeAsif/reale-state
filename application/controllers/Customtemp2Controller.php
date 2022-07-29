<?php
class Customtemp2Controller extends Zend_Controller_Action
{
    private $modelUser, $modelContent;
    
    public function init()
    {
        $this->modelStatic = new Application_Model_Static();
        $this->modelEmail  = new Application_Model_Email();
		//prd("vbvbn");
        global $objSession;
        $lp_id = $this->_getParam('lp_id');
		
        $type  = $this->_getParam('type');
        if ($lp_id != '') {
            $page_data = $this->modelStatic->Super_Get("landing_pages", 'lp_id="' . $lp_id . '"', 'fetch', array(
                'fields' => array(
                    'lp_status',
                    'lp_pt_id'
                )
            ));
            
            if (($page_data['lp_status'] == 0 && $type == '') || ($page_data['lp_pt_id'] != 8)) {
                $this->_redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
   
    
    public function step1Action()
    {
        global $objSession;
        //prd("vvbn");
		//$page_data=array();
		
        $this->_helper->layout()->setLayout('landinglayout');
        $lp_id= $this->_getParam('lp_id');
		$pop_ip_data=array();
        $this->view->lp_id = $lp_id;
		
        if ($lp_id != '') {
            $join       = array(
                'users',
                'user_id=lp_user_id',
                'left',
                array(
                    'user_full_name',
                    'user_email',
                    'user_company_name',
                    'user_phone',
                    'user_lead_notify',
					'user_license_notify',
					'user_license_number',
                )
            );
            $page_data  = $this->modelStatic->Super_Get("landing_pages", 'lp_id="' . $lp_id . '"', 'fetch', array(), array(
                0 => $join
            ));
            $pt_id      = $page_data['lp_pt_id'];
            $page_title = $page_data['lp_name'];
			//button_color
			$button_style=$this->modelStatic->Super_Get("landing_pages_styles", 'lps_lp_id="' . $lp_id . '"', 'fetch');
        } else {
            $pt_id      = 8;
            $page_title = 'Custom 2';
        }
		
        $bg_image_data = $this->modelStatic->Super_Get("page_templates", 'pt_id="' . $pt_id . '"', 'fetch');
		
		$this->view->bg_image_data=$bg_image_data;
		$this->view->page_data=@$page_data;
		if(!empty($page_data)){		
			if(!empty($page_data['lp_bg_image'])){	
				$this->view->image_main=$page_data['lp_bg_image'];
			}
			else{
				$this->view->image_main=$bg_image_data['pt_background'];
			}
			$this->view->title1=$page_data['lp_headline'];
			$this->view->subheading=$page_data['lp_subheadline'];
			$this->view->video_link=$page_data['lp_video_link'];
			$po=strpos($page_data['lp_video_link'], 'youtu');
			if($po==true){//youtube
				$this->view->video_type="1";
			}else{//vimeo
				$this->view->video_type="2";	
			}			
			$this->view->button_text=$page_data['lp_button_text'];
			$this->view->popup_text=$page_data['lp_popup_text'];
			$this->view->pixel_code=$page_data['lp_pixel_code'];
			$this->view->popup_btn_text=$page_data['lp_popup_btn_text'];
			$this->view->btn_color=$button_style['lps_background'];		
		}
		else
		{
				$this->view->image_main=$bg_image_data['pt_background'];
				$this->view->title1="Insert your headline text here.";
				$this->view->subheading="Insert your subheadline text here.";
				//$this->view->video_link="https://player.vimeo.com/video/201208503";
				//$this->view->video_type="2";
				$this->view->video_link=HTTP_IMG_PATH.'/HypeVideo.mp4';
				$this->view->button_text="Insert your button text here.";
				$this->view->popup_text="Insert your popup headline text here.";
				$this->view->popup_btn_text="Insert your popup button text here.";
				$this->view->btn_color="#e67e22";
		}
		$images = $this->modelStatic->Super_Get("landing_pages_images", 'lpi_lp_id="' . $lp_id . '"', 'fetchall');
		$this->view->images=$images;
        $this->view->title       = $page_title;
        $form                    = new Application_Form_Custompage1();
        $form->step1();
        if ($this->getRequest()->isPost()) { // Post Form Data            
            $posted_data = $this->getRequest()->getPost();            
        if ($form->isValid($this->getRequest()->getPost()))
			{
				$received_data  = $form->getValues();							
				$received_data['pl_lp_id']=$lp_id;
				$received_data['pl_added']=date('Y-m-d H:i:s');
				if($received_data['pl_lp_id']!=''){
				if((isset($_SESSION['inserted_id'])) && ($_SESSION['inserted_id']!='')){
                    	$inserted_data = $this->modelStatic->Super_Insert("page_leads", $received_data,'pl_id="'.$_SESSION['inserted_id'].'"');
						$inserted_data->inserted_id=$_SESSION['inserted_id'] ;
					}
					else{
					$inserted_data = $this->modelStatic->Super_Insert("page_leads", $received_data);
					}					
					if($page_data['user_license_notify']==1)
				$license='License Number '.$page_data['user_license_number'].'';
				else
				$license='';
				/* Send Email to user */
				$email_data=array(
				
					'user_email'=>$received_data['pl_email'],
					'user_full_name'=>$received_data['pl_name'],
					'sender_email'=>$page_data['user_email'],
					'sender_name'=>$page_data['user_full_name'],
					'sender_phone'=>$page_data['user_phone'],
					'sender_company'=>$page_data['user_company_name'],
					'license_no'=>$license
				);
				
				//$this->modelEmail->sendEmail('custom_homebuyer_mistakes_client',$email_data);
				/* Send Email to user */
				
				/* Send Email to Agent */
				$email_data=array(
				
					'user_email'=>$page_data['user_email'],
					'user_full_name'=>$page_data['user_full_name'],
					'page_name'=>$page_data['lp_url']
				);
				//$this->modelEmail->sendEmail('custom_homebuyer_mistakes_agent',$email_data);
				if($page_data['user_lead_notify']==1){
					$url=SITE_HTTP_URL.'/user/login';
					$msg="You have received a (".$bg_image_data['pt_type']." lead) from your (".$page_data['lp_url'].") landing page. \n
 Log into the ALG system ASAP and follow up:".$url;
					sendsms($page_data['user_phone'],$msg);
				}
				/* Send Email to Agent */
				//$_SESSION['inserted_id']=$inserted_data->inserted_id;
				$this->_redirect($page_data['lp_redirect_link']);
				}
			}
			else
			{
				$objSession->errorMsg="Please check the information again!";
			}
            
        } // End Post Form
        
        $this->view->form = $form;
    }
 
    
}
