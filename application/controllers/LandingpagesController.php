<?php
class LandingpagesController extends Zend_Controller_Action
{
  	private $modelUser ,$modelContent; 
	 
	public function init(){
 		$this->modelStatic = new Application_Model_Static();
 	}
	public function indexAction(){
		global $objSession; 
		$this->view->show = "landing_page" ; 
		$buyer_temp=$this->modelStatic->Super_Get("page_templates",'pt_type="buyer"','fetchall');
		$seller_temp=$this->modelStatic->Super_Get("page_templates",'pt_type="seller"','fetchall');
		$custom_temp=$this->modelStatic->Super_Get("page_templates",'pt_type="custom"','fetchall');
	 $this->view->custom_temp=$custom_temp;
		// echo "<pre>"; print_r($custom_temp); exit;
		$this->view->buyer_temp=$buyer_temp;
		$this->view->seller_temp=$seller_temp;
		$this->view->custom_temp=$custom_temp;
		
	}
	public function pdfAction(){
		$imgPath = HTTP_SITEIMG_PATH.'/pdfimage.jpg';
		putenv('GDFONTPATH=' . dirname(__FILE__));
			$font = 'WanderlustLetters-Regular'; 
			
			DEFINE("TTF_TEXT2", '"Why do I look like this?"' );
			DEFINE("TTF_TEXT", "asked  closed the night time book." );
			$textImgPath=time();
			$saveTo=HTTP_SITEIMG_PATH.'/'.$textImgPath.'.jpg';

			// Create Image From Existing File
			$jpg_image = imagecreatefromjpeg($imgPath);
			
			// Allocate A Color For The Text
			$white = imagecolorallocate($jpg_image, 255, 255, 255);
			$black = imagecolorallocate ($jpg_image, 0, 0, 0);
			
			// Set Path to Font File
			$font_path = $font;
			
			// Print Text On Image
			imagettftext($jpg_image, 150, 0, 800,200, $white, $font, TTF_TEXT);
			imagettftext($jpg_image, 150, 0, 850, 380, $white, $font, TTF_TEXT2);
			
			// Send Image to Browser
			header('Content-type: image/jpeg');
			imagejpeg($jpg_image,$saveTo,100);
			
			$pdf=HTTP_SITEIMG_PATH.'/'.$textImgPath.'.jpg';
			$baseName=explode(".jpg",$staticSlideData[$fieldToGet]);
			$baseName123=$textImgPath;
			
			$save_to1=HTTP_SITEIMG_PATH.'/'.$baseName123.'.pdf';
			$pdfName=$baseName123.'.pdf';
			
			$im = new imagick($pdf);
			$im->setResolution(416,412.5);
			$im->setImageFormat('jpg');
			//$im = $im->flattenImages();
			$im = $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
			$im->writeImages($save_to1, false);
			
	die;
		
		
		
	}
	public function checkprofileurlAction(){

		$owner_profile_url = strtolower($this->_getParam('lp_url'));
		if($owner_profile_url==''){
			$owner_profile_url = strtolower($this->_getParam('lp_name'));
		}		
		$lp_id = strtolower($this->_getParam('lp_id'));
		$owner_profile_url1=encodeProfileUrl($owner_profile_url);		
		$exclude = strtolower($this->_getParam('exclude'));		
		$user_id = false ;
		if(!empty($exclude)){
		$user = $this->view->user;
		$user_id =$user->user_id;		
		}
		if(empty($lp_id) || $lp_id=='undefined')
		$email = $this->modelStatic->Super_Get("landing_pages",'LOWER(lp_url)="'.strtolower($owner_profile_url1).'"',"fetchAll");
		else
		$email = $this->modelStatic->Super_Get("landing_pages",'lp_id!="'.$lp_id.'" and LOWER(lp_url)="'.strtolower($owner_profile_url1).'"',"fetchAll");
		$rev = $this->_getParam("rev");		
		if(count($email)>0)
		echo json_encode("`$owner_profile_url` already exists , please enter any other page url ");
		else
		echo json_encode("true");
		exit();
 }
	
	public function exportAction(){
		global $objSession; 
		$this->view->page_title='SEARCH ALL LEADS BY';
		$this->view->show = "allleads" ; 
		$form = new Application_Form_StaticForm();
		$form->lead_search_form();
		
		$lp_id=$this->_getParam('lp_id');
		if(isset($_SESSION['lp_id']) && $_SESSION['lp_id']!='')
		{
			$_REQUEST['lp_id']=$_SESSION['lp_id'];
			
			unset($_SESSION['lp_id']);
			unset($_REQUEST['lp_id']);
		}
		if($lp_id!='')
		{
			$_SESSION['lp_id']=$lp_id;
			$_REQUEST['lp_id']=$lp_id;
			//$this->_redirect('landingpages/allleads');
		}
		
		$result=$this->modelStatic->GetLeadsExel($_REQUEST,$this->view->user->user_id)->query()->fetchAll();
		//prd($result);
		$type_file='Leads('.date("Y-m-d").')_';
		 if(!empty($result)) 
 		 {
 			  printExcel($result,$type_file);
 			  exit();
 		 }
		
	
	}
	
