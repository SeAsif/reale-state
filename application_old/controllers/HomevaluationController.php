<?php
class HomevaluationController extends Zend_Controller_Action
{
    private $modelUser, $modelContent;
    
    public function init()
    {
        $this->modelStatic = new Application_Model_Static();
        $this->modelEmail  = new Application_Model_Email();
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
            if (($page_data['lp_status'] == 0 && $type == '') || ($page_data['lp_pt_id'] != 2)) {
                $this->_redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
   
    
    public function step1Action()
    {
        global $objSession;
        //prd("vvbn");
        $this->_helper->layout()->setLayout('landinglayout');
        $lp_id= $this->_getParam('lp_id');
		$pop_ip_data=array();
        $this->view->lp_id = $lp_id;
        if ($lp_id != '') {
			$pop_ip_data=$this->modelStatic->Super_Get("popup_ips",'pi_lp_id="'.$lp_id.'" and pi_ip_address="'.$_SERVER['REMOTE_ADDR'].'" ');
            $join       = array(
                'users',
                'user_id=lp_user_id',
                'left',
                array(
                    'user_full_name',
                    'user_email',
                    'user_company_name',
                    'user_phone',
                    'user_lead_notify'
                )
            );
            $page_data  = $this->modelStatic->Super_Get("landing_pages", 'lp_id="' . $lp_id . '"', 'fetch', array(
                'fields' => array(
                    'lp_name',
                    'lp_pt_id',
                    'lp_url'
                )
            ), array(
                0 => $join
            ));
            $pt_id      = $page_data['lp_pt_id'];
            $page_title = $page_data['lp_name'];
        } else {
            $pt_id      = 2;
            $page_title = 'SAN JOSE';
        }
        $bg_image_data           = $this->modelStatic->Super_Get("page_templates", 'pt_id="' . $pt_id . '"', 'fetch', array(
            'fields' => array(
                'pt_background',
                'pt_thumb',
                'pt_type'
            )
        ));
		$this->view->pop_ip_data=$pop_ip_data;
        $this->view->bg_image    = $bg_image_data['pt_background'];
        $this->view->title       = $page_title;
        $this->view->share_image = $bg_image_data['pt_thumb'];
        $form                    = new Application_Form_Homevalue();
        $form->step1();
        if ($this->getRequest()->isPost()) { // Post Form Data
            
            
            $posted_data = $this->getRequest()->getPost();
            
          // prd($posted_data); 
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
									'user_full_name' => $page_data['user_full_name'],
									'page_name'=>$page_data['lp_url']
								);
								$this->modelEmail->sendEmail('seller_home_value_agent', $email_data);
								if ($page_data['user_lead_notify'] == 1) {
									$url = SITE_HTTP_URL . '/user/login';
									$msg = "You have received a (" . $bg_image_data['pt_type'] . " lead) from your (" . $page_data['lp_url'] . ") landing page. \n
			 Log into the ALG system ASAP and follow up:" . $url;
									sendsms($page_data['user_phone'], $msg);
								}
					}
                    $_SESSION['inserted_id'] = $inserted_data->inserted_id;
					
					$this->_redirect($page_data['lp_url'] . '-2');
                    //prd("xcxc");
                }
               // prd($received_data);
                
            } else {
                $objSession->errorMsg = "Please check the information again!";
            }
            
        } // End Post Form
        
        $this->view->form = $form;
    }
    
    public function step2Action()
    {
        global $objSession;
        $lp_id = $this->_getParam('lp_id');
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
                    'user_lead_notify'
					,'user_license_notify',
					'user_license_number'
                )
            );
            $page_data  = $this->modelStatic->Super_Get("landing_pages", 'lp_id="' . $lp_id . '"', 'fetch', array(
                'fields' => array(
                    'lp_name',
                    'lp_pt_id',
                    'lp_url'
                )
            ), array(
                0 => $join
            ));
			//prd($page_data);
            $pt_id      = $page_data['lp_pt_id'];
            $page_title = $page_data['lp_name'];
        } else {
            $pt_id      = 2;
            $page_title = 'SAN JOSE';
        }
        if ((!isset($_SESSION['inserted_id'])) || ($_SESSION['inserted_id']=='')) {
            $this->_redirect($page_data['lp_url'] . '-1');
        }
        
        $this->_helper->layout()->setLayout('landinglayout');
        $bg_image_data        = $this->modelStatic->Super_Get("page_templates", 'pt_id="' . $pt_id . '"', 'fetch', array(
            'fields' => array(
                'pt_background',
                'pt_type'
            )
        ));
        $this->view->bg_image = $bg_image_data['pt_background'];
        $this->view->title    = $page_title;
        $form                 = new Application_Form_Homevalue();
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
					//prd($page_data);
                    $this->modelEmail->sendEmail('seller_home_value_client', $email_data);
                    /* Send Email to user */
                    $this->_redirect($page_data['lp_url'] . '-3');
                }
            } else {
                $objSession->errorMsg = "Please check the information again!";
            }
            
        } // End Post Form
        
        $this->view->form = $form;
    }
    
    public function step3Action()
    {
        global $objSession;
        //prd("xcvxc");
        $this->_helper->layout()->setLayout('landinglayout');
        $lp_id = $this->_getParam('lp_id');
        
        if ($lp_id != '') {
            $page_data  = $this->modelStatic->Super_Get("landing_pages", 'lp_id="' . $lp_id . '"', 'fetch', array(
                'fields' => array(
                    'lp_name',
                    'lp_pt_id',
                    'lp_url'
                )
            ));
            $pt_id      = $page_data['lp_pt_id'];
            $page_title = $page_data['lp_name'];
        } else {
            $pt_id      = 2;
            $page_title = 'SAN JOSE';
        }
        if (!isset($_SESSION['inserted_id'])) {
            $this->_redirect($page_data['lp_url'] . '-1');
        }
        
        $this->view->title    = $page_title;
        $bg_image_data        = $this->modelStatic->Super_Get("page_templates", 'pt_id="' . $pt_id . '"', 'fetch', array(
            'fields' => array(
                'pt_background'
            )
        ));
        $this->view->bg_image = $bg_image_data['pt_background'];
        unset($_SESSION['inserted_id']);
		 unset($_SESSION['homevalue']);

    }
    
    
}
