<?php
	defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__) . ''));
	
 	define("SITE_NAME", "Crowdmuster");
 	define("NAME_OF_SITE", "Crowdmuster");
	
	
	define("ADMIN_AUTH_NAMESPACE", "ADMIN_AUTH");
	define("DEFAULT_AUTH_NAMESPACE", "DEFAULT_AUTH");
	
 	define("SITE_BASE_URL", dirname($_SERVER['PHP_SELF']));
	define("SITE_HOST_URL", "http://" . $_SERVER['HTTP_HOST'].SITE_BASE_URL);
	/*define("SITE_HTTP_URL", "http://" . $_SERVER['HTTP_HOST']);

	define("APPLICATION_URL", "http://" . $_SERVER['HTTP_HOST']);*/
	
	define("SITE_HTTP_URL", "http://" . $_SERVER['HTTP_HOST']);
 	define("APPLICATION_URL", "http://" . $_SERVER['HTTP_HOST']);
 
	define("ADMIN_APPLICATION_URL", SITE_HTTP_URL . "/admin");
	
		define('PRICE_SYMBOL','$');
		

	define("FRONT_CSS_PATH",SITE_HTTP_URL.'/assets/front/css');
	define("FRONT_JS_PATH",SITE_HTTP_URL.'/public/front_js');
	define("FRONT_IMAGES_PATH",SITE_HTTP_URL.'/assets/front/img');
	
	
	
	define('ADMIN_CSS_PATH', SITE_HTTP_URL.'/assets/admin/css');
	define('ADMIN_JS_PATH', SITE_HTTP_URL.'/assets/admin/js');
	
	define('ADMIN_IMAGES_PATH', SITE_HTTP_URL.'/assets/admin/img');
 	define('ADMIN_ASSETS_PATH', SITE_HTTP_URL.'/public/plugins');
	
	
	
	/* public path */
		define('PUBLIC_PATH', SITE_HTTP_URL.'/public');
	
	
	
	
	define('ADMIN_PROFILE', '/resources/admin profile images');
	
	define('PROPERTY_IMAGES', '/resources/property images');
	
	
	define('GALLERY_IMAGES', '/resources/gallery images');
	
	define('TEAM_IMAGES', '/resources/team members');

	
	define("IMAGE_VALID_EXTENTIONS","jpg,JPG,png,PNG,jpeg,JPEG");
	define("IMAGE_VALID_SIZE","5MB");

	
	
	
	define("IMG_URL",ROOT_PATH."/assets/img/");
	define("HTTP_IMG_URL",APPLICATION_URL."/assets/img/");
	
	
	
	/* New Theme Constatns */
	define('HTTP_IMG_PATH', SITE_HTTP_URL.'/public/img');
 
 	define('HTTP_PROFILE_IMAGES_PATH', SITE_HTTP_URL.'/public/resources/profile_images');
 	define('PROFILE_IMAGES_PATH', ROOT_PATH.'/public/resources/profile_images');
	
	
	
	 
	 
	define('HTTP_MEDIA_IMAGES_PATH', SITE_HTTP_URL.'/public/resources/media_images');
	define('MEDIA_IMAGES_PATH', ROOT_PATH.'/public/resources/media_images');
	
	define('HTTP_SLIDER_IMAGES_PATH', SITE_HTTP_URL.'/public/resources/slider_images');
	define('SLIDER_IMAGES_PATH', ROOT_PATH.'/public/resources/slider_images');
	
	define('HTTP_SITE_IMAGES', SITE_HTTP_URL.'/public/site_images');
	define('SITE_IMAGES', ROOT_PATH.'/public/site_images');
	
	define('HTTP_TEMPLATE_IMAGES', SITE_HTTP_URL.'/public/resources/page_templates');
	define('TEMPLATE_IMAGES', ROOT_PATH.'/public/resources/page_templates');

 
 	define('HTTP_SITEIMG_PATH',SITE_HTTP_URL.'/public/site_images');

		define('ROOT_BLOG_IMAGES_PATH', ROOT_PATH.'/public/resources/blog_images');
	define('HTTP_BLOGS_IMAGES_PATH', SITE_HTTP_URL.'/public/resources/blog_images');
	
	
global $master_list;
	
	$master_list = array(
    0 => array(
        'name' => 'bathrooms',
        'heading' => 'Bathrooms',
		'prefix'=>'bt',
    ),
    1 => array(
        'name' => 'bedrooms',
        'heading' => 'Bedrooms',
		'prefix'=>'bd',
    ),
	2 => array(
        'name' => 'property_types',
        'heading' => 'Property Types',
		'prefix'=>'pt',
    ),
	3 => array(
        'name' => 'price_ranges',
        'heading' => 'Price Ranges',
		'prefix'=>'pr',
    ),
);
global $font_array;
$font_array=array(
''=>'Select Font',
'Open Sans'=>'Open Sans',
'allerlight'=>'Aller (Light)',
'aller_displayregular'=>'Aller (Regular)',
'oswald_regularregular'=>'Oswald Regular',
'robotobold'=>'roboto (bold)',
'roboto_condensedregular'=>'roboto (condensedregular)',
'roboto_condensedregular'=>'roboto (condensedregular)',
'brownregular'=>'Brown (Regular)',

);


global $font_size;
  $font_size=array(''=>'Select font size');
 for ($i = 30; $i <= 90; $i++)
{
	$font_size[$i]=$i;
}


global $font_size_sm;
  $font_size_sm=array(''=>'Select font size');
 for ($i = 15; $i <= 30; $i++)
{
	$font_size_sm[$i]=$i;
}

global $font_style;
  $font_style=array(
  ''=>'Select font style',
  'normal'=>'Normal',
  'bold'=>'bold',
  );



