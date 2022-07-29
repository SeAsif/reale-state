<?php

class Application_Form_Landingpages extends Twitter_Bootstrap_Form_Vertical
{

    public function init()
    {

        $this->setMethod('post');

        $this->setAttribs(array(
            'class' => 'profile_form',
            'novalidate' => 'novalidate',
            "role" => "form",
            "id" => "landing_pages",
            'enctype' => 'multipart/form-data'
        ));
    }

    public function createpage($lp_id = false)
    {
        if (isset($lp_id) && $lp_id != '') {
            $this->addElement('hidden', 'lp_id', array('value' => $lp_id));

        }
        $this->addElement('text', 'lp_name', array(
            'class' => 'form-control',
            'label' => 'Enter Location',
            "placeholder" => "ENTER LOCATION",
            "ng-model" => "location",
            "ng-focus" => "onFocus()", 
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('textarea', 'lp_pixel_code', array(
            'class' => 'form-control',
            'label' => 'Enter Pixel Code',
            "placeholder" => "Enter Pixel Code",
        ));
        $this->addElement('text', 'lp_url', array(
            'class' => 'form-control required checkprofileurl',
            "placeholder" => "PAGE NAME",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
            "validators" => array(
                array("NotEmpty", true, array("messages" => " This field is Required ")),
            ),
        ));
        $this->addElement('button', 'submitbtn', array(
            'ignore' => true,
            'type' => 'submit',
            'label' => 'Publish the page',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
    }

    public function createpage_custom($lp_id = false)
    {
        if (isset($lp_id) && $lp_id != '') {
            $this->addElement('hidden', 'lp_id', array('value' => $lp_id));

        }
        $this->addElement('file', 'pt_background', array(
            "placeholder" => " Upload  ",
            "class" => "form-control profilefiletype",
            "ng-model" => "background",
            "ngf-select" => "background",
            //"label"=>"Upload background for page(JPG,PNG,JPEG,GIF) minimum size(1024X768)"
            "label" => "Upload background for page(JPG,PNG,JPEG,GIF)"
        ));
        $this->addElement('textarea', 'lp_pixel_code', array(
            'class' => 'form-control',
            'label' => 'Enter Pixel Code',
            "placeholder" => "Enter Pixel Code",
        ));
        $this->pt_background->setDestination(TEMPLATE_IMAGES)
            ->addValidator('Extension', false, "jpg,JPG,png,PNG,jpeg,JPEG,gif,GIF")
            /* ->addValidator('ImageSize', false, array('minwidth' =>1024,
                                                        'minheight' =>768,
                                                       ))*/
            ->addValidator('Size', false, "15MB");

        $this->addElement('text', 'lp_c_sent1', array(
            'class' => 'form-control',
            "label" => "DON'T LEAVE MONEY ON THE TABLE! (Replace Text)",
            "placeholder" => "DON'T LEAVE MONEY ON THE TABLE! (Replace Text)",
            "ng-model" => "setence_1",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));

        $this->addElement('text', 'lp_c_sent2', array(
            'class' => 'form-control',
            "label" => "Big brand online estimators can be off by 10-20%. Get your TRUE home value here. (Replace Text)",
            "placeholder" => "Big brand online estimators can be off by 10-20%. Get your TRUE home value here. (Replace Text)",
            "ng-model" => "setence_2",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));

        $this->addElement('text', 'lp_url', array(
            'class' => 'form-control required checkprofileurl',
            "placeholder" => "PAGE NAME",
            "label" => "PAGE NAME",
            "validators" => array(
                array("NotEmpty", true, array("messages" => " This field is Required ")),
            ),
        ));


        $this->addElement('button', 'submitbtn', array(
            'ignore' => true,
            'type' => 'submit',
            'label' => 'Publish the page',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
    }


    public function createpage_custom_buyer($lp_id = false)
    {
        if (isset($lp_id) && $lp_id != '') {
            $this->addElement('hidden', 'lp_id', array('value' => $lp_id));

        }
        $this->addElement('file', 'lp_bg_image', array(
            "placeholder" => " Upload  ",
            "class" => "form-control profilefiletype required",
            "ng-model" => "home_photo",
            "ngf-select" => "home_photo",
            //"label"=>"Upload Main Home Photo(JPG,PNG,JPEG,GIF) minimum size(1600X1200)"
            "label" => "Upload Main Home Photo(JPG,PNG,JPEG,GIF)"
        ));

        $this->lp_bg_image->setDestination(TEMPLATE_IMAGES)
            ->addValidator('Extension', false, "jpg,JPG,png,PNG,jpeg,JPEG,gif,GIF")
            /* ->addValidator('ImageSize', false, array('minwidth' =>1600,
                                                        'minheight' =>1200,
                                                       ))*/
            ->addValidator('Size', false, "15MB");

        $this->addElement('text', 'lp_c_sent1', array(
            'class' => 'form-control required',
            "ng-model" => "just_listed_in",
            "ng-focus" => "onFocus()", 
            "label" => "Just Listed In The Perfect San Jose Neighborhood (Replace Text)",
            "placeholder" => "Just Listed In The Perfect San Jose Neighborhood (Replace Text)",


        ));

        $this->addElement('text', 'lp_beds', array(
            'class' => 'form-control digits required',
            "label" => "Beds",
            "label" => "Beds",
            "ng-model" => "beds",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('text', 'lp_baths', array(
            'class' => 'form-control number required',
            "label" => "Baths",
            "label" => "Baths",
            "ng-model" => "baths",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('text', 'lp_square_feet', array(
            'class' => 'form-control number required',
            "maxlength" => 6,
            "label" => "Square feet",
            "label" => "Square feet",
            "ng-model" => "area",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('text', 'lp_city', array(
            'class' => 'form-control required',
            "label" => "City",
            "label" => "City",
            "ng-model" => "city",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('text', 'lp_redirect_link', array(
            'class' => 'form-control url required',
            "label" => "Destination Link (link to virtual tour, property listing page, MLS listing page)",
            //"filters"    => array("StringTrim","StripTags","HtmlEntities"),
            "validators" => array(
                array("NotEmpty", true, array("messages" => " This field is Required ")),
            ),

        ));
        $this->addElement('textarea', 'lp_pixel_code', array(
            'class' => 'form-control',
            'label' => 'Enter Pixel Code',
            "placeholder" => "Enter Pixel Code",
        ));
        $this->addElement('text', 'lp_url', array(
            'class' => 'form-control required checkprofileurl',
            "placeholder" => "PAGE NAME",
            "label" => "PAGE NAME",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
            "validators" => array(
                array("NotEmpty", true, array("messages" => " This field is Required ")),
            ),
        ));


        $this->addElement('button', 'submitbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Publish the page',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
    }

    public function style_form()
    {

        global $font_array;
        $this->addElement('select', 'lps_font1', array(
            'class' => 'form-control ',
            'label' => 'Font',
            'filters' => array('StringTrim', 'StripTags'),
            "ng-model" => "sentence_1_font",
            'multiOptions' => $font_array,
        ));
        global $font_size;
        $this->addElement('select', 'lps_font_size1', array(
            'class' => 'form-control ',
            'label' => 'Font Size',
            "ng-model" => "sentence_1_font_size",
            'filters' => array('StringTrim', 'StripTags'),
            'multiOptions' => $font_size,
        ));
        global $font_style;
        $this->addElement('select', 'lps_font_style1', array(
            'class' => 'form-control ',
            'label' => 'Font Style',
            "ng-model" => "sentence_1_font_style",
            'filters' => array('StringTrim', 'StripTags'),
            'multiOptions' => $font_style,
        ));

        $this->addElement('text', 'lps_font_color1', array(
            'class' => 'form-control  c_picker',
            'label' => 'Font Color',
            "ng-model" => "sentence_1_font_color",
            'filters' => array('StringTrim', 'StripTags'),
        ));

        $this->addElement('select', 'lps_font2', array(
            'class' => 'form-control ',
            'label' => 'Font',
            "ng-model" => "sentence_2_font",
            'filters' => array('StringTrim', 'StripTags'),
            'multiOptions' => $font_array,
        ));
        global $font_size;
        $this->addElement('select', 'lps_font_size2', array(
            'class' => 'form-control ',
            'label' => 'Font Size',
            "ng-model" => "sentence_2_font_size",
            'filters' => array('StringTrim', 'StripTags'),
            'multiOptions' => $font_size,
        ));
        global $font_style;
        $this->addElement('select', 'lps_font_style2', array(
            'class' => 'form-control ',
            'label' => 'Font Style',
            "ng-model" => "sentence_2_font_style",
            'filters' => array('StringTrim', 'StripTags'),
            'multiOptions' => $font_style,
        ));

        $this->addElement('text', 'lps_font_color2', array(
            'class' => 'form-control  c_picker',
            "ng-model" => "sentence_2_font_color",
            'label' => 'Font Color',
            'filters' => array('StringTrim', 'StripTags'),
        ));
        $this->addElement('text', 'lps_background3', array(
            'class' => 'form-control  c_picker',
            'label' => 'Background',
            "ng-model" => "button_background_color",
            'readonly' => 'readonly',
            'filters' => array('StringTrim', 'StripTags'),
        ));

        $this->addElement('select', 'lps_font3', array(
            'class' => 'form-control ',
            'label' => 'Font',
            "ng-model" => "button_font",
            'filters' => array('StringTrim', 'StripTags'),
            'multiOptions' => $font_array,
        ));
        global $font_size_sm;
        $this->addElement('select', 'lps_font_size3', array(
            'class' => 'form-control',
            'label' => 'Font Size',
            "ng-model" => "button_font_size",
            'filters' => array('StringTrim', 'StripTags'),
            'multiOptions' => $font_size_sm,
        ));

        $this->addElement('text', 'lps_font_color3', array(
            'class' => 'form-control  c_picker',
            'label' => 'Font Color',
            "ng-model" => "button_font_color",
            'filters' => array('StringTrim', 'StripTags'),
        ));


    }

    public function custompage($lp_id = false)
    {
        if (isset($lp_id) && $lp_id != '') {
            $this->addElement('hidden', 'lp_id', array('value' => $lp_id));

        }
        $this->addElement('hidden', 'lp_bg_image', array(
            "placeholder" => " Upload  image",
            "class" => "form-control profilefiletype",

        ));

        /*$this->lp_bg_image->setDestination(TEMPLATE_IMAGES)
           ->addValidator('Extension', false,"jpg,JPG,png,PNG,jpeg,JPEG,gif,GIF")
            ->addValidator('ImageSize', false, array('minwidth' =>1600,
                                                       'minheight' =>1200,
                                                      ))
           ->addValidator('Size', false, "15MB");*/

        $this->addElement('text', 'lp_headline', array(
            'class' => 'form-control required',
            'placeholder' => 'Insert your headline text here.',
            "ng-model" => "headline",
            "ng-focus" => "onFocusHeadline()", 
            "label" => "1. Headline",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_button_text', array(
            'class' => 'form-control required',
            "label" => "2. Button text",
            "ng-model" => "button_text",
            "ng-focus" => "onFocusButtonText()", 
            'placeholder' => 'Insert your button text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_popup_text', array(
            'class' => 'form-control required',
            'placeholder' => 'Insert your popup headline text here.',
            "label" => "3. Popup headline",
            "ng-model" => "popup_headline",
            "ng-focus" => "onFocusPopup()",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_popup_btn_text', array(
            'class' => 'form-control required',
            "label" => "4. Popup button text",
            "ng-model" => "popup_button_text",
            "ng-focus" => "onFocusPopupButtonText()",
            'placeholder' => 'Insert your popup button text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('hidden', 'lps_background', array(
            'class' => 'form-control ',
            "ng-model" => "button_color",
            'filters' => array('StringTrim', 'StripTags'),
        ));

        $this->addElement('text', 'lp_redirect_link', array(
            'class' => 'form-control url required',
            "label" => "Destination Link (link to virtual tour, property listing page, MLS listing page)",
            //"filters"    => array("StringTrim","StripTags","HtmlEntities"),
            "validators" => array(
                array("NotEmpty", true, array("messages" => " This field is Required ")),
            ),

        ));

        $this->addElement('textarea', 'lp_pixel_code', array(
            'class' => 'form-control checkprofileurl',
            'label' => 'Enter Pixel Code',
            "placeholder" => "Enter Pixel Code",
        ));

        $this->addElement('text', 'lp_name', array(
            'class' => 'form-control required checkprofileurl',
            "label" => 'Page Name',
            "placeholder" => "Page Name",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('button', 'submitbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Publish the page',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
        $this->addElement('button', 'previewbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Preview',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->previewbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
    }

    public function custompage2($lp_id = false)
    {
        if (isset($lp_id) && $lp_id != '') {
            $this->addElement('hidden', 'lp_id', array('value' => $lp_id));

        }
        $this->addElement('hidden', 'lp_bg_image', array(
            "placeholder" => " Upload  image",
            "ng-model" => "background",
            "class" => "form-control profilefiletype",

        ));
        $this->addElement('textarea', 'lp_pixel_code', array(
            'class' => 'form-control checkprofileurl',
            'label' => 'Enter Pixel Code',
            "placeholder" => "Enter Pixel Code",
        ));
        $this->addElement('text', 'lp_headline', array(
            'class' => 'form-control required',
            'placeholder' => 'Insert your headline text here.',
            "label" => "1. Headline",
            "ng-model" => "headline",
            "ng-focus" => "onFocusHeadline()", 
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_subheadline', array(
            'class' => 'form-control required',
            "label" => "2. Subheadline",
            "ng-model" => "sub_headline",
            "ng-focus" => "onFocusSubHeadline()", 
            'placeholder' => 'Insert your subheadline text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_video_link', array(
            'class' => 'form-control required',
            "label" => "3. Video",
            "ng-model" => "vide_link",
            "ng-focus" => "onFocusVideoLink()", 
            'placeholder' => 'https://player.vimeo.com/video/68082407',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_button_text', array(
            'class' => 'form-control required',
            "label" => "4. Button text",
            "ng-model" => "button_text",
            "ng-focus" => "onFocusButtonText()", 
            'placeholder' => 'Insert your button text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_popup_text', array(
            'class' => 'form-control required',
            "label" => "5. Popup headline",
            "ng-model" => "popup_headline",
            "ng-focus" => "onFocusPopup()",
            'placeholder' => 'Insert your popup headline text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_popup_btn_text', array(
            'class' => 'form-control required',
            'placeholder' => 'Insert your popup button text here.',
            "label" => "6. Popup button text",
            "ng-model" => "popup_button_text",
            "ng-focus" => "onFocusPopupButtonText()",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('hidden', 'lps_background', array(
            'class' => 'form-control ',
            "ng-model" => "button_color",
            'filters' => array('StringTrim', 'StripTags'),
        ));

        $this->addElement('text', 'lp_redirect_link', array(
            'class' => 'form-control url required',
            "label" => "Destination Link (link to virtual tour, property listing page, MLS listing page)",
            //"filters"    => array("StringTrim","StripTags","HtmlEntities"),
            "validators" => array(
                array("NotEmpty", true, array("messages" => " This field is Required ")),
            ),

        ));
        $this->addElement('text', 'lp_name', array(
            'class' => 'form-control required checkprofileurl',
            "label" => 'Page Name',
            "placeholder" => "Page Name",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('button', 'submitbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Publish the page',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
        $this->addElement('button', 'previewbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Preview',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->previewbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
    }

    public function custompage3($lp_id = false)
    {
        if (isset($lp_id) && $lp_id != '') {
            $this->addElement('hidden', 'lp_id', array('value' => $lp_id));

        }
        $this->addElement('hidden', 'lp_bg_image', array(
            "placeholder" => " Upload  image",
            "ng-model" => "background",
            "class" => "form-control profilefiletype",

        ));
        $this->addElement('textarea', 'lp_pixel_code', array(
            'class' => 'form-control checkprofileurl',
            'label' => 'Enter Pixel Code',
            "placeholder" => "Enter Pixel Code",
        ));
        $this->addElement('text', 'lp_headline', array(
            'class' => 'form-control required',
            "label" => "1. Headline",
            "ng-model" => "headline",
            "ng-focus" => "onFocusHeadline()", 
            'placeholder' => 'Insert your headline text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_subheadline', array(
            'class' => 'form-control required',
            "label" => "2. Subheadline",
            "ng-model" => "sub_headline",
            "ng-focus" => "onFocusSubHeadline()", 
            'placeholder' => 'Insert your subheadline text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_bullet_1', array(
            'class' => 'form-control required',
            "label" => "3. Bullet Point 1",
            "ng-model" => "bullet_point_1",
            "ng-focus" => "onFocusBulletPoint1()", 
            'placeholder' => 'Bullet point text 1 goes here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_bullet_2', array(
            'class' => 'form-control required',
            "label" => "4. Bullet Point 2",
            "ng-model" => "bullet_point_2",
            "ng-focus" => "onFocusBulletPoint2()", 
            'placeholder' => 'Bullet point text 2 goes here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_bullet_3', array(
            'class' => 'form-control required',
            "label" => "5. Bullet Point 3",
            "ng-model" => "bullet_point_3",
            "ng-focus" => "onFocusBulletPoint3()", 
            'placeholder' => 'Bullet point text 3 goes here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_button_text', array(
            'class' => 'form-control required',
            "label" => "6. Button text",
            "ng-model" => "button_text",
            "ng-focus" => "onFocusButtonText()", 
            'placeholder' => 'Insert your button text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_popup_text', array(
            'class' => 'form-control required',
            "label" => "7. Popup headline",
            "ng-model" => "popup_headline",
            "ng-focus" => "onFocusPopup()",
            'placeholder' => 'Insert your popup headline text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_popup_btn_text', array(
            'class' => 'form-control required',
            "label" => "8. Popup button text",
            "ng-model" => "popup_button_text",
            "ng-focus" => "onFocusPopupButtonText()",
            'placeholder' => 'Insert your popup button text here.',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('hidden', 'lps_background', array(
            'class' => 'form-control ',
            "ng-model" => "button_color",
            'filters' => array('StringTrim', 'StripTags'),
        ));

        $this->addElement('text', 'lp_redirect_link', array(
            'class' => 'form-control url required',
            "label" => "Destination Link (link to virtual tour, property listing page, MLS listing page)",
            //"filters"    => array("StringTrim","StripTags","HtmlEntities"),
            "validators" => array(
                array("NotEmpty", true, array("messages" => " This field is Required ")),
            ),

        ));
        $this->addElement('text', 'lp_name', array(
            'class' => 'form-control required checkprofileurl',
            "label" => 'Page Name',
            "placeholder" => "Page Name",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('button', 'submitbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Publish the page',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
        $this->addElement('button', 'previewbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Preview',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->previewbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
    }

    public function custompage5($lp_id = false)
    {
        if (isset($lp_id) && $lp_id != '') {
            $this->addElement('hidden', 'lp_id', array('value' => $lp_id));

        }
        $this->addElement('hidden', 'lp_bg_image', array(
            "placeholder" => " Upload  image",
            "ng-model" => "background",
            "class" => "form-control profilefiletype",

        ));

        /*$this->lp_bg_image->setDestination(TEMPLATE_IMAGES)
           ->addValidator('Extension', false,"jpg,JPG,png,PNG,jpeg,JPEG,gif,GIF")
            ->addValidator('ImageSize', false, array('minwidth' =>1600,
                                                       'minheight' =>1200,
                                                      ))
           ->addValidator('Size', false, "15MB");*/

        $this->addElement('text', 'lp_headline', array(
            'class' => 'form-control required',
            'placeholder' => 'Insert your headline text here',
            "label" => "1. Headline",
            "ng-model" => "headline",
            "ng-focus" => "onFocusHeadline()", 
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('textarea', 'lp_pixel_code', array(
            'class' => 'form-control checkprofileurl',
            'label' => 'Enter Pixel Code',
            "placeholder" => "Enter Pixel Code",
        ));
        $this->addElement('text', 'lp_button_text', array(
            'class' => 'form-control required',
            "label" => "2. Button text",
            "ng-model" => "button_text",
            "ng-focus" => "onFocusButtonText()", 
            'placeholder' => 'Insert your button text here',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $this->addElement('text', 'lp_popup_text', array(
            'class' => 'form-control required',
            'placeholder' => 'Insert your popup headline text here',
            "label" => "3. Popup headline",
            "ng-model" => "popup_headline",
            "ng-focus" => "onFocusPopup()",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));

        $this->addElement('text', 'lp_popup_btn_text', array(
            'class' => 'form-control required',
            "label" => "4. Popup button text",
            "ng-model" => "popup_button_text",
            "ng-focus" => "onFocusPopupButtonText()",
            'placeholder' => 'Insert your popup button text here',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));
        $option = array('1' => 'Yes', '0' => 'No');

        $this->addElement('radio', 'lp_phone_request', array(
            'class' => 'required  icons',
            'required' => true,
            "ng-model" => "request_number",
            "multiOptions" => $option,
            'label' => '5. Request phone number? &nbsp; <a href="#" id="hover_div"  class="" data-html="true" data-toggle="popover" data-trigger="hover" data-placement="top"  data-content="Please choose `Yes` option if you want phone numbers from users else left `No`" style="font-weight:500"><span class="fa fa-question-circle" style="vertical-align:top;z-index:99999;font-size:20px;margin-top:2px;font-weight:500 !important;"></span></a>',
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),
        ));

        $this->addElement('hidden', 'lps_background', array(
            'class' => 'form-control ',
            "ng-model" => "button_color",
            'filters' => array('StringTrim', 'StripTags'),
        ));

        $this->addElement('text', 'lp_redirect_link', array(
            'class' => 'form-control url required',
            "label" => "Destination Link (link to virtual tour, property listing page, MLS listing page)",
            //"filters"    => array("StringTrim","StripTags","HtmlEntities"),
            "validators" => array(
                array("NotEmpty", true, array("messages" => " This field is Required ")),
            ),

        ));
        $this->addElement('text', 'lp_name', array(
            'class' => 'form-control required checkprofileurl',
            "label" => 'Page Name',
            "placeholder" => "Page Name",
            "filters" => array("StringTrim", "StripTags", "HtmlEntities"),

        ));
        $this->addElement('button', 'submitbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Publish the page',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->submitbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
        $this->addElement('button', 'previewbtn', array(
            'ignore' => true,
            'type' => 'button',
            'label' => 'Preview',
            'class' => 'btn blue btn-primary  btn btn-default pull-right '
        ));

        $this->previewbtn->removeDecorator('label')->removeDecorator('HtmlTag')->removeDecorator('Wrapper');
    }
}