	public function allleadsAction(){
		global $objSession; 
		$this->view->page_title='SEARCH ALL LEADS BY';
		$this->view->show = "allleads" ; 
		$form = new Application_Form_StaticForm();
		$form->lead_search_form();
		
		$lp_id=$this->_getParam('lp_id');
		if(isset($_SESSION['lp_id']) && $_SESSION['lp_id']!='')
		{
			$_REQUEST['lp_id']=$_SESSION['lp_id'];
			
			unset($_SESSION['lp_id']);
			unset($_REQUEST['lp_id']);
		}
		if($lp_id!='')
		{
			$_SESSION['lp_id']=$lp_id;
			$_REQUEST['lp_id']=$lp_id;
			//$this->_redirect('landingpages/allleads');
		}
		//prn("df".$_REQUEST['lp_id']);
		//prn("g".$_SESSION['lp_id']);
		$temp=array('page_templates','lp_pt_id=pt_id','left',array('pt_title'));
		if(isset($_REQUEST['lp_id']))
		$page_title=$this->modelStatic->Super_get("landing_pages","lp_id='".$_REQUEST['lp_id']."'",'fetch',array('fields'=>array('lp_name')),array(0=>$temp));
		if(isset($page_title['pt_title']) && ($page_title['pt_title']!=''))
		$this->view->page_title='All Leads For '.$page_title['pt_title'].'('.$page_title['lp_name'].')';
		else
		$this->view->page_title='SEARCH ALL LEADS BY';
		$form->populate($_REQUEST);
		$this->view->form=$form;
		$result=$this->modelStatic->GetLeads($_REQUEST,$this->view->user->user_id);
		$page=1;
		$page=$this->_getParam('page');
		if(!isset($_REQUEST['record_per_page']))
		$_REQUEST['record_per_page']=10;
		$paginator=$this->pagination($result,$page,$_REQUEST['record_per_page']);
		$this->view->paginator=$paginator;
		$this->view->search_array=$_REQUEST;
	
	}
	public function pagination($searchDataQuery,$page,$record_per_page)
	{
		$adapter = new Zend_Paginator_Adapter_DbSelect($searchDataQuery);
		$paginator = new Zend_Paginator($adapter);
		$page =$page;
		$this->view->page=$page;
		
		$rec_counts = $this->_getParam('itemcountpage');
		if(!$rec_counts){
			if(isset($record_per_page))
			$rec_counts =$record_per_page;
			else
			$rec_counts =10;
			
		}
		$paginator->setItemCountPerPage($rec_counts);
		$paginator->setCurrentPageNumber($page);
		$paginator->setPageRange(5);
		$paginationControl = new Zend_View_Helper_PaginationControl($paginator, 'sliding', 'search-pagination-control.phtml');
		$this->view->paginationControl=$paginationControl;
		return $paginator;
	}
	
	public function addAction(){
		global $objSession; 
		$this->view->show = "landing_page" ; 
		$pt_id=$this->_getParam('pt_id');
		$temp_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_title','pt_id','pt_thumb')));
		$this->view->temp_data=$temp_data;
	
		$form = new Application_Form_Landingpages();
		
		$form->createpage();
		
		if ($this->getRequest()->isPost()){ // Post Form Data
 			$posted_data  = $this->getRequest()->getPost();
			if ($form->isValid($this->getRequest()->getPost()))
			{
				$received_data  = $form->getValues();
				$data=array(
					'lp_pt_id'=>$pt_id,
					'lp_user_id'=>$this->view->user->user_id,
					'lp_name'=>$received_data['lp_name'],
					'lp_added'=>date("Y-m-d H:i:s"),
					'lp_url'=>encodeProfileUrl($received_data['lp_url']),
					'lp_updated'=>date("Y-m-d H:i:s"),
					'lp_pixel_code'=>$received_data['lp_pixel_code'],
				);
				$this->modelStatic->Super_Insert("landing_pages",$data);
				$objSession->successMsg="Your page is published sucessfully!";
				$this->_redirect('landing-pages');
			}
			else
			{
				$objSession->errorMsg="Please check the information again!";
			}
 			
 		} // End Post Form
		
		$this->view->form=$form;
		
	}
	
	
	public function addcustompageAction(){
		global $objSession; 
		
		$this->view->show = "landing_page" ; 
		$pt_id=$this->_getParam('pt_id');
		$temp_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_title','pt_id','pt_thumb')));
		$this->view->temp_data=$temp_data;
		$form = new Application_Form_Landingpages();
		$form->createpage_custom();
		$newform = new Application_Form_Landingpages();
		$newform->style_form();
		
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
			if ($form->isValid($this->getRequest()->getPost()))
			{
				if($_FILES['pt_background']['name']!='')
				{
					$is_uploaded = $this->_handle_uploaded_image(TEMPLATE_IMAGES);	
				}
				$received_data  = $form->getValues();
				if(trim($received_data['lp_c_sent1'])=='')
				{
					$received_data['lp_c_sent1']="DON'T LEAVE MONEY ON THE TABLE!";
				}
				if(trim($received_data['lp_c_sent2'])=='')
				{
					$received_data['lp_c_sent2']="Big brand online estimators can be off by 10-20%. Get your TRUE home value here.";
				}
				$data=array(
					'lp_pt_id'=>$pt_id,
					'lp_user_id'=>$this->view->user->user_id,
					'lp_added'=>date("Y-m-d H:i:s"),
					'lp_url'=>encodeProfileUrl($received_data['lp_url']),
					'lp_updated'=>date("Y-m-d H:i:s"),
					'lp_c_sent1'=>$received_data['lp_c_sent1'],
					'lp_c_sent2'=>$received_data['lp_c_sent2'],
					'lp_pixel_code'=>$received_data['lp_pixel_code']
				
				);
				if(is_object($is_uploaded) and $is_uploaded->success){
					
					$data['lp_bg_image']=$is_uploaded->media_path;
				}
				$inserted=$this->modelStatic->Super_Insert("landing_pages",$data);
				
				if((isset($posted_data['lps_font1'])) || (isset($posted_data['lps_font_size1'])) ||(isset($posted_data['lps_font_style1'])) ||(isset($posted_data['lps_font_color1'])) )
				{
					$sent1=array(
					
						'lps_lp_id'=>$inserted->inserted_id,
						'lps_lp_element'=>1,
						'lps_font'=>$posted_data['lps_font1'],
						'lps_font_size'=>$posted_data['lps_font_size1'],
						'lps_font_style'=>$posted_data['lps_font_style1'],
						'lps_font_color'=>$posted_data['lps_font_color1'],
					
					);
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent1);
				}
				
				if((isset($posted_data['lps_font2'])) || (isset($posted_data['lps_font_size2'])) ||(isset($posted_data['lps_font_style2'])) ||(isset($posted_data['lps_font_color2'])) )
				{
					$sent2=array(
					
						'lps_lp_id'=>$inserted->inserted_id,
						'lps_lp_element'=>2,
						'lps_font'=>$posted_data['lps_font2'],
						'lps_font_size'=>$posted_data['lps_font_size2'],
						'lps_font_style'=>$posted_data['lps_font_style2'],
						'lps_font_color'=>$posted_data['lps_font_color2'],
					
					);
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent2);
				}
				
