<?php
class Application_Form_Customhomevalue extends Twitter_Bootstrap_Form_Vertical
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
		$this->addElement('hidden', 'homeval_Lat', array ());
			$this->addElement('hidden', 'homeval_Lng', array ());
		$this->addElement('text', 'pl_address', array (
			'class' => 'form-control required',
			'required'=>true,
			"placeholder" => "Enter Your Home Address",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
			"validators" =>  array(
								array("NotEmpty",true,array("messages"=>" This field is Required ")),
 							),
  		));
		
 	
 		$this->addElement('button', 'submitbtn', array(
			'ignore'   => true,
			'type'=>'submit',
			'label'    => 'I WANT MY HOME VALUE NOW!',
			'class'    => 'btn blue btn-primary bttnsubmit hvr-inner-shadow  btn-default btn btn-default site_button site_button1'
		));
		
		$this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
 	}
	
		public function step2(){
		$this->addElement('text', 'pl_name', array (
			'class' => 'form-control required',
			'required'=>true,
			"placeholder" => "Enter First Name & Last Name",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
			"validators" =>  array(
								array("NotEmpty",true,array("messages"=>" This field is Required ")),
 							),
  		));
		$this->addElement('text', 'pl_email', array (
			'class' => 'form-control email required',
			"placeholder" => "Enter Your Email Address",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
			"validators" =>  array(
								array("NotEmpty",true,array("messages"=>" This field is Required ")),
 							),
  		));
		$this->addElement('text', 'pl_phone', array (
			'class' => 'form-control',
			"placeholder" => "Enter Phone Number",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
 	
 	
 		$this->addElement('button', 'submitbtn', array(
			'ignore'   => true,
			'type'=>'submit',
			'label'    => 'I WANT MY HOME VALUE NOW!',
			'class'    => 'btn blue btn-primary bttnsubmit hvr-inner-shadow  btn-default btn btn-default site_button site_button1'
		));
		
		$this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
 	}

	
	
	
}