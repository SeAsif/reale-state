<?php
class Application_Model_Category extends Zend_Db_Table_Abstract
{
	protected $_name = 'category';
	
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
			$cId = $this->update($formData, 'category_id =  '.$id );  
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
	public function getallCategoryList(){
		
		$result = $this->select()
						->where("category_status='1'")
		               ->Query()
					   ->fetchAll();

        return $result;					   
	}
	
	
    ##--------------------------##
	##  Check username exitance
	##--------------------------##	 
	function checkCategoryExistance($CategoryTitle,$CategoryId=false){	
	
	
	 // pr($educationLevel);
	 // pr("education_level_id!='".$educationId."'   and education_level_title = '".$educationLevel."'");
	//  prd($educationId);
			if(!$CategoryId){
				$result = $this->select()->where("category_title = '".$CategoryTitle."'") ; 	
			}else{
				$result = $this->select()->where("category_id !='".$CategoryId."'   and category_title = '".$CategoryTitle."'") ; 	
			}
			$Category_record= $result->query()->fetch() ; 
			 
			return $Category_record;
	}

	##-------------------------##
	## Get One Package Data
	##-------------------------##
	public function getCategoryInfo($categoryId){
		
		$result  = $this->select()->where("category_id = '".$categoryId."'")->query()->fetch();
		return $result;
		
		
	}
	
	
	##-------------------------##
	## Get list
	##--------------------------##	 
	
	function getCategoryList(){
		
		$c=$this->getallCategoryList();
		
		$OptionsArr = array();
		$OptionsArr[0]['key'] = "";
		$OptionsArr[0]['value'] = "Select Industry";
		
		$k = 1;
		foreach($c as $values)
		{
			$OptionsArr[$k]['key'] = $values['category_id'];
			$OptionsArr[$k]['value'] = $values['category_title'];
			$k++;
		}
				
 
		
	 
		return $OptionsArr;
	}

	
	
	##---------------------------##
	## Delete Membershiop Package
	##---------------------------##
	public function deleteCategory($category_ids){
		
		$mId = $this->delete('category_id IN('.$category_ids.')');
		return $mId;

	}
	 
	 
	   
	
}