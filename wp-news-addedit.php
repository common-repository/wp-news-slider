<?php

function wpnewsslider_edit_page() {
	global $wpdb;
	global $wpnewsslider_table_name;
  
	// *** Post-It Info
	$id_message      	 = $_REQUEST["wpnewsslider_id_message"];
	$message_title		 = "";
	$message_link 	 	 = "";
	$message_date    	 = date("Y-m-d");
			
	// *******  SAVE POST-IT ********
	if ( !($_POST["wpnewsslider_submit"] == "ok") )
	{
		if ($id_message)
		{   $query = "SELECT * FROM " . $wpdb->prefix . $wpnewsslider_table_name .  
							 			     " WHERE id_message = $id_message ";
			
		    $messageInfo = $wpdb->get_results($query);
			
			$message_title		 = $messageInfo[0]->message_title;
			$message_link 	 	 = $messageInfo[0]->message_link;
			$message_date    	 = $messageInfo[0]->message_date;
			
		}
	}
	else
	{
		   // *** Postits Data
		   $message_title        = str_replace("\'"," ",$_POST["wpnewsslider_message_title"]);
		   $message_link 	    = str_replace("\'"," ",$_POST["wpnewsslider_message_link"]);
		   $message_date    	    = str_replace("\'"," ",$_POST["wpnewsslider_message_date"]);
		   
		   
		   if ($id_message)
		   {
			    	 $query = " UPDATE " . $wpdb->prefix . $wpnewsslider_table_name .  
				   				  " SET message_title   		= '$message_title', " . 
				   				      " message_link 		= '$message_link', " . 
				   				      " message_date  		= '$message_date'  "  . 
			    	 		 " WHERE id_message = $id_message ";
			    	 
			    	$wpdb->query($query);
		   }
		   else
		   {
				    $query = "INSERT INTO " . $wpdb->prefix . $wpnewsslider_table_name  .  
				   					 "( message_title, message_link, message_date )  " . 
				   				"VALUES ('$message_title', '$message_link', '$message_date') "; 
			    
			     
			   		 $wpdb->query($query);
			    	 $lastID = $wpdb->get_results("SELECT MAX(id_message) as lastid_message " .
			    		  						   " FROM " . $wpdb->prefix . $wpnewsslider_table_name .
			    							 	  " WHERE message_title = '$message_title'");
			    	 
			    	 $id_message = $lastID[0]->lastid_message;
		  }
			    
	}
	 

?>
<div class="wrap">
<script type="text/javascript">
	function validateInfo(forma)
	{
		if (forma.wpnewsslider_message_title.value == "")
		{
			alert("You must type a title");
			forma.wpnewsslider_message_title.focus();
			return false;
		}
		
		if (forma.wpnewsslider_message_date.value == "")
		{
			alert("The date cannot be empty");
			forma.wpnewsslider_message_date.focus();
			return false;
		}
		
		
	return true;
}
</script>

<form name="wpnewsslider_form" method="post" onsubmit="return validateInfo(this);" 
	  action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	  

<?php
    // Now display the options editing screen

    // header
	if ($id_message)
    	echo "<h2>" . __( 'Edit Message',    'mt_trans_domain' ) . "</h2>";
    else
       	echo "<h2>" . __( 'Add New Message', 'mt_trans_domain' ) . "</h2>";

    // options form
    
 ?>
    <?php if ( $_POST["wpnewsslider_submit"] == "ok" ) { ?>
    <div class="updated"><p><strong><?php _e('Message information saved.', 'mt_trans_domain' ); ?></strong></p></div><br>	
    <? }; ?>

 	
 	<span class="stuffbox" >
 		
		 <label for="wpnewsslider_message_date">Date</label>
		 <span class="inside">	
		 	<input type="text" size="10" maxlength="11" id="wpnewsslider_message_date" name="wpnewsslider_message_date"
		 		   value="<?php echo $message_date ?>"> 
	     </span>
	     
	     <br>
	     
	     
	     <br>
	     
	     News Header<br>
		 <span class="inside">	
		 	<textarea id="wpnewsslider_message_title" name="wpnewsslider_message_title" 
		 	           rows="6" cols="60"><?php echo $message_title ?></textarea>
	     </span>
	     
	     <br><br>
	     
	     URL Address (link, include the "http://" prefix)<br>
		 <span class="inside">	
		 	<input type="text" size="60"  id="wpnewsslider_message_link" name="wpnewsslider_message_link"
		 		   value="<?php echo $message_link ?>"> 
	     </span>
	     
	     <br>
	     
 	</span>
 

<p class="submit">
	<input type="hidden" name="wpnewsslider_submit" value="ok">
	<input type="hidden" name="wpnewsslider_id_message" value="<?php echo $id_message ?>">
	<input type="submit" name="Submit" value="<?php _e('Save Message Information', 'mt_trans_domain' ) ?>" />&nbsp;
	<input type="button" name="Return" value="<?php _e('Return to Message List', 'mt_trans_domain' ) ?>"
		   onclick="document.location='options-general.php?page=wpnewsslider' " />
</p>

</form>

</div> <!-- **** DIV WRAPPER *** -->

<?php } ?>