<?php
class LeadController extends Zend_Controller_Action
{
  	private $modelUser ,$modelContent; 
	 
	public function init(){
 		$this->modelStatic = new Application_Model_Static();
 		$this->_helper->layout()->setLayout('lead');
	}
	
	
 	public function homebuyermistakesAction(){
		 global $objSession; 
	
	}
	public function listofhomesAction(){
	 global $objSession; 
	
	}
	public function directmailAction(){
	 global $objSession; 
	
	}
	public function downloadAction(){
	 global $objSession; 
	
	}
	public function currentactivelistingAction(){
	 global $objSession; 
	
	}
	public function homevalueAction(){
	 global $objSession; 
	
	}
	
	public function sellyourhomefortopdollarAction(){
	 global $objSession; 
	
	}
	public function customhomevalueAction(){
	 global $objSession; 
	
	}
	public function agentleadAction(){
	 global $objSession; 
	
	}
	public function agenttoolboxlpAction(){
	 global $objSession; 
	
	}
	public function at1openhouseAction(){
	 global $objSession; 
	
	}
	public function at2banneradstutorialAction(){
	 global $objSession; 
	}
}

