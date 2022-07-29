<?php
class Application_Form_Leadmagent extends Twitter_Bootstrap_Form_Vertical
{
	
	public function init(){
 
  		$this->setMethod('post');
 		
		$this->setAttribs(array(
 			'class' => 'profile_form',
 			'novalidate'=>'novalidate',
			"role"=>"form",
			'enctype'=>'multipart/form-data'
		));
  	}
	
		public function step1(){
		
 		$this->addElement('button', 'submitbtn', array(
			'ignore'   => true,
			'type'=>'submit',
			'label'    => 'I DO NOT WANT TO MAKE THESE MISTAKES!',
			'class'    => 'btn blue btn-primary bttnsubmit hvr-inner-shadow  btn-default btn btn-default site_button site_button1'
		));
		
		$this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
 	}
	
		public function step2(){
		$this->addElement('text', 'pl_name', array (
			'class' => 'form-control',
			"placeholder" => "First Name & Last Name",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
			
  		));
		
		$this->addElement('text', 'pl_email', array (
			'class' => 'form-control required  email',
			"placeholder" => "Email address",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
			
  		));
		
		$this->addElement('text', 'pl_phone', array(
						'class' => 'form-control  middle-element phone_validate',
						'placeholder'   => ' Phone Number',
						'filters'    => array('StringTrim','StripTags'),
        ));
		
 	
 		$this->addElement('button', 'submitbtn', array(
			'ignore'   => true,
			'type'=>'submit',
			'label'    => 'Get My Cheat Sheet Now!',
			'class'    => 'btn blue btn-primary bttnsubmit hvr-inner-shadow  btn-default btn btn-default site_button site_button1'
		));
		
		$this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
 	}

	
	
	
}