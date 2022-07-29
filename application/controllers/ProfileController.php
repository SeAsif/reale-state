<?php
class ProfileController extends Zend_Controller_Action
{
	public function init(){	
 		$this->modelStatic = new Application_Model_Static();
		$this->modelUser = new Application_Model_User();
		$this->modelSuper = new Application_Model_SuperModel();
		$this->pluginImage = new Application_Plugin_Image();
		
   	}
	
	public function changestatAction(){
			
					$par = $this->_getParam('par');
					$_SESSION['video_seen']=1;
					if($par==1)
					$x=$this->modelSuper->Super_Insert("users",array('user_video_seen'=>1),'user_id="'.$this->view->user->user_id.'"');	
					//prd($x);
					exit;
		}
	public function homeAction(){	
	$temp=array('page_templates','lp_pt_id=pt_id','left',array());
		$buyer_landing_pages=$this->modelStatic->Super_get("landing_pages","lp_user_id='".$this->view->user->user_id."' and pt_type='buyer'",'fetch',array('fields'=>array('count(distinct lp_id) as count')),array(0=>$temp));
		$this->view->buyer_count=$buyer_landing_pages['count'];
		$seller_landing_pages=$this->modelStatic->Super_get("landing_pages","lp_user_id='".$this->view->user->user_id."' and pt_type='seller'",'fetch',array('fields'=>array('count(distinct lp_id) as count')),array(0=>$temp));
		$this->view->seller_count=$seller_landing_pages['count'];
		$all_leads=$this->modelStatic->GetAllLeads("lp_user_id='".$this->view->user->user_id."'");
		$this->view->all_leads=$all_leads;
		$all_leads=$this->modelStatic->GetAllLeads("lp_user_id='".$this->view->user->user_id."' and year(pl_added)='".date('Y')."' and month(pl_added)='".date('m')."'  and day(pl_added)='".date('d')."' ");
		$this->view->all_leads_today=$all_leads;
	}
 	public function indexAction(){	
 		global $objSession ; 
		
   		$content = $this->modelStatic->getPage(40); 
		$this->view->btn=1;
		$form = new Application_Form_User();
		
		
		$this->view->page_slug = "profile" ; 
		
		$form->profile_front($this->view->user->user_id);
		
		$form->populate((array)$this->view->user);
		
 		
		if($this->getRequest()->isPost()){

		$data_post = $this->getRequest()->getPost();
		
		if($form->isValid($data_post)){
			
			$data_to_update = $form->getValues() ;
			
			/*if($data_to_update['user_email']!=$this->view->user->user_email){
				 $data_to_update["user_email_verified"] = "0" ;
			}*/
			
 			$is_update  = $this->modelUser->add($data_to_update , $this->view->user->user_id);
			
			if(is_object($is_update) and $is_update->success){
				if($is_update->row_affected > 0){
					$objSession->successMsg = " Profile Information Changed Successfully ";
				}else{
					$objSession->infoMsg = " New Information is Same as previous one ";
				}
				$this->_helper->getHelper("Redirector")->gotoRoute(array(),"front_profile");
			}
			
			$objSession->errorMsg  = $is_update->message; ;
			
		}else{
			$objSession->errorMsg = "Please Check Information Again ...!";	
		}
	}
		
		$this->view->form = $form;
		
		$this->view->content = $content ;
	}


