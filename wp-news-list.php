<?php

function wpnewsslider_list_page()
	{
			
	  global $wpdb;
	  global $wpnewsslider_table_name;
	  
?>
<div class="wrap">

<script type="text/javascript">
	function delete_message(idmessage, title)
	{
		if (confirm("Are you sure you want to delete the message " +  title + "?"))
		{
			document.forms["wpnewsslider_listform"].wpnewsslider_messagetodelete.value = idmessage;
			document.forms["wpnewsslider_listform"].wpnewsslider_action.value = "delete";
			document.forms["wpnewsslider_listform"].submit();
		}
	}
</script>
<?php 
	if ( $_POST["wpnewsslider_action"] == "delete" )
	{
		$wpdb->query("DELETE FROM " . $wpdb->prefix .  $wpnewsslider_table_name .
					 " WHERE id_message = " . $_POST["wpnewsslider_messagetodelete"] );	
	}
	
?>
<h2>WordPress News Slider</h2>
<form name="wpnewsslider_listform" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"
	  method="post" >
	<input type="hidden" name="wpnewsslider_messagetodelete" value="">
	<input type="hidden" name="wpnewsslider_action" value="">
				   
	<p class="submit">
		<input type="button" value="Add New Message" 
			   onclick="document.location='options-general.php?page=wpnewsslider&wpnewsslider_addnew=ok'">
		<br>
	</p>
</form>		
<br>
<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
	<th scope="col" class="manage-column column-name" style="">News Message</th>
	<th scope="col" class="manage-column column-name" style="">Date</th>
	<th scope="col" class="manage-column column-email" style="">&nbsp;</th>
	<th scope="col" class="manage-column column-email" style="">&nbsp;</th>
</tr>
</thead>

<tbody id="users" class="list:user user-list">
<?php
	
	$query = " SELECT *  FROM " .
			  $wpdb->prefix . $wpnewsslider_table_name .
			  " ORDER BY message_date, message_title ";
			  
	$myMessages = $wpdb->get_results($query);
	
	foreach ($myMessages as $message)
	{
 ?>
        <tr id='user-1' class="alternate">
			<td class="username column-username">
				<?php echo substr($message->message_title,0,100) . "..."; ?>
		    </td>
		    <td class="username column-username">
				<?php echo $message->message_date; ?>
		    </td>
		   
		   <td class="username column-username">
				<a href="options-general.php?page=wpnewsslider&wpnewsslider_id_message=<?php echo $message->id_message; ?>">
				Edit Message</a>
		   </td>
		   <td class="username column-username">
				<a href="javascript:delete_message(<?php echo $message->id_message ?>,'<?php echo $message->message_title ?>');">
				Delete Message</a>
		   </td>		
		</tr>
        
        <?
    }

?>
	
    </tbody>
</table>
<?php 
	}

?>