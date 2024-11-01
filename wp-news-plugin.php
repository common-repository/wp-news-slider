<?php
/*
Plugin Name: WP News Slider
Plugin URI:   http://www.grupomayanfriends.com/wp-news-slider/
Description: Show a widget with news that you add in your blog.
Author: Angeline Strauss
Version: 1.0
Author URI: http://www.grupomayanfriends.com/
*/


// *********** PARSER ***********

register_activation_hook(__FILE__,'wpnewsslider_install');

$wpnewsslider_table_name = "newsslider";

function wpnewsslider_install () {
   global $wpdb;
   $wpnewsslider_table_name = "newsslider";
   
   $table_name = $wpdb->prefix . $wpnewsslider_table_name;
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  id_message mediumint(9) NOT NULL AUTO_INCREMENT,
	  message_date DATE NOT NULL,	  
	  message_title TEXT NOT NULL,
	  message_link TEXT NOT NULL,
	  UNIQUE KEY id_message (id_message)
	);";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
	
    // **** Insert two messages **** 
    $siteurl = get_option("siteurl"); 
    $query = "INSERT INTO " . $wpdb->prefix . $wpnewsslider_table_name  .  
				   					 "( message_title, message_link, message_date )  " . 
		" VALUES ('Hi, this is the first message from WP News Slider ', '$siteurl', '" 
    	. date("Y-m-d") ."') "; 
	$wpdb->query($query);
	
	$query = "INSERT INTO " . $wpdb->prefix . $wpnewsslider_table_name  .  
				   					 "( message_title, message_link, message_date )  " . 
		" VALUES ('You can now show your news header in your blog ', '$siteurl', '" 
    	. date("Y-m-d") ."') ";
    	 
	$wpdb->query($query);
	
	 update_option("wpnewsslider_timeinterval", "3");
	 update_option("wpnewsslider_dateformat",   "m/d/Y");
	 update_option("wpnewsslider_linktext", "read more");
			   		 
   }
}

function wpnewsslider_getwidget()
{	
	global $wpdb;	
	global $wpnewsslider_table_name;
	$query = " SELECT *  FROM " .
			  $wpdb->prefix . $wpnewsslider_table_name .
			  " ORDER BY message_date, message_title ";
			  
	$myMessages = $wpdb->get_results($query);
	
		
  	$optDateFormat = get_option("wpnewsslider_dateformat");
  	if (!$optDateFormat)
		$optDateFormat = "m/d/Y";
		
  	$optLinkText = get_option("wpnewsslider_linktext");
  	if (!$optLinkText)
		$optLinkText = "read more";		
		
		
	$newsMessages = "";
	$i = 0;
	foreach ($myMessages as $message)
	{
	  $i++;			  
      $newsMessages .=  '<div id="newssection-' . $i .'" class="newssection upper">
						   <strong>' . date($optDateFormat,time($message->message_date)) . '</strong>  ' .
      						substr($message->message_title,0,170) .
						   '...<a href="' . $message->message_link . '">' . $optLinkText . '</a>
						</div>';
						
				
	};
	if ($i == 0)
	{
		$newsMessages .=  '<div id="newssection-' . $i .'" class="newssection upper">
						   <strong>' . date("Y-m-d") . '</strong>  No news headers added yet. ' .
						   '...<a href="#">' . $optLinkText . '</a>
						</div>';
	};		
	
	return     '<div class="newsslider">
						    <div class="newsslidercontent" id="newsslider">'
						    	. $newsMessages .
						    '</div>' .
			   '</div>' .
				'<font style="font-size:6px;">Created by ' . 
				  '<a href="http://www.grupomayanfriends.com" target="_TOP" title="grupo mayan">wp news slider</a></font>';
			   
}



function wpnewsslider_widget($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;?><?php echo $after_title;
  echo wpnewsslider_getwidget();
  echo $after_widget;
}



function wpnewsslider_setfiles()
{
	echo '<script type="text/javascript" src="' . get_option("siteurl") . '/wp-content/plugins/' 
			. basename(dirname(__FILE__))  . '/wpnews_slider.js">' .
		 '</script>';

	echo '<link rel="stylesheet" type="text/css" href="' . get_option("siteurl") . '/wp-content/plugins/' .
		        basename(dirname(__FILE__)) . '/wpnews_slider.css" />';
	
}


function wpnewsslider_widget_control()
{
  
  $optTimeInterval = get_option("wpnewsslider_timeinterval");
  if (!$optTimeInterval)
		$optTimeInterval = "5";
		
  $optDateFormat = get_option("wpnewsslider_dateformat");
  if (!$optDateFormat)
		$optDateFormat = "m/d/Y";
		
  $optLinkText = get_option("wpnewsslider_linktext");
  if (!$optLinkText)
		$optLinkText = "read more";		
		
  if ($_POST['wpnewsslider-Submit'])
  {
	    update_option("wpnewsslider_timeinterval", $_POST['wpnewsslider_timeinterval']);
	    update_option("wpnewsslider_dateformat",   $_POST['wpnewsslider_dateformat']);
	    update_option("wpnewsslider_linktext",   $_POST['wpnewsslider_linktext']);
  }
  
  ?>
   <p>
    <label for="wpnewsslider_timeinterval">Time interval between messages: </label>
    <input type="text" id="wpnewsslider_timeinterval" name="wpnewsslider_timeinterval" size="3" maxlength="3" 
    	   value="<?php echo $optTimeInterval;?>" /> seconds.<br>
    
    Date format:
    <input type="text" id="wpnewsslider_dateformat" name="wpnewsslider_dateformat" size="10" maxlength="10" 
    	   value="<?php echo $optDateFormat;?>" /><br>
    <br>
    Link Text:
    <input type="text" id="wpnewsslider_linktext" name="wpnewsslider_linktext" size="15" maxlength="15" 
    	   value="<?php echo $optLinkText;?>" /><br>
    	   	   
    <input type="hidden" id="wpnewsslider-Submit" name="wpnewsslider-Submit" value="1" />
  </p>
  <?php
};

function wpnewsslider_init()
{
  register_sidebar_widget(__('WordPress News Slider'), 'wpnewsslider_widget');
  register_widget_control('WordPress News Slider', 'wpnewsslider_widget_control', 300, 200 );
}

function wpnewsslider_startslider()
{	
	 $optTimeInterval = get_option("wpnewsslider_timeinterval");
  	if (!$optTimeInterval)
		$optTimeInterval = "5";
		        
	echo '<script type="text/javascript">' .
		  "autoScroll('newsslider','newssection','" . $optTimeInterval . " ',true);" .
		 '</script>';		        				
	
}


include ("wp-news-list.php");
include ("wp-news-addedit.php");
function wpnewsslider_add_pages() {
    
	// Add a new submenu under Options:
	 if ($_REQUEST["wpnewsslider_id_message"] || $_REQUEST["wpnewsslider_addnew"] )
   		add_options_page('WP News Slider', 'WP News Slider', 8, 'wpnewsslider', 'wpnewsslider_edit_page');
     else
		add_options_page('WP News Slider', 'WP News Slider', 8, 'wpnewsslider', 'wpnewsslider_list_page');
}

add_action('admin_menu',     "wpnewsslider_add_pages");
add_action("wp_head",        "wpnewsslider_setfiles");
add_action("wp_footer",      "wpnewsslider_startslider");
add_action("plugins_loaded", "wpnewsslider_init");

?>