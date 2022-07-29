<?php
class Application_Model_Static extends Application_Model_SuperModel
{
	 
	
	/* Add / Edit Page Information */
	public function add($table_name,$data,$id=false){
 		
		$this->_name= $table_name;
		
		try{
 			
 			if($id){

				$rows_affected = $this->update($data ,$id);

				return (object)array("success"=>true,"error"=>false,"message"=>"Content Successfully Updated","rows_affected"=>$rows_affected) ;
			}
 			
			$inserted_id = $this->insert($data);
			
			return (object)array("success"=>true,"error"=>false,"message"=>"New Page Successfully Added to the database","inserted_id"=>$inserted_id) ;
			
 		}catch(Zend_Exception $e){
			return (object)array("success"=>false,"error"=>true,"message"=>$e->getMessage(),"exception"=>true,"exception_code"=>$e->getCode()) ;
 		}
	}
	 
	 
	public function getPage($id){
		
		$this->_name = "pages";
		
		return $this->select()->where("page_id = ? ",$id)->query()->fetch();
		
		
		
		
	}
	
	public function GetAllLeads($where){
		
		$join_pages=array('landing_pages','lp_id=pl_lp_id','full',array());
		$data=$this->Super_Get("page_leads",$where,'fetch',array('fields'=>array('count(distinct pl_id) as count')),array(0=>$join_pages));
		return $data['count'];
 	}
	
	public function GetLeads($search_array,$user_id){
		
		$where='lp_user_id="'.$user_id.'"';
		$order='pl_id DESC';
		if((isset($search_array['pl_added'])) && (trim($search_array['pl_added'])!=''))
		{
			$order=' pl_added '.$search_array['pl_added'].'';
		}
		if((isset($search_array['lp_id'])) && (trim($search_array['lp_id'])!=''))
		{
			$where.=' and lp_id="'.$search_array['lp_id'].'"';
		}
		if((isset($search_array['pl_property_type'])) && (trim($search_array['pl_property_type'])!=''))
		{
			$where.=' and pl_property_type="'.$search_array['pl_property_type'].'"';
		}
		if((isset($search_array['pl_bedrooms'])) && (trim($search_array['pl_bedrooms'])!=''))
		{
			$where.=' and pl_bedrooms="'.$search_array['pl_bedrooms'].'"';
		}
		if((isset($search_array['pl_bathrooms'])) && (trim($search_array['pl_bathrooms'])!=''))
		{
			$where.=' and pl_bathrooms="'.$search_array['pl_bathrooms'].'"';
		}
		if((isset($search_array['pl_price'])) && (trim($search_array['pl_price'])!=''))
		{
			$where.=' and pl_price="'.$search_array['pl_price'].'"';
		}
		if((isset($search_array['pt_type'])) && (trim($search_array['pt_type'])!=''))
		{
			$where.=' and pt_type="'.$search_array['pt_type'].'"';
		}
		if((isset($search_array['keyword'])) && (trim($search_array['keyword'])!=''))
		{
			$where.=' and (pl_name like "%'.$search_array['keyword'].'%"  or pl_email like "%'.$search_array['keyword'].'%" or  pl_address like "%'.$search_array['keyword'].'%")';
		}
		$result= $this->getAdapter()->select() ->from(array('pl'=>'page_leads'),array('*'))
		->joinLeft(array('lp'=>'landing_pages'),'lp.lp_id=pl.pl_lp_id',array('lp_name','lp_url'))
		->joinLeft(array('pt'=>'page_templates'),'pt.pt_id=lp.lp_pt_id',array('pt_title','pt_id'))
		->joinLeft(array('pr'=>'price_ranges'),'pr.pr_id=pl.pl_price',array('pr_name'))
		->joinLeft(array('prt'=>'property_types'),'prt.pt_id=pl.pl_property_type',array('pt_name as property_title'))
		->joinLeft(array('ba'=>'bathrooms'),'ba.bt_id=pl.pl_bathrooms',array(''))
		->joinLeft(array('be'=>'bedrooms'),'be.bd_id=pl.pl_bedrooms',array(''))
		->group('pl.pl_id')
		->where($where)
		->order($order);
		return $result;
	
 	}
	
	public function GetLeadsExel($search_array,$user_id){
		
		$where='lp_user_id="'.$user_id.'"';
		$order='pl_id DESC';
		if((isset($search_array['pl_added'])) && (trim($search_array['pl_added'])!=''))
		{
			$order=' pl_added '.$search_array['pl_added'].'';
		}
		if((isset($search_array['lp_id'])) && (trim($search_array['lp_id'])!=''))
		{
			$where.=' and lp_id="'.$search_array['lp_id'].'"';
		}
		if((isset($search_array['pl_property_type'])) && (trim($search_array['pl_property_type'])!=''))
		{
			$where.=' and pl_property_type="'.$search_array['pl_property_type'].'"';
		}
		if((isset($search_array['pl_bedrooms'])) && (trim($search_array['pl_bedrooms'])!=''))
		{
			$where.=' and pl_bedrooms="'.$search_array['pl_bedrooms'].'"';
		}
		if((isset($search_array['pl_bathrooms'])) && (trim($search_array['pl_bathrooms'])!=''))
		{
			$where.=' and pl_bathrooms="'.$search_array['pl_bathrooms'].'"';
		}
		if((isset($search_array['pl_price'])) && (trim($search_array['pl_price'])!=''))
		{
			$where.=' and pl_price="'.$search_array['pl_price'].'"';
		}
		if((isset($search_array['pt_type'])) && (trim($search_array['pt_type'])!=''))
		{
			$where.=' and pt_type="'.$search_array['pt_type'].'"';
		}
		if((isset($search_array['keyword'])) && (trim($search_array['keyword'])!=''))
		{
			$where.=' and (pl_name like "%'.$search_array['keyword'].'%"  or pl_email like "%'.$search_array['keyword'].'%" or  pl_address like "%'.$search_array['keyword'].'%")';
		}
		$result= $this->getAdapter()->select() ->from(array('pl'=>'page_leads'),array('pl_name as Name','pl_email as Email Address','pl_phone as Phone Number','pl_phone as Phone Number','pl_address as Address','pl_bedrooms as Number of bedrooms','pl_bathrooms as Number of bathrooms','pl_interest as Interest','pl_added as Date'))
		->joinLeft(array('lp'=>'landing_pages'),'lp.lp_id=pl.pl_lp_id',array())
		->joinLeft(array('pt'=>'page_templates'),'pt.pt_id=lp.lp_pt_id',array('pt_title as template'))
		->joinLeft(array('pr'=>'price_ranges'),'pr.pr_id=pl.pl_price',array('pr_name as Price Range'))
		->joinLeft(array('prt'=>'property_types'),'prt.pt_id=pl.pl_property_type',array('pt_name as Property Type'))
		->joinLeft(array('ba'=>'bathrooms'),'ba.bt_id=pl.pl_bathrooms',array(''))
		->joinLeft(array('be'=>'bedrooms'),'be.bd_id=pl.pl_bedrooms',array(''))
		->group('pl.pl_id')
		->where($where)
		->order($order);
		return $result;
	
 	}
	
