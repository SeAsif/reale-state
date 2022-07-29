<?php
class Application_Form_Custompage1 extends Twitter_Bootstrap_Form_Vertical
{
	
	public function init(){
 
  		$this->setMethod('post');
 		
		$this->setAttribs(array(
 			'class' => 'profile_form',
 			'novalidate'=>'novalidate',
			"role"=>"form",
			'id'=>'c_buyer',
			'name'=>'c_buyer',
			'enctype'=>'multipart/form-data'
		));
  	}
	
		public function step1(){
		$this->addElement('text', 'pl_name', array (
			'class' => 'form-control required',
			'required'=>true,
			"placeholder" => "Full Name",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
			"validators" =>  array(
								array("NotEmpty",true,array("messages"=>" This field is Required ")),
 							),
  		));
		$this->addElement('text', 'pl_email', array (
			'class' => 'form-control email required',
			"placeholder" => "Email Address",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
			"validators" =>  array(
								array("NotEmpty",true,array("messages"=>" This field is Required ")),
 							),
  		));
		$this->addElement('text', 'pl_address', array (
			'class' => 'form-control required',
			"placeholder" => "Enter Address",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
		$this->addElement('text', 'pl_phone', array (
			'class' => 'form-control required',
			"placeholder" => "Enter Phone Number",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
		$this->addElement('button', 'submitbtn', array(
				'ignore'   => true,
				'type'=>'button',				
				'class'    => 'btn form-btn color-background'
			));
			
			$this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
 
 	}
	
}