	public function pagination($searchDataQuery,$page,$record_per_page)
	{
		$adapter = new Zend_Paginator_Adapter_DbSelect($searchDataQuery);
		$paginator = new Zend_Paginator($adapter);
		$page =$page;
		$this->view->page=$page;
		$rec_counts = $this->_getParam('itemcountpage');
		if(!$rec_counts)
		{
			if(isset($record_per_page))
			$rec_counts =$record_per_page;
			else
			$rec_counts =10;
		}
		$paginator->setItemCountPerPage($rec_counts);
		$paginator->setCurrentPageNumber($page);
		$paginationControl = new Zend_View_Helper_PaginationControl($paginator, 'sliding', 'pagination-control.phtml');
		$this->view->paginationControl=$paginationControl;
		return $paginator;
	}
	
	
	
	
 	public function imageAction(){
		
		//prd('bnvb');
		global $objSession ;
		$this->view->show = "front_image";
		 /* Form For Update Profile Image  */
 		$form =  new Application_Form_User();
		$form->image();
		
		
		if($this->getRequest()->isPost()){
 
 			$data_post = $this->getRequest()->getPost();
			//prd($data_post);
			if($form->isValid($data_post) && $_FILES['user_image']['name']!=''){
 				$is_uploaded = $this->_handle_profile_image();
				if(is_object($is_uploaded) and $is_uploaded->success){

					if(empty($is_uploaded->media_path)){
						/* Not Image is Uploaded  */
						$objSession->defaultMsg = "No Images Selected ...";
						$this->_helper->getHelper("Redirector")->gotoRoute(array(),'front_image');
					}
					
					
					
					$is_updated = $this->modelUser->add(array("user_image"=>$is_uploaded->media_path),$this->view->user->user_id);
					
					if(is_object($is_updated) and $is_updated->success){
						
						/* Remove Old User Images*/
						$this->_remove_image(); 
						$objSession->successMsg = " Image Successfully Updated";
						$this->_helper->getHelper("Redirector")->gotoRoute(array(),'front_image');
						
 					}
										
				}
				else
					{
						//prd($form->getErrors());
						$objSession->errorMsg = $is_uploaded->message;
					}
 			}
			
			else
			{
				
				$objSession->infoMsg = " New Information is Same as previous one ";
			}
		}
		
		
		$this->view->form = $form ;
		
	}

	
	/* Method to Crop User Images  */
	public function cropimageAction(){

		global $objSession;
		
   		$this->view->pageHeading = "Crop Image";
		$this->view->pageDescription="";

 		$path=$this->_getParam('path');
		

		if(empty($path)){
			$path = $this->view->user->user_image ;
		}
				
		$this->view->path = $path;
 		
		$filePath = PROFILE_IMAGES_PATH."/".$path;
		
		$imgdata = getimagesize($filePath);
		
		$this->view->imageWidth =  $imgdata[0];
		$this->view->imageHeight =  $imgdata[1];

		
		/* Code for Copping Image */
		if($this->getRequest()->isPost()){
			
			$posted_data = $this->getRequest()->getPost();
			
			$uploaded_image_extension = getFileExtension($path);
			
 			$file_title  = str_replace(".".$uploaded_image_extension,"",$path);
						
			$file_title = formatImageName($file_title);
			
			/* retrive name */
			$_temp = explode("-",$file_title);
			
			array_pop($_temp);array_pop($_temp);
			$file_title = implode("-",$_temp);
			
  			$new_name = $file_title."-".time()."-".rand(1,100000).".".$uploaded_image_extension;
 			
   			$crop_image = array(
				"source_directory" => PROFILE_IMAGES_PATH,
				"name"=>$path,
				"target_name"=>$new_name,
 				'_w'=>$posted_data['w'],
				'_h'=>$posted_data['h'],
				'_x'=>$posted_data['x'],
				'_y'=>$posted_data['y'],
				'destination'=>array(
					"60"=>array("size"=>60),
					"160"=>array("size"=>160),
					"thumb"=>array("size"=>300)
				)
 			);
			
 			$is_crop = $this->pluginImage->universal_crop_image($crop_image);
			
			if($is_crop->success){
				
 				/* Update Name into the database and Replace the prev uploaded news to new names */	
				$this->pluginImage->simple_rename($path,$new_name,array('directory'=>PROFILE_IMAGES_PATH));	
				
				$this->pluginImage->universal_unlink($path,array('directory'=>PROFILE_IMAGES_PATH));	
				
				$is_updated = $this->modelUser->add(array("user_image"=>$new_name),$this->view->user->user_id);
 
   				$objSession->successMsg = $is_crop->message;
			}else{
				$objSession->errorMsg = $is_crop->message;
			}
 			
			$this->_redirect('change-avatar');
		}
		
	}
	
 	
	/* Crop Image  */
	private function _crop_image($param = array()){
 			
			$targ_w = isset($param['width'])?$param['width']:160;
			$targ_h = isset($param['height'])?$param['height']:160;
 			$jpeg_quality = isset($param['quality'])?$param['quality']:100;
 			$src = isset($param['source'])?$param['source']: "";
			$destination = isset($param['destination'])?$param['destination']: "";
			
			$name = isset($param['name'])?$param['name']: "";
			
 
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
			
			 
			list($imagewidth, $imageheight, $imageType) = getimagesize($src."/".$name);
			
			$imageType = image_type_to_mime_type($imageType);
			
			
			$uploaded_image_extension = getFileExtension($name);
 	
			$src = $src.'/'.$name;
			
			switch($imageType) {
				case "image/gif":$source=imagecreatefromgif($src);break;

				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$source=imagecreatefromjpeg($src); 
				break;

				case "image/png":
				case "image/x-png":
					$source=imagecreatefrompng($src); 
				break;
			}
			
			imagecopyresampled($dst_r,$source,0,0,$param['_x'],$param['_y'],$targ_w,$targ_h,$param['_w'],$param['_h']);

			switch($imageType) {
				case "image/gif":
					imagegif($dst_r, $destination."/".$name); 
				break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					imagejpeg($dst_r, $destination."/".$name,$jpeg_quality); 
				break;
				case "image/png":
				case "image/x-png":
					imagepng($dst_r, $destination."/".$name); 
					imagepng($dst_r, $destination."/".$name);  
				break;
				}
	 		
			return true; 
	}
	


