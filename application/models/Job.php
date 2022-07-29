<?php
class Application_Model_Job extends Zend_Db_Table_Abstract
{
	protected $_name = 'jobpost';
	
	public function init(){}
 	
	public function getUserPostedJob($id){		
		 	$this->_name = 'jobpost';
			
			 $result = $this->getAdapter()
							->select()
							->from(array('jobpost' => 'jobpost'))
							//->joinLeft(array('b' => 'bid'), 'jobpost.jobpost_id = b.bid_jobid',array('COUNT(bid_id) as bidcount'))
							->where("job_postby = ".$id)
							->group('jobpost.jobpost_id')
							->order('job_added DESC');
							//->query()       
							//->fetchAll();				
			//prd($result);
			return $result;
	}
	
	public function getallJobsStats($id,$status){		
		 	$this->_name = 'jobpost';
			$whr="1!=1";
			if($status=='active')
			{
				$whr="job_postby = '".$id."' and job_status='1'";
			}
			if($status=='live')
			{
				$whr="job_postby = '".$id."' and job_status='1' and private_job = '0'";
			}
			if($status=='applied')
			{
				$result = $this->getAdapter()->fetchAll("SELECT * FROM bid where bid_userid = '".$id."'");
				for($i=0;$i<count($result);$i++)
				{
					$ids[$i]=$result[$i]['bid_jobid'];
				}
				if(isset($ids))
				{
					$imp=implode(',',$ids);
					$whr="jobpost.jobpost_id IN (".$imp.")";
				}
			}
			
			if($status=='awarded')
			{
				$result = $this->getAdapter()->fetchAll('SELECT jobaward_jobid FROM job_award');
				for($i=0;$i<count($result);$i++)
				{
					$ids[$i]=$result[$i]['jobaward_jobid'];
				}
				if(isset($ids))
				{
					$imp=implode(',',$ids);
					$whr="job_postby = '".$id."' and jobpost.jobpost_id IN (".$imp.")";
				}
			}
			$result = $this->getAdapter()
							->select()
							->from(array('jobpost' => 'jobpost'))
							//->joinLeft(array('b' => 'bid'), 'jobpost.jobpost_id = b.bid_jobid',array('COUNT(bid_id) as bidcount'))
							->where(" ".$whr." ")
							->group('jobpost.jobpost_id')
							->order('job_added DESC');
									
			$result =$result ->query()       
							->fetchAll();		
			//prd($result);
			return $result;
	}
	
	public function getAllPostedJob(){	
			//$this->_name='job_award';
			$imp="";$where="";
			/*$result = $this->getAdapter()->fetchAll('SELECT jobaward_jobid FROM job_award');
			for($i=0;$i<count($result);$i++)
			{
				$ids[$i]=$result[$i]['jobaward_jobid'];
			}
			if(isset($ids))
			{
				$imp=implode(',',$ids);
				$where=" and jobpost.jobpost_id NOT IN (".$imp.")";
			}*/
			
		 	$this->_name = 'jobpost';	
			
			$select = $this->getAdapter()->select()->from(array('jobpost' => 'jobpost'))
						->joinLeft(array('u' => 'users'), 'jobpost.job_postby = u.user_id',array('u.user_first_name','u.user_last_name','u.user_image','u.user_id'))
						->where("jobpost.job_status = '1' and jobpost.private_job = '0' ".$where."");
						
			$select=$select->order('jobpost.job_added DESC');
			return $select;
	}
	