	public function GetLeadsAdmin($search_array){
		
		$where='1';
		$order='pl_id DESC';
		if((isset($search_array['pl_added'])) && (trim($search_array['pl_added'])!=''))
		{
			$order=' pl_added '.$search_array['pl_added'].'';
		}
		if((isset($search_array['lp_id'])) && (trim($search_array['lp_id'])!=''))
		{
			$where.=' and lp_id="'.$search_array['lp_id'].'"';
		}
		if((isset($search_array['pl_property_type'])) && (trim($search_array['pl_property_type'])!=''))
		{
			$where.=' and pl_property_type="'.$search_array['pl_property_type'].'"';
		}
		if((isset($search_array['pl_bedrooms'])) && (trim($search_array['pl_bedrooms'])!=''))
		{
			$where.=' and pl_bedrooms="'.$search_array['pl_bedrooms'].'"';
		}
		if((isset($search_array['pl_bathrooms'])) && (trim($search_array['pl_bathrooms'])!=''))
		{
			$where.=' and pl_bathrooms="'.$search_array['pl_bathrooms'].'"';
		}
		if((isset($search_array['pl_price'])) && (trim($search_array['pl_price'])!=''))
		{
			$where.=' and pl_price="'.$search_array['pl_price'].'"';
		}
		if((isset($search_array['pt_type'])) && (trim($search_array['pt_type'])!=''))
		{
			$where.=' and pt_type="'.$search_array['pt_type'].'"';
		}
		if((isset($search_array['keyword'])) && (trim($search_array['keyword'])!=''))
		{
			$where.=' and (pl_name like "%'.$search_array['keyword'].'%"  or pl_email like "%'.$search_array['keyword'].'%" or  pl_address like "%'.$search_array['keyword'].'%")';
		}
		$result= $this->getAdapter()->select() ->from(array('pl'=>'page_leads'),array('*'))
		->joinLeft(array('lp'=>'landing_pages'),'lp.lp_id=pl.pl_lp_id',array('lp_name'))
		->joinLeft(array('pt'=>'page_templates'),'pt.pt_id=lp.lp_pt_id',array('pt_title','pt_id'))
		->joinLeft(array('pr'=>'price_ranges'),'pr.pr_id=pl.pl_price',array('pr_name'))
		->joinLeft(array('prt'=>'property_types'),'prt.pt_id=pl.pl_property_type',array('pt_name as property_title'))
		->joinLeft(array('ba'=>'bathrooms'),'ba.bt_id=pl.pl_bathrooms',array(''))
		->joinLeft(array('be'=>'bedrooms'),'be.bd_id=pl.pl_bedrooms',array(''))
		->group('pl.pl_id')
		->where($where)
		->order($order);
		return $result;
	
 	}
	public function getContentBlock($id = false){
		
		$this->_name = "content_block";
		
		if($id){
			return $this->select()->where("content_block_id = ? ",$id)->query()->fetch();
		}
		
		return $this->select()->query()->fetchAll();
		
 	}
	
	
	
	/* ================= Static Functions Related To Site Config Table =============================== */
	
	public function getConfigs($type=false){
		$this->_name = "config";
		$where = "1";
		if($type){
			$where = "config_group='".strtoupper($type)."'";
		}
		return $this->select()->where($where)->query()->fetchAll();
	}
	
	
	
	
	/* /////////////////////////////////// END === > Static Functions Related To Site Config Table \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\*/
	
	
	
	/* ================= Static Functions Related To Graphic Media Table =============================== */
	
	public function getMedia($option = false){
		
		$this->_name = "graphic_media";
		
		$result = $this->select();
		
 		if($option){
			$where = "media_id=$option";
			return  $result->where(" media_id = ? ",$option )->query()->fetch();
		}
		 
		return $result->query()->fetchAll();
	}
	
	
	
	
	/* /////////////////////////////////// END === > Static Functions Related To Site Config Table \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\*/
	
	
	
	/* ================= Static Functions Related To Email Templates Table =============================== */
   	public function  getTemplate($param = false){
		$this->_name = "email_templates";
 		if($param){
			if(is_array($param)){
			}else{
				$result = $this->find($param);
				if($result->count())
					return $result->current()->toArray();
				return false ;
			}
		}
	}
	
	/* /////////////////////////////////// END === > Static Functions Related To Email Templates Table \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\*/
	

	
	 
	
	   
	
}