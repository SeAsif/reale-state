<?php
class Application_Model_Department extends Zend_Db_Table_Abstract
{
	protected $_name = 'department';
	
 	/*******************************************
		Category Model
		Action : Add and update Product Series 
	********************************************/
	
	##-------------------------##
	##  Save category
	##--------------------------##
	function add($formData , $id=false)
	{
		if($id)
		{
			$cId = $this->update($formData, 'department_id =  '.$id );  
		}
		else
		{
			$cId=$this->insert(($formData));	
		}
		return $cId ;
	}
	
	/*End */
	
	##----------------------------##
	## Get Education level
	##----------------------------## 
	public function getallDepartmentList(){
		
		$result = $this->select()
					->where("department_status='1'")
		               ->Query()
					   ->fetchAll();

        return $result;					   
	}
	
	
    ##--------------------------##
	##  Check username exitance
	##--------------------------##	 
	function checkDepartmentExistance($CategoryTitle,$CategoryId=false){	
	
	
	 // pr($educationLevel);
	 // pr("education_level_id!='".$educationId."'   and education_level_title = '".$educationLevel."'");
	//  prd($educationId);
			if(!$CategoryId){
				$result = $this->select()->where("department_title = '".$CategoryTitle."'") ; 	
			}else{
				$result = $this->select()->where("department_id !='".$CategoryId."'   and department_title = '".$CategoryTitle."'") ; 	
			}
			$Category_record= $result->query()->fetch() ; 
			 
			return $Category_record;
	}

	##-------------------------##
	## Get One Package Data
	##-------------------------##
	public function getDepartmentInfo($categoryId){
		
		$result  = $this->select()->where("department_id = '".$categoryId."'")->query()->fetch();
		return $result;
		
		
	}
	
	
	##-------------------------##
	## Get list
	##--------------------------##	 
	
	function getDepartmentList(){
		
		$c=$this->getallDepartmentList();
		
		$OptionsArr = array();
		$OptionsArr[0]['key'] = "";
		$OptionsArr[0]['value'] = "Select Department";
		
		$k = 1;
		foreach($c as $values)
		{
			$OptionsArr[$k]['key'] = $values['department_id'];
			$OptionsArr[$k]['value'] = $values['department_title'];
			$k++;
		}
				
 
		
	 
		return $OptionsArr;
	}

	
	
	##---------------------------##
	## Delete Membershiop Package
	##---------------------------##
	public function deleteDepartment($category_ids){
		
		$mId = $this->delete('department_id IN('.$category_ids.')');
		return $mId;

	}
	 
	 
	   
	
}