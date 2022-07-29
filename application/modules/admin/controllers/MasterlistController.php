<?php
class Admin_MasterlistController extends Zend_Controller_Action
{
    public function init(){
		
		$this->Supermodel = new Application_Model_SuperModel();
		$this->view->pageIcon = "fa fa-list";
    }
 	
 	public function indexAction(){
 		global $mySession; 
		global $master_list;
		$master_type_id =  $this->_getParam("master_type_id");
		$this->view->master_type_id = $master_type_id ;	
		$tablename=$master_list[$master_type_id]['name'];
		$heading=$master_list[$master_type_id]['heading'];
		
 		$this->view->pageHeading = "Manage All ".$heading;
		$this->view->pageDescription = "manage all ".$heading;
		$this->view->request_type = "all";
		
 	}
 		
	public function addmasterlistAction(){
		
		global $mySession; 
		global $master_list;
		$master_type_id =  $this->_getParam("master_type_id");
		
		$this->view->master_type_id = $master_type_id ;	
		$tablename=$master_list[$master_type_id]['name'];
		$heading=$master_list[$master_type_id]['heading'];
		$prefix=$master_list[$master_type_id]['prefix'];
		$fieldname=$prefix.'_name';
		$fielddate=$prefix.'_added';
		
		$this->view->pageHeading = ("Add ".$heading);
		$this->view->pageDescription =("Add ".$heading);
		
		$form = new Application_Form_Masterlist();
		$form->addtolist($fieldname);
		
		if($this->getRequest()->isPost()){
			
			$posted_data = $this->getRequest()->getPost();
 
  			if($form->isValid($posted_data)){
				
				$data = $form->getValues();
				$data[$fielddate]=date("Y-m-d H:i:s");
				$check_exists=$code_information = $this->Supermodel->Super_Get($tablename,"$fieldname='".$data[$fieldname]."'","","");
				if(empty($check_exists)){
 				$is_update = $this->Supermodel->Super_Insert($tablename,$data);
				
				if(is_object($is_update) and $is_update->success){
					$mySession->successMsg = $heading." is added successfully.";
					$this->_redirect("admin/masterlist/index/master_type_id/".$master_type_id);
				}
				}
				else
				{
				$mySession->errorMsg = $data[$fieldname]." already exists.";
				}
			}
 		}
		
  		$this->view->form = $form ;	
   	    $this->_helper->getHelper('viewRenderer')->renderScript('add.phtml');
	}
	
