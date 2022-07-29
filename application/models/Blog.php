<?php
class Application_Model_Blog extends Application_Model_SuperModel
{
	protected $_name = "blogs";
	
	
	
	 
	
	public function getBlogAllData($bId="",$admin=false){	
	 
	if(!empty($bId)) {
		$condition="blog_id='".$bId."' and blog_status='1'";
	} else {
		$condition = "blog_status='1'";
		}
		
		$query= $this->getAdapter()
							->select()
							->from("blogs")
							->where($condition)
							->order('blog_id DESC'); 
      
	   if($admin){
			$query=  $query->query()->fetch();
	  	 
	   }
		
		return $query;
			 
					
		/*$blogData=$query->query()->fetchAll();					*/
		 
	}
	
	public function getBlogData($bId=""){	
	 
	if(!empty($bId)) {
		$condition="blog_id='".$bId."' and blog_status='1'";
	} else {
		$condition = "blog_status='1'";
		}
		$query= $this->getAdapter()
							->select()
							->from("blogs")
							->where($condition)
							->order('blog_id ASC'); 
       
		$blog = $query->query()->fetchAll();
		return $blog;
			 
					
		/*$blogData=$query->query()->fetchAll();					*/
		 
	}
	
	
	 
	 public function blogSearch($search_key) {
		 
		 $condition ="blog_status='1'";
		 $query= $this->getAdapter()
							->select()
							->from("blogs")
							->where("blog_title LIKE '%".$search_key."%' or blog_content LIKE '%".$search_key."%' or blog_label LIKE '%".$search_key."%' and blog_status='1'")
							->order('blog_id ASC'); 
       
		//$blogData=$query->query()->fetchAll();
		 
		return $query;
		 }
	 
		
	
	function getBlogCommentData($bId){	
		
		$condition="blog_comment_status='1'";
		
		if($bId!=false){
			$condition.=" and blog_comment_blog_id=".$bId;
		}
		
		
 		$blogCommentData= $this->getAdapter()
							->select()
							->from("blog_comments")
							->where($condition)
							->query()
							->fetchAll();
							  
							
  		return $blogCommentData;
	}	
	public function getAllCategory(){	
		$blogData= $this->getAdapter()
							->select()
							->from(TABLE_BLOGS)
							->query()
							->fetchAll();
		
		return $blogCategoryData;
	}
	
	 
 
	public function getblogCommeent($userid= false) {
		
		
		$this->_name = TABLE_BLOG_COMMENTS;
		$condition="blog_category_status=1 and blog_status='1' ";
		$query =$this->getAdapter()->select()
								->from(array('c'=>TABLE_BLOG_COMMENTS))
								->join(array('b'=>TABLE_BLOGS) , 'c.blog_comment_blog_id=b.blog_id')
								->join(array('bc'=>TABLE_BLOG_CATEGORY) , 'b.blog_blog_category_id=bc.blog_category_id')
								->where('blog_comment_user_id='.$userid.'')
								->where($condition)
								->group('blog_id');
	 		
		return $query;
		
	} 
		
  
}