	public function jobSearch($posted_data)
	{ 
		    //$this->_name='job_award';
			$imp="";
			/*$result = $this->getAdapter()->fetchAll('SELECT jobaward_jobid FROM job_award');
			for($i=0;$i<count($result);$i++)
			{
				$ids[$i]=$result[$i]['jobaward_jobid'];
			}
			if(isset($ids))
			{
				$imp=implode(',',$ids);	
			}*/
			$whr="";
			//prd($posted_data);
			$this->_name = 'jobpost';
			$select = $this->getAdapter()->select()->from(array('jobpost' => 'jobpost'))
			 ->joinLeft(array('u' => 'users'), 'jobpost.job_postby = u.user_id',array('u.user_first_name','u.user_last_name','u.user_id','u.user_image'));
			$flg=0;$svol='';
			
			/*if($imp!='')
			{
				$whr=" and jobpost.jobpost_id NOT IN (".$imp.")";
			}*/
			
			if(!empty($posted_data['miles']) and !empty($posted_data['location'])){  $flg=1;
				
				if($posted_data['latitude']!='' and $posted_data['longitude']!='')
				{
					$select =$this->getAdapter()->select()->from("jobpost",array('*','distance'=>new Zend_Db_Expr("(((acos(sin((".$posted_data['latitude']."*pi()/180)) * 
					sin((job_latitude*pi()/180))+cos((".$posted_data['latitude']."*pi()/180)) * 
					cos((job_latitude*pi()/180)) * cos(((".$posted_data['longitude']."- job_longitude)* 
					pi()/180))))*180/pi())*60
				)")))
				 ->joinLeft(array('u' => 'users'), 'jobpost.job_postby = u.user_id',array('u.user_first_name','u.user_last_name','u.user_id','u.user_image'))
				->where("(((acos(sin((".$posted_data['latitude']."*pi()/180)) * 
					sin((job_latitude*pi()/180))+cos((".$posted_data['latitude']."*pi()/180)) * 
					cos((job_latitude*pi()/180)) * cos(((".$posted_data['longitude']."- job_longitude)* 
					pi()/180))))*180/pi())*60
				)<='".$posted_data['miles']."' ".$whr." and jobpost.job_status='1' and jobpost.private_job = '0'")->order("distance"); 
				//echo $select; die;
				}
				else
				{
					$select =$select->where("jobpost.job_status='1' and jobpost.private_job = '0' ".$whr."");
				}
			}
			
			if(!empty($posted_data['user_job_sector'])){  $flg=1;
				$jobSectors = $posted_data['user_job_sector'];		
				$select =$select->where("job_industry ='".$jobSectors."'  and jobpost.job_status='1' and jobpost.private_job = '0'  ".$whr."");//->__toString();								
				   	//->query()					
				   	//->fetchAll();
			}
			
			if(!empty($posted_data['user_skills'])){  $flg=1;
				$jobskills = $posted_data['user_skills'];		
				$select =$select->where("job_department ='".$jobskills."'  and jobpost.job_status='1' and jobpost.private_job = '0'  ".$whr."");//->__toString();								
				   	//->query()					
				   	//->fetchAll();
			}
			
			if(!empty($posted_data['price_range']))
			{
				$price=$posted_data['price_range'];
				if($price!='' and $price!=0 and $price!=1000)
				{
					$select =$select->where("job_fee<='".$price."'  and jobpost.job_status='1' and jobpost.private_job='0' ".$whr."");
				}
			}
			
			if(!empty($posted_data['keyword_search'])){  $flg=1;
				$joinTableName='department';
				$joinCondition='jobpost.job_department=department.department_id';
				$keyword_search = $posted_data['keyword_search'];
				$select = $select->joinLeft($joinTableName,$joinCondition)->joinLeft("category","jobpost.job_industry=category.category_id")->where("(job_title LIKE ('%".trim($keyword_search)."%') or department_title LIKE ('%".trim($keyword_search)."%') or category_title LIKE ('%".trim($keyword_search)."%')) ".$whr." and jobpost.job_status='1' and jobpost.private_job = '0'");//->__toString();	
					//->query()					
					//->fetchAll();
				//prd($res);
			}
			
			if($flg=='0')
			{
				$select =$select->where("jobpost.job_status='1' and jobpost.private_job = '0' ".$whr."");
			}
			
			if(isset($posted_data['sort_order']) && trim($posted_data['sort_order'])=='ASC')
			{
				$select=$select->order("jobpost.job_added ASC");
			}
			else
			{
				$select=$select->order("jobpost.job_added DESC");
			}
			
		return $select;
 	}	
	
	public function deletePostedJob($id){
		$this->_name = 'jobpost';
		$res = $this->delete('jobpost_id IN('.$id.')');
		return $res;
	} 
	
	public function jobdata($job_id)
	{
				$select = $this->getAdapter()->select()->from(array('jobpost' => 'jobpost'))
						
						->joinLeft(array('u' => 'users'), 'jobpost.job_postby = u.user_id',array('u.user_first_name','u.user_last_name','u.user_id','u.user_image'))
						->joinLeft(array('d' => 'department'), 'jobpost.job_department = d.department_id',array('d.department_title','d.department_id'))
						->joinLeft(array('c' => 'category'), 'jobpost.job_industry = c.category_id',array('c.category_title','c.category_id'))
						->where('jobpost_id="'.$job_id.'"');
						$job_data=array();
						$job_data=$select->query()->fetch();
						return $job_data;
	}
	
	public function getBidInformation($bid_id){		
		 	$this->_name = 'bid';
			$result = $this->getAdapter()->select()->from(array('b' => 'bid'))
							->joinLeft(array('jp' => 'jobpost'), 'jp.jobpost_id = b.bid_jobid',array('jp.job_postby','jp.jobpost_id','jp.job_title'))	
                            ->joinLeft(array('u' => 'users'), 'b.bid_userid = u.user_id',array('u.user_id','u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address'))
							->where("b.bid_id = ".$bid_id)
							->order('b.bid_added DESC') 			                             
							->query()->fetch();
			//prd($result);
			
			return $result;
	}
	
	public function getSendCV($award_id){		
		 	$this->_name = 'send_cv';		
			$result = $this->getAdapter()->select()->from(array('sv' => 'send_cv'))
							->joinLeft(array('ja' => 'job_award'), 'ja.jobaward_id = sv.awarded_id')	
							->joinLeft(array('b' => 'bid'), 'ja.jobaward_bidid = b.bid_id')	
							->joinLeft(array('jp' => 'jobpost'), 'ja.jobaward_jobid = jp.jobpost_id')		
							->joinLeft(array('u' => 'users'), 'sv.candidate = u.user_id',array('u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address','u.user_id','u.user_cv'))				
							->where("sv.awarded_id = ".$award_id)
							->query()->fetchAll();
	        return $result;
	}
	
	public function getCVdetail($cv_id){		
		 	$this->_name = 'send_cv';		
			$result = $this->getAdapter()->select()->from(array('sv' => 'send_cv'))
							->joinLeft(array('ja' => 'job_award'), 'ja.jobaward_id = sv.awarded_id')	
							->joinLeft(array('b' => 'bid'), 'ja.jobaward_bidid = b.bid_id')	
							->joinLeft(array('jp' => 'jobpost'), 'ja.jobaward_jobid = jp.jobpost_id')		
							->joinLeft(array('u' => 'users'), 'sv.candidate = u.user_id',array('u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address','u.user_id','u.user_cv'))				
							->where("sv.send_cv_id = ".$cv_id)
							->query()->fetch();
	        return $result;
	}
		
	public function getAwardedJob($award_id){		
		 	$this->_name = 'job_award';			
			$result = $this->getAdapter()->select()->from(array('ja' => 'job_award'))
							->joinLeft(array('b' => 'bid'), 'ja.jobaward_bidid = b.bid_id')	
							->joinLeft(array('jp' => 'jobpost'), 'ja.jobaward_jobid = jp.jobpost_id')		
							->joinLeft(array('u' => 'users'), 'b.bid_userid = u.user_id',array('u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address','u.user_id'))				
							->where("ja.jobaward_id = ".$award_id)
							->query()->fetch();
	        return $result;
	}
		
	public function getGuruAwardedJob($user_id){		
		 	$this->_name = 'job_award';
			$result = $this->getAdapter()->select()->from(array('ja' => 'job_award'))
                            ->joinLeft(array('b' => 'bid'), 'ja.jobaward_bidid = b.bid_id',array('b.bid_jobid','b.bid_id','b.bid_userid','b.bid_amount'))							
						    ->joinLeft(array('jp' => 'jobpost'), 'b.bid_jobid = jp.jobpost_id',array('jp.jobpost_id','jp.job_postby','jp.job_title','jp.job_location','jp.job_estimationcost','jp.job_description'))
							->joinLeft(array('u' => 'users'), 'jp.job_postby = u.user_id',array('u.user_id','u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address'))							
							->where("b.bid_userid = '".$user_id."'")
							//->order('b.bid_added DESC');//->__toString();	
							->order('ja.jobaward_added DESC');				                             
							//->query()->fetchAll();
			//echo $result;
			//prd($result);
			
			return $result;
	}	
	
	public function getallGuruAwardedJob($user_id){		
		 	$this->_name = 'job_award';
			$result = $this->getAdapter()->select()->from(array('ja' => 'job_award'))
                            ->joinLeft(array('b' => 'bid'), 'ja.jobaward_bidid = b.bid_id',array('b.bid_jobid','b.bid_id','b.bid_userid','b.bid_amount'))							
						    ->joinLeft(array('jp' => 'jobpost'), 'ja.jobaward_jobid = jp.jobpost_id',array('jp.jobpost_id','jp.job_postby','jp.job_title','jp.job_location','jp.job_description'))
							->joinLeft(array('u' => 'users'), 'b.bid_userid = u.user_id',array('u.user_id','u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address'))							
							->where("jp.job_postby = ".$user_id)
							->order('ja.jobaward_added DESC');//->__toString();					                             
							//->query()->fetchAll();
			//prd($result);
			
			return $result;
	}
	
	public function getAllJobsBids($jobpost_id){		
		 	$this->_name = 'bid';
			$result = $this->getAdapter()->select()->from(array('b' => 'bid'))
							->joinLeft(array('jp' => 'jobpost'), 'jp.jobpost_id = b.bid_jobid')	
                            ->joinLeft(array('u' => 'users'), 'b.bid_userid = u.user_id',array('u.user_id','u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address'))
							->where("b.bid_jobid = ".$jobpost_id)
							->order('b.bid_added DESC');//->__toString();					                             
							//->query()->fetchAll();
			//prd($result);
			//echo $result; die;
			return $result;
	}
	
	public function getpmbData($where)
	{
		  $result = $this->getAdapter()->select()->from(array('p' => 'pmb'))
						->joinLeft(array('u' => 'users'), 'p.msg_by = u.user_id')
						->where($where)
						->order('p.pmb_id DESC')->query()							
						->fetchAll();
		 return $result;
	}
	
	public function addanything($table,$data)
	{
		$record = $this->getAdapter()->insert($table,$data);   
		return $record;
	}
	
	 public function getsingleDataorder()
	   {
		   
		   $condition="users.user_id=pmb.msg_by";
		   $record = $this->getAdapter()->select()
				  ->from('users',array('user_id','user_first_name'))
				  ->order("pmb_id DESC")
				  ->limit(1)
				  ->joinInner('pmb',$condition)
				  ->query()							
				  ->fetch();
					
		  return $record;
	  }
	  
	  public function getreplystatus($msg_by,$msg_to,$bid_id)
		{
			
			$where="msg_by='".$msg_by."' and msg_to='".$msg_to."' and bid='".$bid_id."'";
		
			$record = $this->getAdapter()->select()
					   ->from('pmb')
					    ->where($where)
					   ->query()
					   ->fetchAll();
					  return $record;
		}
	 
	public function getAllPostedBids($user_id){		
		 	$this->_name = 'bid';
			$result = $this->getAdapter()->select()->from(array('b' => 'bid'))
                            ->joinLeft(array('jp' => 'jobpost'), 'b.bid_jobid = jp.jobpost_id',array('jp.jobpost_id','jp.job_postby','jp.job_title','jp.job_location','jp.job_estimationcost','jp.job_description','jp.job_fee'))
							->joinLeft(array('u' => 'users'), 'jp.job_postby = u.user_id',array('u.user_id','u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address'))							
							->where("b.bid_userid = ".$user_id)
							->order('b.bid_added DESC');//->__toString();					                             
							//->query()->fetchAll();
			//prd($result);
			
			return $result;
	}

	public function getData($table,$where,$status)
	{
		if($status==1)
		{
		  $result = $this->getAdapter()->select()->from(array('b' => 'bid'))
						->joinLeft(array('j' => 'jobpost'), 'b.bid_jobid= j.jobpost_id',array('j.job_postby'))
						->joinLeft(array('u' => 'users'), 'b.bid_userid = u.user_id',array('u.user_id','u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address'))
						->where($where)
						->order('b.bid_added DESC')->query()							
						->fetch();
		}
		else
		{
			
			 $result = $this->getAdapter()->select()->from(array('b' => 'bid'))
						->joinLeft(array('j' => 'jobpost'), 'b.bid_jobid= j.jobpost_id',array('j.job_postby'))
						->joinLeft(array('u' => 'users'), 'j.job_postby= u.user_id',array('u.user_id','u.user_first_name','u.user_last_name','u.user_image','u.user_email','u.user_address'))
						->where($where)
						->order('b.bid_added DESC')->query()							
						->fetch();
						
		}
		 return $result;
	}
		
	public function getallcandidatedata($job_id)
	{
		$select = $this->getAdapter()->select()->from(array('job_candidates' => 'job_candidates'))
												->where('j_c_jobid="'.$job_id.'"')
												->joinLeft(array('u' => 'users'), 'job_candidates.j_c_userid = u.user_id',array('u.user_first_name','u.user_last_name','u.user_id','u.user_image','u.user_cv'));
		
		$candidate_data=array();
		$candidate_data=$select->query()->fetchAll();
		return $candidate_data;
		
	}
	
	public function getrecruiterdata($user_id)
	{
		$select = $this->getAdapter()->select()->from(array('u1' => 'users'),array('u1.user_first_name as candidate_name','u1.user_id as candidate_userid'))
												->where('u1.user_id="'.$user_id.'"')
												->joinLeft(array('u2' => 'users'), 'u2.user_id = u1.user_parent_id',array('u2.user_paypal_email as recruiter_paypal_email','u2.user_id as recruiter_userid','u2.user_email as recruiter_email'));
		//echo $select;die;
		$recruiter_data=array();
		$recruiter_data=$select->query()->fetch();
		return $recruiter_data;
		
	}
	
	
	
}