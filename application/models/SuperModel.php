<?php
class Application_Model_SuperModel extends Zend_Db_Table_Abstract
{
 	protected $_name = "";
	
	public function init(){		
		
	}
	
  	/* 	Insert  / Update Record to the DataBase 
 	 */
 	public function Super_Insert($table_name ,$data , $where = false){	
	
		$this->_name = $table_name;
				
		try{			
			if($where){
				
				$updated_records = $this->getAdapter()->update($table_name ,$data , $where);
				
				return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Updated","row_affected"=>$updated_records) ;
			}
			
			$insertedId = $this->getAdapter()->insert($table_name,$data); 

 			return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Inserted","inserted_id"=>$this->getAdapter()->lastInsertId()) ;
 		}
		catch(Zend_Exception  $e) {/* Handle Exception Here  */
			return (object)array("success"=>false,"error"=>true,"message"=>$e->getMessage(),"exception"=>true,"exception_code"=>$e->getCode()) ;
 		}
	}
	
  	/*   */
 	public function Super_Get($table_name , $where = 1, $fetchMode = 'fetch', $extra = array(),$joinArr=array()){
		
		$this->_name = $table_name;
		
		$fields = array('*');
		if(isset($extra['fields']) and  $extra['fields']){
			if(is_array($extra['fields'])){
				$fields = $extra['fields'];
			}else{
				$fields = explode(",",$extra['fields']);
			}
		}
		$query  = $this->getAdapter()->select()->from($this->_name,$fields)->where($where);
		
		/* Join Conditions */
		if(isset($joinArr)){
			foreach($joinArr as $newCondition){ 
				if($newCondition[2]=='full')
					$query->join($newCondition[0],$newCondition[1],$newCondition[3]);
				else
					$query->joinLeft($newCondition[0],$newCondition[1],$newCondition[3]);	
			}
		}
		//echo $query;die;
		
		
		if(isset($extra['group']) and  $extra['group']){
			$query = $query->group($extra['group']);
		}
		
		if(isset($extra['having']) and  $extra['having']){
			$query = $query->having($extra['having']);
		}
		
		if(isset($extra['order']) and  $extra['order']){
			$query = $query->order($extra['order']);
		}
		
		if(isset($extra['limit']) and  $extra['limit']){
			$query = $query->limit($extra['limit']);
		}
 		/* If Pagging is Required */
		if(isset($extra['pagination']) and  $extra['pagination'] and !isset($extra['pagination_result'])){
			return $query;
		}
		if(isset($extra['pagination']) and  $extra['pagination'] and isset($extra['pagination_result']) and  $extra['pagination_result']){
			return array($query,$query->query()->fetchAll());
		}
		
		
 		
		return $fetchMode=='fetch'? $query->query()->fetch():$query->query()->fetchAll();
	 }
	 
	 /* 	Insert  / Update Record to the DataBase 
 	 */
 	public function Super_Delete($table_name , $where = "1"){	
   		try{
			
			$deleted_records = $this->getAdapter()->delete($table_name ,  $where);
 			return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Deleted","deleted_records"=>$deleted_records) ;
  		}
		catch(Zend_Exception  $e) {/* Handle Exception Here  */
			return (object)array("success"=>false,"error"=>true,"message"=>$e->getMessage(),"exception"=>true,"exception_code"=>$e->getCode()) ;
 		}
	}
	
	 
	 
	 
 	
	public function PrepareSelectOptions_withdefault($tabelname ,$fieldname1,$fieldname2,$where,$order,$default_value=false)
	{	

		

		if(!$order)
		$result = $this->getAdapter()->select()->from($tabelname)->where($where);
		else  
		$result = $this->getAdapter()->select()->from($tabelname)->where($where)->order($order);
		//echo $result;die;
		$data= $result->query()->fetchAll() ; 

		$getdata=array();

		if($default_value)

		{

		$getdata['']=$default_value;

		}

		for ($i = 0; $i < count($data); $i++) 

		{



		$getdata[rtrim($data[$i][$fieldname1])]= rtrim($data[$i][$fieldname2]);



		}

		

		return $getdata;

	}
	
	
	public function PrepareSelectOptions_withformated($tabelname ,$type,$default_value=false)
	{	

		

	 
		$result = $this->getAdapter()->select()->from($tabelname)->where(1);
		//echo $result;die;
		$data= $result->query()->fetchAll() ; 

		$getdata=array();

		if($default_value)

		{

		$getdata['']=$default_value;

		}

		for ($i = 0; $i < count($data); $i++) 

		{


		if($type=='price')
		$getdata[$data[$i]['pr_id']]=$data[$i]['pr_name']; 
		elseif($type=='bathrooms')
		{
					$getdata[rtrim($data[$i]['bt_id'])]= rtrim($data[$i]['bt_name']);

		}
		elseif($type=='bedrooms')
		{
					$getdata[rtrim($data[$i]['bd_id'])]= rtrim($data[$i]['bd_name']);

		}
		



		}

		

		return $getdata;

	}


	public function PrepareSelectOptions_withdefault1($tabelname ,$fieldname1,$fieldname2,$where,$order,$default_value=false,$id)
	{	

		

		if(!$order)

		$result = $this->getAdapter()->select()->from($tabelname)->where($where);

		else  

		$result = $this->getAdapter()->select()->from($tabelname)->where($where)->order($order);

		$data= $result->query()->fetchAll() ; 

		$getdata=array();

		if($default_value)

		{

		$getdata['']=$default_value;

		}

		$b=0;

		for ($i = 0; $i < count($data); $i++) 

		{

		

		$keyword_data='';

		if(!empty($id))

		{

			

		$keyword_data=$this->Super_Get("keywords",'keyword_label="'.$id.'"',"fetchAll");

		

		if(!empty($keyword_data))

		{

	

		$getdata[$data[$i][$fieldname1]]= $data[$i][$fieldname2];

		

		$b++;

		}

		}

		else

		{

		$keyword_data=$this->Super_Get("keywords",'keyword_label="'.$data[$i]['label_id'].'"',"fetchAll");	

		if(empty($keyword_data))

		{

		

		$getdata[$data[$i][$fieldname1]]= $data[$i][$fieldname2];

		

		$b++;

		}

		}

		

		}

		

		return $getdata;

	}
	 
	 
}