	public function editmasterlistAction(){
		
		global $mySession; 
		global $master_list;
		$master_type_id =  $this->_getParam("master_type_id");
		$this->view->master_type_id = $master_type_id ;	
		
		$tablename=$master_list[$master_type_id]['name'];
		$heading=$master_list[$master_type_id]['heading'];
		$prefix=$master_list[$master_type_id]['prefix'];
		$fieldname=$prefix.'_name';
		$fielddate=$prefix.'_added';
		$fieldid=$prefix.'_id';
		
		$main_id =  $this->_getParam("main_id");
		
		$code_information = $this->Supermodel->Super_Get($tablename,"$fieldid='".$main_id."'","","");
		
		if(!$code_information){
			$mySession->errorMsg = "No Such ".$heading." Found , Invalid Request .";
			$this->_redirect("admin/masterlist/index/master_type_id/".$master_type_id);
		}
		
		$this->view->pageHeading = "Edit ".$heading;
		$this->view->pageDescription = "Edit ".$heading;
		
		$form = new Application_Form_Masterlist();
		$form->addtolist($fieldname);
		$form->populate($code_information[0]);
 		
		
		if($this->getRequest()->isPost()){
			
			$posted_data = $this->getRequest()->getPost();
 
  			if($form->isValid($posted_data)){
				
				$data = $form->getValues();
				$check_exists=$code_information = $this->Supermodel->Super_Get($tablename,"$fieldname='".$data[$fieldname]."' and $fieldid!='".$main_id."'","","");
			if(empty($check_exists)){
 				$is_update = $this->Supermodel->Super_Insert($tablename,$data,"$fieldid='".$main_id."'");
				
				if(is_object($is_update) and $is_update->success){
					
					$mySession->successMsg = $heading." successfully updated.";
					$this->_redirect("admin/masterlist/index/master_type_id/".$master_type_id);
				}
			}
			else{
				$mySession->errorMsg = $data[$fieldname]." already exists.";
			}
			}
 		}
		
  		$this->view->form = $form ;	
		$this->view->code_information = $code_information ;
   	   $this->_helper->getHelper('viewRenderer')->renderScript('add.phtml');
	}
	
 
 	/* Ajax Call For Get Users */
  	public function getmasterlistAction(){
		global $master_list;
		$master_type_id =  $this->_getParam("master_type_id");
		$this->view->master_type_id = $master_type_id ;	
		$tablename=$master_list[$master_type_id]['name'];
		$prefix=$master_list[$master_type_id]['prefix'];
		$heading=$master_list[$master_type_id]['heading'];
		$fieldname=$prefix.'_name';
		$fielddate=$prefix.'_added';
		$fieldid=$prefix.'_id';
		
		
		$this->dbObj = Zend_Registry::get('db');
		 
 		$aColumns = array(
			$fieldid,
			$fieldid,
			$fieldname,
			$fielddate,
			
  		);

		$sIndexColumn = $fieldid;
		$sTable = $tablename;
 		
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
				$sWhere .= "LOWER(".$aColumns[$i].") LIKE '%".addslashes(trim(strtolower($_GET["sSearch"])))."%' OR "; // NEW CODE
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
				$sWhere .= "LOWER(".$aColumns[$i].") LIKE '%".addslashes(trim(strtolower($_GET['sSearch_'.$i])))."%' ";
			}
		}
		
		
 		
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." FROM  $sTable $sWhere $sOrder $sLimit";
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
 				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
		
		$j=0;
		foreach($qry as $row1){
			
 			$row=array();
			for ($i=0 ; $i<1 ; $i++ ){
				if($aColumns[0]!= ' '){
					//$row[] = $row1[ $aColumns[0] ];
					$row[] = $j+1;
				}
			}
  			$row[]='<input class="elem_ids checkboxes"  type="checkbox" name="'.$sTable.'['.$row1[$sIndexColumn].']"  value="'.$row1[$sIndexColumn].'">';
			$row[]=$row1[$fieldname];
 		    $row[]=date("M d,Y",strtotime($row1[$fielddate]));
			/*$status = $row1[$fieldstatus]!=1?"checked='checked'":" ";
 			$row[]='<div class="danger-toggle-button">
						<input type="checkbox" class="toggle status-'.(int)$row1[$fieldstatus].' "  '.$status.'  id="'.$sTable.'-'.$row1[$sIndexColumn].'" onChange="globalStatus(this)" />
					</div>';*/
			$row[] =  '<a href="'.APPLICATION_URL.'/admin/masterlist/editmasterlist/main_id/'.$row1[$sIndexColumn].'/master_type_id/'.$master_type_id.'" class="btn btn-xs purple"> Edit <i class="fa fa-edit"></i></a>';
 			$output['aaData'][] = $row;
			$j++;
		}
		
		echo json_encode( $output );
		exit();
  	}
	
	/* 
	 *	Remove Graphic Media 
	 */
 	public function removeAction(){
		
		global $mySession;
 		global $master_list;
		$master_type_id =  $this->_getParam("master_type_id");
		$this->view->master_type_id = $master_type_id ;	
		$tablename=$master_list[$master_type_id]['name'];
		$heading=$master_list[$master_type_id]['heading'];
		$fieldname=$tablename.'_name';
		$fielddate=$tablename.'_added';
		$fieldid=$tablename.'_id';
 		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
 
 		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();
			
			if(isset($formData[$tablename]) and count($formData[$tablename])){
				
				 foreach($formData[$tablename] as $key=>$values){

   					 $code_info = $this->Supermodel->Super_Get($tablename,"$fieldid='".$values."'");
					 if(empty($code_info))
						continue ;
						
					$removed1 = $this->Supermodel->Super_Delete($tablename,"$fieldid IN ($values)");
				 }

 				 
 				$mySession->successMsg = $heading."(s) deleted successfully ";
				
 			}else{
				$mySession->errorMsg = " Invalid Request to Delete ".$heading."(s) ";
			}
			
 			$this->_redirect('/admin/masterlist/index/master_type_id/'.$master_type_id);	 
   	 
		} 
 	}
}