				if((isset($posted_data['lps_font3'])) || (isset($posted_data['lps_font_size3'])) ||(isset($posted_data['lps_background3'])) ||(isset($posted_data['lps_font_color3'])) )
				{
					$sent3=array(
					
						'lps_lp_id'=>$inserted->inserted_id,
						'lps_lp_element'=>3,
						'lps_font'=>$posted_data['lps_font3'],
						'lps_font_size'=>$posted_data['lps_font_size3'],
						'lps_background'=>$posted_data['lps_background3'],
						'lps_font_color'=>$posted_data['lps_font_color3'],
					
					);
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent3);
				}
				$objSession->successMsg="Your page is published sucessfully!";
				$this->_redirect('landing-pages');
			}
			else
			{
				$objSession->errorMsg="Please check the information again!";
			}
 			
 		} // End Post Form
		
		$this->view->form=$form;
		$this->view->newform=$newform;
		
	}
	
	public function uploadimageAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$options = array();
		
		if(isset($_GET['file']) && $_GET['file'] != ""){
		}
		$options['script_url'] = SITE_HTTP_URL."/landingpages/uploadimage";
		$path=ROOT_PATH."/public/resources/page_templates/gallery_".$this->view->user->user_id."/";
		//prd($path);
		if(!is_dir($path))
		{
			mkdir($path,0755);
		}
		$options['upload_dir'] = $path;
		$options['max_number_of_files']=$_SESSION['allowed'];
		$options['upload_url'] = SITE_HTTP_URL."/public/resources/page_templates/gallery_".$this->view->user->user_id."/";
		$imageUpload = new Application_Plugin_UploadHandler($options);
		exit;
	}
	public function updatedelcountAction(){
		$_SESSION['allowed']=$_SESSION['allowed']+1;
		exit;
	}
	
	public function addnewbuyertempAction(){
		global $objSession; 
		$_SESSION['allowed']=5;
		$this->view->show = "landing_page" ; 
		$pt_id=$this->_getParam('pt_id');
		$lp_id=$this->_getParam('lp_id');
		$pop_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch');
		if(!empty($pop_data))
		{
			$pt_id=$pop_data['lp_pt_id'];
			if($pop_data['lp_user_id']!=$this->view->user->user_id)
			$this->_redirect('landing-pages');
			
		}
		else if(empty($pop_data) && $lp_id!='')
		{
			$objSession->errorMsg="You are accesssing wrong url";
			$this->_redirect('landing-pages');
		}
		
		$temp_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch',array('fields'=>array('pt_title','pt_id','pt_thumb')));
		$this->view->temp_data=$temp_data;
		$form = new Application_Form_Landingpages();
		$form->createpage_custom_buyer();
		if(!empty($pop_data)){
			$form->populate($pop_data);
		}
		$this->view->pop_data=$pop_data;
		$this->view->pop_photos=$this->modelStatic->Super_Get("landing_pages_images",'lpi_lp_id="'.$lp_id.'"','fetchall');
		$_SESSION['allowed']=$_SESSION['allowed']-count($this->view->pop_photos);
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
			 $path =ROOT_PATH.'/public/resources/page_templates/gallery_'.$this->view->user->user_id.'/';	
				 $files = scandir($path);
				 $array=array();
				 foreach ($files as $file) 
				 {
						if($file!='.' && $file!='..' && ((strpos($file,"."))))
						{ 
							if($file!='thumbnail')
							{
								
								
								$newname=time().".".$file;
								array_push($array,$newname);
								if(file_exists($path."/".$file))
								{
								  copy($path."/".$file,TEMPLATE_IMAGES."/".$newname);
								  $thumb_config = array("source_path"=>TEMPLATE_IMAGES,"name"=> $newname);
Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("destination_path"=>TEMPLATE_IMAGES."/87","crop"=>true ,"width"=>400,"height"=>400,"ratio"=>false)));

								}
							}
						}
					}
			
			
			if ($form->isValid($this->getRequest()->getPost()))
			{
				
				
				if($_FILES['lp_bg_image']['name']!='')
				{
					$is_uploaded = $this->_handle_uploaded_image(TEMPLATE_IMAGES);	
				}
				
				$received_data  = $form->getValues();
				if(is_object($is_uploaded) and $is_uploaded->success){
					
					$received_data['lp_bg_image']=$is_uploaded->media_path;
				}
				//prd($received_data);
				if($lp_id==''){
				$received_data['lp_user_id']=$this->view->user->user_id;
				$received_data['lp_pt_id']=$pt_id;
				$received_data['lp_added']=date("Y-m-d H:i:s");
				$received_data['lp_updated']=date("Y-m-d H:i:s");
				$is_insert=$this->modelStatic->Super_Insert("landing_pages",$received_data);
				}
				else
				{
					if(trim($received_data['lp_bg_image'])=='')
					{
						$received_data['lp_bg_image']=$pop_data['lp_bg_image'];
					}
					$this->modelStatic->Super_Insert("landing_pages",$received_data,'lp_id="'.$lp_id.'"');
					$is_insert->inserted_id=$lp_id;
						if(isset($_POST['delete_img']) && ltrim($_POST['delete_img'],",")!='')
							{
						
							$delete_gallery=$this->modelStatic->Super_Get("landing_pages_images","lpi_id IN (".ltrim($_POST['delete_img'],",").")","fetchall");
					
							foreach($delete_gallery as $del_img)
							{
								unlink(TEMPLATE_IMAGES."/".$del_img['api_name']);	
								if(file_exists(TEMPLATE_IMAGES."/87/".$del_img['lpi_lp_name']))
								{
									unlink(TEMPLATE_IMAGES."/87/".$del_img['lpi_lp_name']);
								}
								
								
							}
							$this->modelStatic->Super_Delete("landing_pages_images","lpi_id IN (".ltrim($_POST['delete_img'],",").")");
							 
							}	
					
				}
				if(!empty($array)){
							foreach($array as $k=>$v)
									{
												$car_insert=array();
												$car_insert=array('lpi_lp_id'=>$is_insert->inserted_id,
												'lpi_lp_name'=>$v,
												);
												$s=$this->modelStatic->Super_insert("landing_pages_images",$car_insert);
									}
									//prd("ccvb");
									DeleteDirfileupload(ROOT_PATH.'/public/resources/page_templates/gallery_'.$this->view->user->user_id);
							}
			
				$objSession->successMsg="Your page is published sucessfully!";
				$this->_redirect('landing-pages');
			}
			else
			{
				//prd($form->getErrors());
				$objSession->errorMsg="Please check the information again!";
				
			}
			
 			
 		} // End Post Form
		
		else
			  {
				  if(file_exists(ROOT_PATH.'/public/resources/page_templates/gallery_'.$this->view->user->user_id))
				  DeleteDirfileupload(ROOT_PATH.'/public/resources/page_templates/gallery_'.$this->view->user->user_id);
			  }
		
		$this->view->form=$form;
		
	}
	
	public function editcustompageAction(){
		global $objSession; 
		
		$this->view->show = "landing_page" ; 
		$lp_id=$this->_getParam('lp_id');
		$pop_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch');
		$temp_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pop_data['lp_pt_id'].'"','fetch',array('fields'=>array('pt_title','pt_id','pt_thumb')));
		$this->view->temp_data=$temp_data;
		if($pop_data['lp_bg_image']!='')
		$this->view->lp_bg_image=$pop_data['lp_bg_image'];
		$form = new Application_Form_Landingpages();
		$form->createpage_custom($lp_id);
		$newform = new Application_Form_Landingpages();
		$newform->style_form();
		$form->submitbtn->setLabel("Update Page");
		$form->populate($pop_data);
		$sent1_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="1"','fetch');
			$sent2_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="2"','fetch');
			$button_style=$this->modelStatic->Super_Get("landing_pages_styles",'lps_lp_id="'.$lp_id.'" and lps_lp_element="3"','fetch');
		
		$pop_data_inner=array(
					
						'lps_font1'=>$sent1_style['lps_font'],
						'lps_font_size1'=>$sent1_style['lps_font_size'],
						'lps_font_style1'=>$sent1_style['lps_font_style'],
						'lps_font_color1'=>$sent1_style['lps_font_color'],
						
						'lps_font2'=>$sent2_style['lps_font'],
						'lps_font_size2'=>$sent2_style['lps_font_size'],
						'lps_font_style2'=>$sent2_style['lps_font_style'],
						'lps_font_color2'=>$sent2_style['lps_font_color'],
						
						'lps_font3'=>$button_style['lps_font'],
						'lps_font_size3'=>$button_style['lps_font_size'],
						'lps_background3'=>$button_style['lps_background'],
						'lps_font_color3'=>$button_style['lps_font_color'],
					
		);	
		$newform->populate($pop_data_inner);
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
  			
			
			if ($form->isValid($this->getRequest()->getPost()))
			{
				if($_FILES['pt_background']['name']!='')
				{
					$is_uploaded = $this->_handle_uploaded_image(TEMPLATE_IMAGES);	
				}
				$received_data  = $form->getValues();
				if(trim($received_data['lp_c_sent1'])=='')
				{
					$received_data['lp_c_sent1']="DON'T LEAVE MONEY ON THE TABLE!";
				}
				if(trim($received_data['lp_c_sent2'])=='')
				{
					$received_data['lp_c_sent2']="Big brand online estimators can be off by 10-20%. Get your TRUE home value here.";
				}
				$data=array(
					'lp_url'=>encodeProfileUrl($received_data['lp_url']),
					'lp_updated'=>date("Y-m-d H:i:s"),
					'lp_c_sent1'=>$received_data['lp_c_sent1'],
					'lp_c_sent2'=>$received_data['lp_c_sent2'],
				
				);
				if(is_object($is_uploaded) and $is_uploaded->success){
					
					$data['lp_bg_image']=$is_uploaded->media_path;
				}
				$data['lp_updated']=date("Y-m-d H:i:s");
				
				$this->modelStatic->Super_Insert("landing_pages",$data,'lp_id="'.$lp_id.'"');
				
					if((isset($posted_data['lps_font1'])) || (isset($posted_data['lps_font_size1'])) ||(isset($posted_data['lps_font_style1'])) ||(isset($posted_data['lps_font_color1'])) )
				{
					$sent1=array(
					
						'lps_lp_id'=>$lp_id,
						'lps_lp_element'=>1,
						'lps_font'=>$posted_data['lps_font1'],
						'lps_font_size'=>$posted_data['lps_font_size1'],
						'lps_font_style'=>$posted_data['lps_font_style1'],
						'lps_font_color'=>$posted_data['lps_font_color1'],
					
					);
					if(empty($sent1_style))
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent1);
					else
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent1,'lps_id="'.$sent1_style['lps_id'].'"');
				}
				
				if((isset($posted_data['lps_font2'])) || (isset($posted_data['lps_font_size2'])) ||(isset($posted_data['lps_font_style2'])) ||(isset($posted_data['lps_font_color2'])) )
				{
					$sent2=array(
					
						'lps_lp_id'=>$lp_id,
						'lps_lp_element'=>2,
						'lps_font'=>$posted_data['lps_font2'],
						'lps_font_size'=>$posted_data['lps_font_size2'],
						'lps_font_style'=>$posted_data['lps_font_style2'],
						'lps_font_color'=>$posted_data['lps_font_color2'],
					
					);
				
					if(empty($sent2_style))
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent2);
					else
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent2,'lps_id="'.$sent2_style['lps_id'].'"');
				}
				
				if((isset($posted_data['lps_font3'])) || (isset($posted_data['lps_font_size3'])) ||(isset($posted_data['lps_background3'])) ||(isset($posted_data['lps_font_color3'])) )
				{
					$sent3=array(
					
						'lps_lp_id'=>$lp_id,
						'lps_lp_element'=>3,
						'lps_font'=>$posted_data['lps_font3'],
						'lps_font_size'=>$posted_data['lps_font_size3'],
						'lps_background'=>$posted_data['lps_background3'],
						'lps_font_color'=>$posted_data['lps_font_color3'],
					
					);
					if(empty($button_style))
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent3);
					else
					$this->modelStatic->Super_Insert("landing_pages_styles",$sent3,'lps_id="'.$button_style['lps_id'].'"');
				}
				
				
				$objSession->successMsg="Your page is updated sucessfully!";
				$this->_redirect('landing-pages');
			}
			else
			{
				$objSession->errorMsg="Please check the information again!";
			}
 			
 		} // End Post Form
		
		$this->view->form=$form;
		$this->view->newform=$newform;
		$this->render('addcustompage');
		
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
				
				Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("destination_path"=>$path."/450","size"=>530)));
				Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("destination_path"=>$path."/450crop","width"=>530,"height"=>319,"crop"=>true,"ratio"=>false)));
				
				Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("destination_path"=>$path."/1600crop","width"=>1600,"height"=>981,"crop"=>false,"ratio"=>false)));
				Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("destination_path"=>$path."/212","width"=>212,"height"=>144,"crop"=>false,"ratio"=>false)));
			}
			
				 
 			//$uploaded_image_names[]=array('media_path'=>$new_name); => For Multiple Images
   		
		}/* End Foreach Loop for all images */
		
		
		return (object)array("success"=>true,'error'=>false,"message"=>"Image(s) Successfully Uploaded","media_path"=>$new_name) ;
 		
   	 
 	}
	
	public function editAction(){
		global $objSession; 
		
		$this->view->show = "landing_page" ; 
		$lp_id=$this->_getParam('lp_id');
		$pop_data=$this->modelStatic->Super_Get("landing_pages",'lp_id="'.$lp_id.'"','fetch');
		$temp_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pop_data['lp_pt_id'].'"','fetch',array('fields'=>array('pt_title','pt_id','pt_thumb')));
		$this->view->temp_data=$temp_data;
		$form = new Application_Form_Landingpages();
		$form->createpage($lp_id);
		$form->submitbtn->setLabel("Update Page");
		$form->populate($pop_data);
		if ($this->getRequest()->isPost()){ // Post Form Data
			
				
 			$posted_data  = $this->getRequest()->getPost();
  			
			
			if ($form->isValid($this->getRequest()->getPost()))
			{
				$received_data  = $form->getValues();
				$data=array(
					'lp_name'=>$received_data['lp_name'],
					'lp_url'=>encodeProfileUrl($received_data['lp_url']),
					'lp_updated'=>date('Y-m-d H:i:s')
				
				);
				$this->modelStatic->Super_Insert("landing_pages",$data,'lp_id="'.$lp_id.'"');
				$objSession->successMsg="Your page is updated sucessfully!";
				$this->_redirect('landing-pages');
			}
			else
			{
				$objSession->errorMsg="Please check the information again!";
			}
 			
 		} // End Post Form
		
		$this->view->form=$form;
		$this->render('add');
		
	}
	
	
	public function allpagesAction(){
		global $objSession; 
		global $mySession; 
 		$this->view->pageHeading = "Manage All Users";
		$this->view->pageDescription = "manage all site users ";
		
	}
	
	public function getpagesAction(){
		
		$this->dbObj = Zend_Registry::get('db');
		
		$request_type = $this->_getParam('type');
 
 		$aColumns = array(
			'lp_id',
			'lp_name',
			'lp_added',
			'lp_url',
			'lp_updated',
			
  		);

		$sIndexColumn = 'lp_id';
		$sTable = 'landing_pages';
		/* 
		 * Paging
		 */
		 
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".intval( $_GET['iDisplayLength'] );
		}
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= "".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$sWhere = "";
		if ( isset($_GET['sSearch']) and $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				//$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
				$sWhere .= "".$aColumns[$i]." LIKE '%".trim(addslashes($_GET["sSearch"]))."%'  OR ";
				//$sWhere .= "".$aColumns[$i]." LIKE '%".trim(addslashes($_GET["sSearch"]))."%' OR "; // NEW CODE
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) and $_GET['bSearchable_'.$i] == "true" and $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				//$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				$sWhere .= "".$aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
		}
		
	
		
	
		if($sWhere){
			$sWhere.=" and lp_user_id='".$this->view->user->user_id."' AND lp_page_publish='publish' ";
		}else{
			$sWhere.=" where lp_user_id='".$this->view->user->user_id."' and lp_page_publish='publish' ";
		}
		
		
		
 		
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns)).",pt_thumb,pt_title,pt_id,count(distinct pl_id) as lead_count FROM  $sTable  left join page_templates on pt_id=lp_pt_id  left join page_leads on pl_lp_id=lp_id $sWhere group by lp_id   $sOrder $sLimit";
 		$qry = $this->dbObj->query($sQuery)->fetchAll();
 		/* Data set length after filtering */
		$sQuery = "SELECT FOUND_ROWS() as fcnt";
		$aResultFilterTotal =  $this->dbObj->query($sQuery)->fetchAll(); 
		$iFilteredTotal = $aResultFilterTotal[0]['fcnt'];
		
		/* Total data set length */
		$sQuery = "SELECT COUNT(`".$sIndexColumn."`) as cnt FROM $sTable";
		$rResultTotal = $this->dbObj->query($sQuery)->fetchAll(); 
		$iTotal = $rResultTotal[0]['cnt'];
		
		/*
		 * Output
		 */
		 
		 
		$output = array(
 				"iTotalRecords" => $iTotal-1,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
		
		$j=0;
		$i=1;
		foreach($qry as $row1){
			
 			$row=array();
			
			$row[] = $i;
  			$row[]='<input class="elem_ids checkboxes"  name="all_pages[]" type="checkbox" name="'.$sTable.'['.$row1[$sIndexColumn].']"  value="'.$row1[$sIndexColumn].'">';
			$row[]=$row1['pt_title'];
			$row[]=$row1['lp_url'];
			$view_url= get_redirect_url_string($row1['pt_id']);
			//$pub_url=APPLICATION_URL.'/'.$view_url.'/'.$row1[$sIndexColumn];
			
			$pub_url=SITE_HTTP_URL.'/'.$row1['lp_url'];
			$row[]='<a href="'.$pub_url.'" target="_blank">'.$pub_url.'</a>';
			$url=HTTP_TEMPLATE_IMAGES.'/'.$row1['pt_thumb'];
			
			$row[]='<img  style="max-width:100px" src="'.$url.'" />';
			if($row1['lead_count']>0)
			{
				$lead_str='<a href="'.APPLICATION_URL.'/landingpages/allleads/lp_id/'.$row1[$sIndexColumn].'" class="btn btn-xs purple">  '.$row1['lead_count'].' LEADS </a>';
			}
			else
			{
				$lead_str='0';
			}
			
			$row[]=$lead_str;
			$row[]=date('F j Y',strtotime($row1['lp_added']));
			if($row1['lp_updated']=='0000-00-00 00:00:00')
			$row1['lp_updated']=$row1['lp_added'];
			$row[]=date('F j Y',strtotime($row1['lp_updated']));
			$caption =urlencode(substr(stripslashes('www.techdemolink.co.in/landingpages'),0,100));
            $name=urlencode($row1['lp_name']);
			$url=HTTP_SITEIMG_PATH.'/t-icon.png';
			$click='window.open("https://twitter.com/intent/tweet?screen_name=Agentlead&text='.$name.'&url='.$pub_url.'","sharer","toolbar=0,status=0,width=580,height=325")';
             $tshare='<div  class="twitter_share inline-block" onclick='.$click.' ><img  style="max-width:100px;cursor:pointer" src="'.$url.'" /></div> ';
			 $url=HTTP_SITEIMG_PATH.'/f-icon.png';
			 	$click='FBShareOp("'.$name.'","'.$pub_url.'","'.$pub_url.'","'.$url.'")';

              $fshare='<div class="twitter_share inline-block" onclick='.$click.' ><img  style="max-width:100px;cursor:pointer" src="'.$url.'" /></div> ';
			  $url=HTTP_SITEIMG_PATH.'/g-icon.png';
			  $pub_url=$pub_url;
			  $click='window.open("https://plus.google.com/share?url='.$pub_url.'","sharer","toolbar=0,status=0,width=580,height=325","sharer","toolbar=0,status=0,width=580,height=325")';
             $gshare='<div class="twitter_share inline-block"  onclick='.$click.' ><img  style="max-width:100px;cursor:pointer" src="'.$url.'" /></div> ';
			//$pub_url=APPLICATION_URL.'/'.$view_url.'/'.$row1[$sIndexColumn];	
			/* $row[]=" ".$fshare." &nbsp ".$tshare." &nbsp ".$gshare."";*/
			if($row1['pt_id']==5)
			$row[] =  '<a href="'.APPLICATION_URL.'/update-custom-landing-page/'.$row1[$sIndexColumn].'" class="btn btn-xs purple"> Edit <i class="fa fa-pencil"></i></a>';
			else if($row1['pt_id']==6)
			$row[] =  '<a href="'.APPLICATION_URL.'/update-buyer-landing-page/'.$row1[$sIndexColumn].'" class="btn btn-xs purple"> Edit <i class="fa fa-pencil"></i></a>';
			else if($row1['pt_id']==7 || $row1['pt_id']==8 ||  $row1['pt_id']==9 ||  $row1['pt_id']==10 || $row1['pt_id']==11)
			//$this->url(array('pt_id'=>$this->bg_image_data['pt_id'],'lp_id'=>$this->lp_id),'add_custom_page')
			$row[] =  '<a href="'.$this->view->url(array('pt_id'=>$row1['pt_id'],'lp_id'=>$row1[$sIndexColumn]),'add_custom_page').'" class="btn btn-xs purple"> Edit <i class="fa fa-pencil"></i></a>';
			else
			$row[] =  '<a href="'.APPLICATION_URL.'/update-landing-page/'.$row1[$sIndexColumn].'" class="btn btn-xs purple"> Edit <i class="fa fa-pencil"></i></a>';
			$row[] =  '<a href="'.$pub_url.'" class="btn btn-xs purple"> View <i class="fa fa-search"></i></a>';
			//$row[] = '<a class="btn mini green-stripe" href="'.APPLICATION_URL.'/admin/user/account/user_id/'.$row1[$sIndexColumn].'">View</a>';
 			$output['aaData'][] = $row;
			$j++;
		$i++;
		}
		
		echo json_encode( $output );
		exit();
  	}
	 public function removeAction()
 {
 	 global $objSession;
   $this->_helper->layout->disableLayout();
  $this->_helper->viewRenderer->setNoRender(true);
   if ($this->getRequest()->isPost()) {
   $formData = $this->getRequest()->getPost();
   if(isset($formData['all_pages']) and count($formData['all_pages'])){
     foreach($formData['all_pages'] as $key=>$values){
         $this->modelStatic->Super_Delete("landing_pages","lp_id='".$values."'");
      
     }
     $objSession->successMsg = " Data Deleted Successfully ";
    }else{
    $objSession->errorMsg = "Invalid Request to Delete User(s) ";
   }
    $this->_redirect('landing-pages');
  } 
  

 }
 
 	 public function removeleadAction()
 {
 	 global $objSession;
	 $pl_id = strtolower($this->_getParam('pl_id'));
  	 $this->_helper->layout->disableLayout();
   	$this->modelStatic->Super_Delete("page_leads","pl_id='".$pl_id."'");
		 $objSession->successMsg="Record deleted successfully.";

	 $this->_redirect('/landingpages/allleads');
  

 }
 public function customlistingAction(){
	 $custom_temp=$this->modelStatic->Super_Get("page_templates",'pt_type="custom"','fetchall');
	 $this->view->custom_temp=$custom_temp;
	 
 }
 
 public function customaddAction(){
		global $objSession; 
		$this->view->show = "landing_page" ; 
		$pt_id=$this->_getParam('pt_id');
		$lp_id=$this->_getParam('lp_id');		
		$this->view->pt_id=$pt_id;
		$temp_data=$this->modelStatic->Super_Get("page_templates",'pt_id="'.$pt_id.'"','fetch');
		$this->view->temp_data=$temp_data;	
		$form = new Application_Form_Landingpages();
		if($lp_id!=''){	
			$page_data  = $this->modelStatic->Super_Get("landing_pages", 'lp_id="' . $lp_id . '"', 'fetch'); 
			$this->view->page_data=$page_data;		      
			//button_color
			$button_style=$this->modelStatic->Super_Get("landing_pages_styles", 'lps_lp_id="' . $lp_id . '"', 'fetch');	
			$this->view->btn_color=$button_style['lps_background'];
			if($pt_id=='7' || $pt_id=='9'){
				$form->custompage($lp_id);
			}else if($pt_id=='8' ){
				$form->custompage2($lp_id);
			}else if($pt_id=='10'){
				$form->custompage3($lp_id);
			}else if($pt_id=='11'){
				$form->custompage5($lp_id);
			}
			$form->populate($page_data);
		}else{
			if($pt_id=='7' || $pt_id=='9'){
				$form->custompage();
				//$temppopulatedata=array('lp_headline'=>"Insert your headline text here. Insert your headline text here. Insert your headline text here.","lp_button_text"=>"Insert Your Button text here","lp_popup_text"=>"Insert your popup headline text here.","lp_popup_btn_text"=>"Insert your popup button text here.");
			}else if($pt_id=='8'){
				$form->custompage2();
				//$temppopulatedata=array('lp_headline'=>"Insert your headline text here.","lp_subheadline"=>'Insert your subheadline text here',"lp_video_link"=>'https://player.vimeo.com/video/201208503',"lp_button_text"=>"Insert Your Button text here","lp_popup_text"=>"Insert your popup headline text here.","lp_popup_btn_text"=>"Insert your popup button text here.");
			}
			else if($pt_id=='10'){
				$form->custompage3();
				//$temppopulatedata=array('lp_headline'=>"Insert your headline text here.","lp_subheadline"=>'Insert your subheadline text here',"lp_bullet_1"=>"Bullet point text 1 goes here","lp_bullet_2"=>"Bullet point text 2 goes here","lp_bullet_3"=>"Bullet point text 3 goes here","lp_button_text"=>"Insert Your Button text here","lp_popup_text"=>"Insert your popup headline text here.","lp_popup_btn_text"=>"Insert your popup button text here.");
			}else if($pt_id=='11'){
				$form->custompage5();
			}
				
			$this->view->btn_color='#e67e22';
			//$form->populate($temppopulatedata);		
		}
		
		if ($this->getRequest()->isPost()){ // Post Form Data
 			$posted_data  = $this->getRequest()->getPost();	
			if ($form->isValid($this->getRequest()->getPost()))
			{
				$received_data  = $form->getValues();	
				if($posted_data['Preview']=='1'){
					$received_data['lp_page_publish']='preview';
				}else{
					$received_data['lp_page_publish']='publish';
				}
				if(empty($received_data['lp_bg_image']) && !empty($page_data['lp_bg_image'])){
					$received_data['lp_bg_image']=$page_data['lp_bg_image'];
				}
				$received_data['lp_pt_id']=$pt_id;
				$received_data['lp_user_id']=$this->view->user->user_id;
				$received_data['lp_url']=encodeProfileUrl($received_data['lp_name']);								
				$styledata['lps_lp_element']='2';
				$styledata['lps_background']=$received_data['lps_background'];
				unset($received_data['lps_background']);
				if($lp_id==''){//Insert
				$received_data['lp_added']=date("Y-m-d H:i:s");
				
				$result=$this->modelStatic->Super_Insert("landing_pages",$received_data);
				$styledata['lps_lp_id']=$result->inserted_id;
				//button bg color
				$style=$this->modelStatic->Super_Insert("landing_pages_styles",$styledata);	
				}else{//Update
				$received_data['lp_updated']=date("Y-m-d H:i:s");
				$result=$this->modelStatic->Super_Insert("landing_pages",$received_data,'lp_id="'.$lp_id.'"');				
				//button bg color
				$style=$this->modelStatic->Super_Insert("landing_pages_styles",$styledata,'lps_lp_id="'.$lp_id.'"');	
				}				
				if($posted_data['Preview']!='1'){
					$this->_redirect('landing-pages');
					$objSession->successMsg="Your page is published sucessfully!";
				}else{//preview of page
					$this->_redirect(SITE_HTTP_URL.'/'.$received_data['lp_url']);
				}
			}
			else
			{
				$objSession->errorMsg="Please check the information again!";
			}
 		} // End Post Form
		$this->view->form=$form;
 }
 public function custompublishAction(){
	 global $objSession;
	 $lp_id=$this->_getParam('lp_id');	
	 $received_data['lp_updated']=date("Y-m-d H:i:s");
	 $received_data['lp_page_publish']='publish';
	 $result=$this->modelStatic->Super_Insert("landing_pages",$received_data,'lp_id="'.$lp_id.'"');	
	 $objSession->successMsg="Your page is published sucessfully!";
	 $this->_redirect('landing-pages');
 }
 public function custombgimageAction(){			
		global $objSession;
		$extarr=explode(".",$_FILES['lp_bg_image_upload']['name']);
		$imagesize=getimagesize($_FILES['lp_bg_image_upload']['tmp_name']);
		
		$ext=explode(",",IMAGE_VALID_EXTENTIONS);
		if(!in_array($extarr[count($extarr)-1],$ext)){
			echo json_encode(0);
		}
		else if(empty($imagesize)){			
			echo json_encode(0);
		}		
		else{
			/*if(!empty($imagesize)){
				if($imagesize[0]<1600){//height
					echo json_encode(2);
					exit();
				}
				if($imagesize[1]<1200){//width
					echo json_encode(2);
					exit();
				}
			}*/
			$is_uploaded=$this->_handle_uploaded_image(TEMPLATE_IMAGES);			
			echo json_encode($is_uploaded->media_path);
		}
		exit();	 
 }
}