	 /* Remove / Unlink Old Profile Image  
 	 */	 
 	private function _remove_image(){
		
		$image_name = $this->view->user->user_image;
		
		if(file_exists(PROFILE_IMAGES_PATH."/".$image_name)){
			unlink(PROFILE_IMAGES_PATH."/".$image_name);
		}
		
		if(file_exists(PROFILE_IMAGES_PATH."/thumb/".$image_name)){
			unlink(PROFILE_IMAGES_PATH."/thumb/".$image_name);
		}
		 
 		if(file_exists(PROFILE_IMAGES_PATH."/60/".$image_name)){
			unlink(PROFILE_IMAGES_PATH."/60/".$image_name);
		}
		if(PROFILE_IMAGES_PATH."/160/".$image_name){
			unlink(PROFILE_IMAGES_PATH."/160/".$image_name);
		}
		
		return true ;
		
	}
	
	
 	
	
	/* Handle The Uploaded Images For Graphic Media  */
	private function _handle_profile_image(){
		
 		global $objSession; 
		
		$uploaded_image_names = array();
	 
		$adapter = new Zend_File_Transfer_Adapter_Http();
	
		$files = $adapter->getFileInfo();
  			$size=$files['user_image']['size'];
			
		$uploaded_image_names = array();
		
		$new_name = false; 
		 
  		/*prd($adapter);*/
		foreach ($files as $file => $info) { /* Begin Foreach for handle multiple images */
		
  			$name_old = $adapter->getFileName($file);
		
			$size=filesize($files);
			
		
			if(empty($name_old)){
				continue ;			
			}
			
			$file_title  = $adapter->getFileInfo($file);
			
			$file_title = $file_title[$file]['name']; 
				
  			$uploaded_image_extension = getFileExtension($name_old);
			
 			$file_title  = str_replace(".".$uploaded_image_extension,"",$file_title);
			
			$file_title = formatImageName($file_title);
  
 			$new_name = $file_title."-".time()."-".rand(1,100000).".".$uploaded_image_extension;
 			
  			$adapter->addFilter('Rename',array('target' => PROFILE_IMAGES_PATH."/".$new_name));
		
			try{
				$adapter->receive($file);
			}
			catch(Zend_Exception $e){
				return (object) array('success'=>false,"error"=>true,'exception'=>true,'message'=>$e->getMessage(),'exception_code'=>$e->getCode()) ;
			}
			
				$thumb_config = array("source_path"=>PROFILE_IMAGES_PATH,"name"=> $new_name);
				Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("size"=>300)));
				Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("destination_path"=>PROFILE_IMAGES_PATH."/60","crop"=>true ,"size"=>60,"ratio"=>false)));
				Application_Plugin_ImageCrop :: uploadThumb(array_merge($thumb_config,array("destination_path"=>PROFILE_IMAGES_PATH."/160","crop"=>true ,"size"=>160,"ratio"=>false)));
			
  			//$uploaded_image_names[]=array('media_path'=>$new_name); => For Multiple Images
   		
		}/* End Foreach Loop for all images */
		
		
		return (object)array("success"=>true,'error'=>false,"message"=>"Image(s) Successfully Uploaded","media_path"=>$new_name) ;
 		
   	 
 	}
	 	
	
	
	public function getfulladdressAction(){
		
		$address_string = $this->_getParam('address_string');
			
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
 
 		$getGeometry=json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address_string).'&sensor=true'));
					
		$address_specification = array();  /*  For Address Specification Array */
		
	
		if(isset($getGeometry->results[0])) {
				
			/* Country City and State */
			foreach($getGeometry->results[0]->address_components as $addressComponet) {
				
				if(in_array('sublocality', $addressComponet->types)) {
					$address_specification['sublocality'] = ($addressComponet->long_name); 
				}
				
				if(in_array('locality', $addressComponet->types)) {
					$address_specification['locality'] = ($addressComponet->long_name); 
				}
				if(in_array('administrative_area_level_2', $addressComponet->types)) {
					$address_specification['city'] = ($addressComponet->long_name); 
				}
				if(in_array('administrative_area_level_1', $addressComponet->types)) {
					$address_specification['state'] = ($addressComponet->long_name); 
				}
				if(in_array('country', $addressComponet->types)) {
					$address_specification['country'] = ($addressComponet->long_name); 
				}
			}
		}else{
			$address_specification['sublocality'] = ""; 
			$address_specification['locality'] = ""; 
			$address_specification['city'] = ""; 
			$address_specification['state'] = "";
			$address_specification['country'] = ""; 
			
		}
 			
		echo json_encode($address_specification);
		exit;
		//prd($address_string);
		
		
		
		
		
		
	}
	

    public function passwordAction(){
		
		global $objSession; 
		
		if($this->view->user->user_login_type!="normal"){
			$objSession->warningMsg = "You cannot access this feature with this login type";
			$this->_helper->getHelper("Redirector")->gotoRoute(array(),'front_profile');
		}
    		
		$this->view->pageHeading = "Change Password";
 		$this->view->pageDescription = "you can change your account password here ";
 
 		$this->view->show = "change_password";
  
		/* Change Password Form */
		$form =  new Application_Form_User();
		$form->changepassword();
  
   		if($this->getRequest()->isPost()){
 
 			$data_post = $this->getRequest()->getPost();
			
			if($form->isValid($data_post)){
				
				$data_to_update = $form->getValues();
				//prd($data_to_update);
				$data_to_update['user_password'] = md5($data_to_update['user_password']);
				
   				$is_update = $this->modelUser->add($data_to_update,$this->view->user->user_id);
  			
				if(is_object($is_update) and $is_update->success){
					$objSession->successMsg = " Password Changed Successfully ";
					$this->_helper->getHelper('Redirector')->gotoRoute(array(),"front_profile");
 				}else{
					$objSession->errorMsg  = $is_update->message; ;
				}
			}else{
				$objSession->errorMsg = "Please Check Information Again ...!";	
			}
  		}
		
		$this->view->form = $form;
		$this->render("index");
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
 	
}