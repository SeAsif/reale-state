<?php
class Application_Form_Leadmagenttwo extends Twitter_Bootstrap_Form_Vertical
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
			
			$this->addElement('text', 'pl_name', array (
			'class' => 'form-control',
			"placeholder" => "Name",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
			
  		));
		
		$this->addElement('text', 'pl_email', array (
			'class' => 'form-control required email',
			"placeholder" => "Best Email",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
		
  		));
		
 		$this->addElement('button', 'submitbtn', array(
			'ignore'   => true,
			'type'=>'submit',
			'label'    => 'GET MY LIST NOW',
			'class'    => 'btn blue btn-primary bttnsubmit hvr-inner-shadow  btn-default btn btn-default  lead_mag'
		));
		
		$this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
 	}
	
		public function step2(){
			 		$model= new Application_Model_Static();

		 $country_arr=$model->PrepareSelectOptions_withdefault("property_types","pt_id","pt_name","1","pt_name","Property Types");
 		 $this->addElement('select', 'pl_property_type', array(
 			'class' => 'form-control name-group',
			"multioptions"=>$country_arr,
 			"placeholder" => "Country",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
		 $country_arr=$model->PrepareSelectOptions_withformated("bedrooms","bedrooms","Minimum Number of Bedrooms");
 		 $this->addElement('select', 'pl_bedrooms', array(
 			'class' => 'form-control name-group',
			"multioptions"=>$country_arr,
 			"placeholder" => "Country",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
		 $country_arr=$model->PrepareSelectOptions_withformated("bathrooms","bathrooms","Minimum Number of Bathrooms");
 		 $this->addElement('select', 'pl_bathrooms', array(
 			'class' => 'form-control name-group',
			"multioptions"=>$country_arr,
 			"placeholder" => "Country",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
		
		 $country_arr=$model->PrepareSelectOptions_withformated("price_ranges","price","Price Range");
 		 $this->addElement('select', 'pl_price', array(
 			'class' => 'form-control name-group required',
			"multioptions"=>$country_arr,
 			"placeholder" => "Country",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
		
 		 $this->addElement('text', 'pl_interest', array(
 			'class' => 'form-control name-group',
 			"placeholder" => "Areas of Interest",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
		 $this->addElement('text', 'pl_phone', array(
 			'class' => 'form-control',
 			"placeholder" => "Phone Number",
			"filters"    => array("StringTrim","StripTags","HtmlEntities"),
  		));
		
	
 	
 		$this->addElement('button', 'submitbtn', array(
			'ignore'   => true,
			'type'=>'submit',
			'label'    => 'Get My List Now!',
			'class'    => 'btn blue btn-primary bttnsubmit hvr-inner-shadow  btn-default btn btn-default lead_mag'
		));
		
		$this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
 	}

	
	
	
}