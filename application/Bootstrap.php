<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	

 	protected function _initLoaderResources()
    {
		
        $this->getResourceLoader()->addResourceType('controller', 'controllers/', 'Controller');
    }
	
	protected function _initAutoloader()
 	{
 	   new Zend_Application_Module_Autoloader(array(
 	      'namespace' => 'Application',
 	      'basePath'  => APPLICATION_PATH,
 	   ));
 	}
	
	    protected function _initHelperPath() {
        $view = $this->bootstrap('view')->getResource('view');
        $view->setHelperPath(APPLICATION_PATH . '/views/helpers', 'Application_View_Helper');
    }
	
	protected function _initDoctype()
	{
		$this->bootstrap('view');
 		$view = $this->getResource('view');
  		$view->setEncoding('UTF-8');
		$view->doctype('HTML5');
 		$view->headMeta()->appendHttpEquiv('Content-Type',  'text/html;charset=utf-8');
	}
	

	
	protected function _initDB() {
	
		$dbConfig = new Zend_Config_Ini(ROOT_PATH.'/private/db.ini',APPLICATION_ENV);
		$dbConfig =$dbConfig->resources->db;
	 
       	$dbAdapter = Zend_Db::factory($dbConfig->adapter, array(
            'host'     => $dbConfig->params->hostname,
            'username' => $dbConfig->params->username,
            'password' => $dbConfig->params->password,
            'dbname'   => $dbConfig->params->dbname
         ));
 		
		//$dbAdapter->exec("SET time_zone='".$dbConfig->params->timezone."'");
		
        Zend_Db_Table_Abstract::setDefaultAdapter($dbAdapter);

        Zend_Registry::set('db', $dbAdapter);
		 
 		
		Zend_Session::start();
		global $objSession;
		$objSession = new Zend_Session_Namespace('default');
    }
 	
	protected function _initAppKeysToRegistry(){
		$appkeys = new Zend_Config_Ini(ROOT_PATH . '/private/appkeys.ini');
		Zend_Registry::set('keys', $appkeys);
	}
	

	public function _initPlugins(){ // Add Plugin path
		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new Application_Plugin_SetLayout());
	}


		protected  function _initApplication(){   
	 
 			$this->FrontController=Zend_Controller_Front::getInstance();
			$this->FrontController->setControllerDirectory(array(
				'default' => '../application/controllers',
				'admin'    => '../application/admin/controllers'
			));
			
			// $this->FrontController->setDefaultControllerName('login'); 
			//	$this->FrontController->throwExceptions(false);
		
			$registry = Zend_Registry::getInstance();
			$registry->set("flash_error",false);
				
		 	// Add a 'foo' module directory:
			// $this->FrontController->setParam('prefixDefaultModule', true);
			// $this->FrontController->setDefaultModule('publisher');
			// $this->FrontController->setDefaultAction("index") ;
			// $this->FrontController->addControllerDirectory('../modules/foo/controllers', 'foo');
			
	 	
	}
	
	
	public function _initRouter()
        {
            $this->FrontController = Zend_Controller_Front::getInstance();
            $this->router = $this->FrontController->getRouter();
            $this->appRoutes = array ();
			
        }
	
	
	/* Site Routers */
	protected function _initSiteRouters(){
		
		
		
		$this->appRoutes['front_page'] = new Zend_Controller_Router_Route('', array ('module' => 'default','controller' => 'index','action' => 'index'));
			$this->appRoutes['price_page'] = new Zend_Controller_Router_Route('pricing', array ('module' => 'default','controller' => 'index','action' => 'pricing'));
		$this->appRoutes['forgeotpassowrd'] = new Zend_Controller_Router_Route('forgeotpassowrd', array ('module' => 'default','controller' => 'index','action' => 'index'));
				/* Fixed Front Redirects */
				$this->appRoutes['buyer_temp_new'] = new Zend_Controller_Router_Route('home-valuation-step1/:lp_id/:type', array ('module' => 'default','controller' => 'homevaluation','action' =>'step1','lp_id'=>'','type'=>''));
		$this->appRoutes['front_login'] = new Zend_Controller_Router_Route('login', array('module' => 'default','controller' => 'user','action' => 'login'));
		$this->appRoutes['front_changepassword'] = new Zend_Controller_Router_Route('change-password', array ('module' => 'default','controller' => 'user','action' => 'changepassword'));
		$this->appRoutes['front_logout'] = new Zend_Controller_Router_Route('logout', array ('module' => 'default','controller' => 'user','action' => 'logout'));
		$this->appRoutes['front_register'] = new Zend_Controller_Router_Route('register', array('module' => 'default','controller' => 'user','action' => 'register'));
		$this->appRoutes['front_forgotpassword'] = new Zend_Controller_Router_Route('forgot-password', array ('module' => 'default','controller' => 'user','action' => 'forgotpassword'));
		$this->appRoutes['facebook_signup'] = new Zend_Controller_Router_Route('social/fblogin', array ('module' => 'default','controller' => 'social','action' => 'fblogin'));
		$this->appRoutes['twitter_signup'] = new Zend_Controller_Router_Route('social/twitterlogin', array ('module' => 'default','controller' => 'social','action' => 'twitterlogin'));
		$this->appRoutes['faq'] = new Zend_Controller_Router_Route('support', array ('module'=>'default','controller'=>'static','action'=>'faq'));
		//$this->appRoutes['tutorial'] = new Zend_Controller_Router_Route('tutorial', array ('module'=>'default','controller'=>'static','action'=>'tutorial'));
$this->appRoutes['tutorial'] = new Zend_Controller_Router_Route('tutorial/:page', array ('module'=>'default','controller'=>'static','action'=>'tutorial','page'=>'/d+'));


		$this->appRoutes['about_us'] = new Zend_Controller_Router_Route('about-us', array ('module'=>'default','controller'=>'static','action'=>'index','page_id'=>'1'));
		
		$this->appRoutes['privacy'] = new Zend_Controller_Router_Route('privacy', array ('module'=>'default','controller'=>'static','action'=>'index','page_id'=>'2'));
		
		$this->appRoutes['terms_employer'] = new Zend_Controller_Router_Route('terms-conditions', array ('module'=>'default','controller'=>'static','action'=>'index','page_id'=>'3'));
	
		$this->appRoutes['contact_us'] = new Zend_Controller_Router_Route('contact-us', array ('module'=>'default','controller'=>'static','action'=>'contact'));
		
		$this->appRoutes['front_profile'] = new Zend_Controller_Router_Route('profile', array ('module'=>'default','controller'=>'profile','action'=>'index'));
		$this->appRoutes['front_image'] = new Zend_Controller_Router_Route('change-avatar', array ('module'=>'default','controller'=>'profile','action'=>'image'));
		$this->appRoutes['front_image_crop'] = new Zend_Controller_Router_Route('crop-image', array ('module'=>'default','controller'=>'profile','action'=>'cropimage'));
		
		$this->appRoutes['change_password'] = new Zend_Controller_Router_Route('change-password', array ('module'=>'default','controller'=>'profile','action'=>'password'));
		
		$this->appRoutes['user_cart'] = new Zend_Controller_Router_Route('my-cart', array ('module'=>'default','controller'=>'cart','action'=>'index'));
		$this->appRoutes['create_page'] = new Zend_Controller_Router_Route('landing-page-templates', array ('module'=>'default','controller'=>'landingpages','action'=>'index'));
		$this->appRoutes['landing_pages'] = new Zend_Controller_Router_Route('landing-pages', array ('module'=>'default','controller'=>'landingpages','action'=>'allpages'));
		  $this->appRoutes['add_landing_page'] = new Zend_Controller_Router_Route('add-landing-page/:pt_id', array ('module' => 'default','controller' => 'landingpages','action' =>'add'));
		  $this->appRoutes['add_custom_landing_page'] = new Zend_Controller_Router_Route('add-custom-landing-page/:pt_id', array ('module' => 'default','controller' => 'landingpages','action' =>'addcustompage'));
		  $this->appRoutes['edit_custom_landing_page'] = new Zend_Controller_Router_Route('update-custom-landing-page/:lp_id', array ('module' => 'default','controller' => 'landingpages','action' =>'editcustompage'));
		  
		  $this->appRoutes['edit_buyer_landing_page'] = new Zend_Controller_Router_Route('update-buyer-landing-page/:lp_id', array ('module' => 'default','controller' => 'landingpages','action' =>'addnewbuyertemp','lp_id'=>''));
		   $this->appRoutes['edit_landing_page'] = new Zend_Controller_Router_Route('update-landing-page/:lp_id', array ('module' => 'default','controller' => 'landingpages','action' =>'edit'));
		  
		  $this->appRoutes['home_valuation_1'] = new Zend_Controller_Router_Route('home-valuation-step1/:lp_id/:type', array ('module' => 'default','controller' => 'homevaluation','action' =>'step1','lp_id'=>'','type'=>''));
		  
		  $this->appRoutes['custom_home_valuation_1'] = new Zend_Controller_Router_Route('custom-home-valuation-step1/:lp_id/:type', array ('module' => 'default','controller' => 'customhomevaluation','action' =>'step1','lp_id'=>'','type'=>''));
		  
		  $this->appRoutes['custom_buyer_1'] = new Zend_Controller_Router_Route('custom-buyer/:lp_id/:type', array ('module' => 'default','controller' => 'custombuyer','action' =>'step1','lp_id'=>'','type'=>''));
		  
		  $this->appRoutes['home_valuation_2'] = new Zend_Controller_Router_Route('home-valuation-step2/:lp_id', array ('module' => 'default','controller' => 'homevaluation','action' =>'step2','lp_id'=>''));
		  $this->appRoutes['home_valuation_3'] = new Zend_Controller_Router_Route('home-valuation-step3/:lp_id', array ('module' => 'default','controller' => 'homevaluation','action' =>'step3','lp_id'=>''));
		  	  $this->appRoutes['lead_magnet_1'] = new Zend_Controller_Router_Route('buyer-lead-magnet-step1/:lp_id/:type', array ('module' => 'default','controller' => 'leadmagent','action' =>'step1','lp_id'=>'','type'=>''));
		  $this->appRoutes['lead_magnet_2'] = new Zend_Controller_Router_Route('buyer-lead-magnet-step2/:lp_id', array ('module' => 'default','controller' => 'leadmagent','action' =>'step2','lp_id'=>''));
		  $this->appRoutes['lead_magnet_3'] = new Zend_Controller_Router_Route('buyer-lead-magnet-step3/:lp_id', array ('module' => 'default','controller' => 'leadmagent','action' =>'step3','lp_id'=>''));
		  
		   	  $this->appRoutes['lead_magnet2_1'] = new Zend_Controller_Router_Route('buyer-lead-magnet-2-step1/:lp_id/:type', array ('module' => 'default','controller' => 'leadmagenttwo','action' =>'step1','lp_id'=>'','type'=>''));
		  $this->appRoutes['lead_magnet2_2'] = new Zend_Controller_Router_Route('buyer-lead-magnet-2-step2/:lp_id', array ('module' => 'default','controller' => 'leadmagenttwo','action' =>'step2','lp_id'=>''));
		  $this->appRoutes['lead_magnet2_3'] = new Zend_Controller_Router_Route('buyer-lead-magnet-2-step3/:lp_id', array ('module' => 'default','controller' => 'leadmagenttwo','action' =>'step3','lp_id'=>''));
		  
		  
		     	  $this->appRoutes['seller_magnet_1'] = new Zend_Controller_Router_Route('seller-lead-magnet-step1/:lp_id/:type', array ('module' => 'default','controller' => 'sellermagent','action' =>'step1','lp_id'=>'','type'=>''));
		  $this->appRoutes['seller_magnet_2'] = new Zend_Controller_Router_Route('seller-lead-magnet-step2/:lp_id', array ('module' => 'default','controller' => 'sellermagent','action' =>'step2','lp_id'=>''));
		  $this->appRoutes['seller_magnet_3'] = new Zend_Controller_Router_Route('seller-lead-magnet-step3/:lp_id', array ('module' => 'default','controller' => 'sellermagent','action' =>'step3','lp_id'=>''));
		//$this->appRoutes['search'] = new Zend_Controller_Router_Route('search', array ('module'=>'default','controller'=>'search','action'=>'index'));
	/* Custom templates */	
		 $this->appRoutes['custom_templates_listing'] = new Zend_Controller_Router_Route('custom-templates', array ('module' => 'default','controller' => 'landingpages','action' =>'customlisting'));
		  $this->appRoutes['cusom_page_1'] = new Zend_Controller_Router_Route('custom-1-step-1/:lp_id/:type', array ('module' => 'default','controller' => 'customtemp1','action' =>'step1','lp_id'=>'','type'=>''));
		   $this->appRoutes['add_custom_page'] = new Zend_Controller_Router_Route('add-custom-page/:pt_id/:lp_id', array ('module' => 'default','controller' => 'landingpages','action' =>'customadd','lp_id'=>''));
		    $this->appRoutes['publish_custom_page'] = new Zend_Controller_Router_Route('publish-custom-page/:lp_id', array ('module' => 'default','controller' => 'landingpages','action' =>'custompublish','lp_id'=>''));
			 $this->appRoutes['custom_page_bg_image'] = new Zend_Controller_Router_Route('change-bg-image/:lp_id', array ('module' => 'default','controller' => 'landingpages','action' =>'custombgimage','lp_id'=>''));
			 //custom temp 2
			 $this->appRoutes['cusom_page_2'] = new Zend_Controller_Router_Route('custom-2-step-1/:lp_id/:type', array ('module' => 'default','controller' => 'customtemp2','action' =>'step1','lp_id'=>'','type'=>''));
			 //custom temp 3
			 $this->appRoutes['cusom_page_3'] = new Zend_Controller_Router_Route('custom-3-step-1/:lp_id/:type', array ('module' => 'default','controller' => 'customtemp3','action' =>'step1','lp_id'=>'','type'=>''));
			  $this->appRoutes['cusom_page_4'] = new Zend_Controller_Router_Route('custom-4-step-1/:lp_id/:type', array ('module' => 'default','controller' => 'customtemp4','action' =>'step1','lp_id'=>'','type'=>''));
			  $this->appRoutes['cusom_page_5'] = new Zend_Controller_Router_Route('custom-5-step-1/:lp_id/:type', array ('module' => 'default','controller' => 'customtemp5','action' =>'step1','lp_id'=>'','type'=>''));
		/* Routings For Product Categories  */
		
		$this->modelSuperModel = new Application_Model_SuperModel();
		$userData=$this->modelSuperModel->Super_Get("landing_pages","1","fetchAll",array('field'=>'lp_id,lp_url','lp_pt_id'));
	
      foreach($userData as $key=>$values){
		  
	if($values['lp_pt_id']==2)
	{
		$con='homevaluation';
	}
	else if($values['lp_pt_id']==1)
	{
		$con='leadmagent';
	}
	else if($values['lp_pt_id']==3)
	{
		$con='leadmagenttwo';
	}
	else if($values['lp_pt_id']==4)
	{
		$con='sellermagent';
	}
	else if($values['lp_pt_id']==5)
	{
		$con='customhomevaluation';
	}
	else if($values['lp_pt_id']==6)
	{
		$con='custombuyer';
	}
	else if($values['lp_pt_id']==7)
	{
		$con='customtemp1';
	}
	else if($values['lp_pt_id']==8)
	{
		$con='customtemp2';
			
	}else if($values['lp_pt_id']==9)
	{
		$con='customtemp3';
			
	}else if($values['lp_pt_id']==10)
	{
		$con='customtemp4';
			
	}else if($values['lp_pt_id']==11)
	{
		$con='customtemp5';
			
	}
    $owner_profile_url=($values['lp_url']);
	//prd($owner_profile_url);
	
		
	$this->appRoutes[$owner_profile_url] = new Zend_Controller_Router_Route($owner_profile_url, array ('module'=>'default','controller'=>$con,'action'=>'step1','name'=>$owner_profile_url,'lp_id'=>$values['lp_id']));
  $this->appRoutes[$owner_profile_url.'-1'] = new Zend_Controller_Router_Route($owner_profile_url.'-1', array ('module'=>'default','controller'=>$con,'action'=>'step1','name'=>$owner_profile_url,'lp_id'=>$values['lp_id']));
    $this->appRoutes[$owner_profile_url.'-2'] = new Zend_Controller_Router_Route($owner_profile_url.'-2', array ('module'=>'default','controller'=>$con,'action'=>'step2','name'=>$owner_profile_url,'lp_id'=>$values['lp_id']));
	  $this->appRoutes[$owner_profile_url.'-3'] = new Zend_Controller_Router_Route($owner_profile_url.'-3', array ('module'=>'default','controller'=>$con,'action'=>'step3','name'=>$owner_profile_url,'lp_id'=>$values['lp_id']));
	  
   }
		
	
		
		$blog_Data = $this->modelSuperModel->Super_Get('blogs','blog_status="1"','fetchall',array('fields'=>array('blog_id','blog_title')));
		
		foreach($blog_Data as $value){	 
		
			$this->appRoutes['blog_'.$value['blog_id']]= new Zend_Controller_Router_Route('blog/'.get_seo_url(trim($value['blog_title'])),
                                     				array(
													'module'     => 'default', 
													'controller' => 'blog',
													'action' => 'blog',
													'blogId' => $value['blog_id'],
													)
													 

			);
		}
		/* Lead pages Layouts*/
		 	$this->appRoutes['homebuyer_mistakes'] = new Zend_Controller_Router_Route('homebuyer-mistakes', array ('module'=>'default','controller'=>'lead','action'=>'homebuyermistakes'));
			$this->appRoutes['list_of_homes'] = new Zend_Controller_Router_Route('list-of-homes', array ('module'=>'default','controller'=>'lead','action'=>'listofhomes'));
			$this->appRoutes['home_value'] = new Zend_Controller_Router_Route('home-value', array ('module'=>'default','controller'=>'lead','action'=>'homevalue'));
			$this->appRoutes['sell_your_home_for_top_dollar'] = new Zend_Controller_Router_Route('sell-your-home-for-top-dollar', array ('module'=>'default','controller'=>'lead','action'=>'sellyourhomefortopdollar'));
			
			$this->appRoutes['custom_home_value'] = new Zend_Controller_Router_Route('custom-home-value', array ('module'=>'default','controller'=>'lead','action'=>'customhomevalue'));
			
			$this->appRoutes['agent_lead'] = new Zend_Controller_Router_Route('agent-lead', array ('module'=>'default','controller'=>'lead','action'=>'agentlead'));
			
			$this->appRoutes['agent_toolbox_lp'] = new Zend_Controller_Router_Route('agent-toolbox-lp', array ('module'=>'default','controller'=>'lead','action'=>'agenttoolboxlp'));
			
			$this->appRoutes['at_1_open_house'] = new Zend_Controller_Router_Route('at-1-open-house', array ('module'=>'default','controller'=>'lead','action'=>'at1openhouse'));
			
				$this->appRoutes['at_2_banner_ads_tutorial'] = new Zend_Controller_Router_Route('at-2-banner-ads-tutorial', array ('module'=>'default','controller'=>'lead','action'=>'at2banneradstutorial'));
		$this->appRoutes['current_list'] = new Zend_Controller_Router_Route('current-active-listings', array ('module'=>'default','controller'=>'lead','action'=>'currentactivelisting'));
				$this->appRoutes['direct_mail'] = new Zend_Controller_Router_Route('direct-mail', array ('module'=>'default','controller'=>'lead','action'=>'directmail'));
				$this->appRoutes['download_post'] = new Zend_Controller_Router_Route('download-post-card', array ('module'=>'default','controller'=>'lead','action'=>'download'));

		$db = Zend_Registry::get('db');
		

	}
	
	

	protected function _initSetupRouting(){
		foreach ($this->appRoutes as $key => $cRouter)
		{
			$this->router->addRoute($key, $cRouter);
		}
		
/*			prd($this);*/
	}
	
	protected function _initTranslator()
	{
			
		$enLangData = require_once(ROOT_PATH.'/private/languages/en.php');
		$deLangData = require_once(ROOT_PATH.'/private/languages/fr.php');
 		$translate = new Zend_Translate(
			array(
				'adapter' => 'array',
				'content' => $enLangData,
				'locale'  => 'en',
			)
		);
		$translate->addTranslation(
			array(
				'content' => $deLangData,
				'locale'  => 'fr',
				'clear'   => true
			)
		);
		if(SITE_STAGE == "development"){
			$translate->setLocale('en');
		}else{
			$translate->setLocale('fr');
		}
		
		Zend_Registry::set('Zend_Translate', $translate);
		 
	}
	
	
  
}





/* ------------------------------------------- Functions ---------------------------------  */
function prepareQuery($args){
	$sql=$args[0];
	 $_sqlSplit = preg_split('/(\?|\:[a-zA-Z0-9_]+)/', $sql, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
$params=0;	
	 foreach ($_sqlSplit as $key => $val) {
            if ($val == '?') {
				$_sqlSplit[$key]=$args[1][$params];
				$params++;
			}
	 }
	 
$query=implode($_sqlSplit);	 
	return($query);
}	