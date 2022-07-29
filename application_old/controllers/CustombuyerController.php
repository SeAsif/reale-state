<?php
class CustombuyerController extends Zend_Controller_Action
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
            //prd($page_data);
            if (($page_data['lp_status'] == 0 && $type == '') || ($page_data['lp_pt_id'] != 6)) {
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
            $page_data  = $this->modelStatic->Super_Get("landing_pages", 'lp_id="' . $lp_id . '"', 'fetch', array(
                'fields' => array(
                    'lp_name',
                    'lp_pt_id',
                    'lp_url',
					'lp_c_sent1',
					'lp_bg_image',
					'lp_redirect_link',
					'lp_beds',
					'lp_baths',
					'lp_square_feet',
					'lp_city',
                )
            ), array(
                0 => $join
            ));
            $pt_id      = $page_data['lp_pt_id'];
            $page_title = $page_data['lp_name'];
        } else {
            $pt_id      = 6;
            $page_title = 'SAN JOSE';
        }
        $bg_image_data           = $this->modelStatic->Super_Get("page_templates", 'pt_id="' . $pt_id . '"', 'fetch', array(
            'fields' => array(
                'pt_background',
                'pt_thumb',
                'pt_type'
            )
        ));
		$this->view->page_data=@$page_data;
		if(!empty($page_data)){
		
			$this->view->image_main=$page_data['lp_bg_image'];
			$this->view->title1=$page_data['lp_c_sent1'];
			$this->view->lp_beds=$page_data['lp_beds'];
			$this->view->lp_baths=$page_data['lp_baths'];
			$this->view->lp_square_feet=$page_data['lp_square_feet'];
			$this->view->lp_city=$page_data['lp_city'];
		}
		else
		{
				$this->view->image_main="temp.png?2343";
				$this->view->title1="JUST LISTED IN THE PERFECT SAN JOSE NEIGHBORHOOD";
				$this->view->lp_beds=5;
				$this->view->lp_baths=25;
				$this->view->lp_square_feet=2400;
				$this->view->lp_city="San Jose, CA";
		}
		$images = $this->modelStatic->Super_Get("landing_pages_images", 'lpi_lp_id="' . $lp_id . '"', 'fetchall');
		$this->view->images=$images;
        $this->view->title       = $page_title;
        $form                    = new Application_Form_Custombuyer();
        $form->step1();
        if ($this->getRequest()->isPost()) { // Post Form Data
            
            
            $posted_data = $this->getRequest()->getPost();
            
            
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
					
					//prd($page_data);
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
				//prd($email_data);
				$this->modelEmail->sendEmail('custom_homebuyer_mistakes_client',$email_data);
				/* Send Email to user */
				
				/* Send Email to Agent */
				$email_data=array(
				
					'user_email'=>$page_data['user_email'],
					'user_full_name'=>$page_data['user_full_name'],
					'page_name'=>$page_data['lp_url']
				);
				$this->modelEmail->sendEmail('custom_homebuyer_mistakes_agent',$email_data);
				if($page_data['user_lead_notify']==1){
					$url=SITE_HTTP_URL.'/user/login';
					$msg="You have received a (".$bg_image_data['pt_type']." lead) from your (".$page_data['lp_url'].") landing page. \n
 Log into the ALG system ASAP and follow up:".$url;
					sendsms($page_data['user_phone'],$msg);
				}
				/* Send Email to Agent */
				$_SESSION['inserted_id']=$inserted_data->inserted_id;
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
