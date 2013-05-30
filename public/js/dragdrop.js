jQuery.noConflict();
  jQuery(document).ready(function(){
    jQuery(".items workouts").draggable({
	  helper:'clone',
	  revert: true,
      
    });
	
	jQuery(".intermediate div").draggable({
      revert: true,
      
    });
	
	
	
	user_id = jQuery("#userid").html();
	workoutID =  jQuery("#selfmadeid").html();
    level    = jQuery("#userlevel").html();
	jQuery.ajax({
          type: "POST",
          url: "../../../../player/products.php",
          data: "act=fill&user_id=" + user_id + "&workoutid=" + workoutID + "&level="+ level,
          cache: false,
          success: function(html){
		     var responseArray = html.split("**");           
            jQuery('#cart').html(responseArray[0]);
			jQuery('#totalcount').html(responseArray[2]);
			jQuery('#totaltime').html(responseArray[1]);
			jQuery('#totalmuscles').html(responseArray[3]);
           			
			var target = jQuery('#cart div').last();
           target.scrollintoview();
			

          }
        });
		
		
		
				
		jQuery("#saveworkout").click(function() {
         user_id = jQuery("#userid").html();
	     level    = jQuery("#userlevel").html();
		 duration     = jQuery("#totaltime").html();
		 workoutID =  jQuery("#selfmadeid").html();
		 userStatus = jQuery("#userstatus").html();
		 workoutName = jQuery("#workoutName").attr('value');
		 
		  result ="";
		  $('#cart div').each(function() {
		    result += $(this).attr("id") + ","; 
		   });
		
       jQuery.ajax({
          type: "POST",
          url: "../../../../player/update.php",
          data: "id=" + result +"&act=save&userid=" + user_id +"&userlevel="+ level + "&time=" + duration + "&workoutid=" + workoutID + "&userstatus=" + userStatus + "&workoutname=" +workoutName ,
          cache: false,
          success: function(html){
		  if(userStatus==1)
		  {
		  	window.location = 'http://fitness4.me/user/listworkouts#tab-3';
		  }
		  else
		  {
		    paramArray      = html.split("%%");
		  	window.location = 'http://fitness4.me/user/displaytrial/collection/'+paramArray[0]+'/name/trial/focus/'+ paramArray[2]+ '/equip/'+ paramArray[1];
		  }
		  
          }
        });
            
    });
		
		
	jQuery("#addworkout").click(function() {
	
	     prod_id = ui.draggable.attr("id");
		 user_id = jQuery("#userid").html();
	     level    = jQuery("#userlevel").html();
		 
		   result ="";
		  $('#cart div').each(function() {
		    result += $(this).attr("id") + ","; 
		   });
		   
		  jQuery.ajax({
          type: "POST",
          url: "../../../../player/update.php",
          data: "id=" + prod_id + "&act=insert&list=" + result+ "&level="+ level,
          cache: false,
          success: function(html){
		    var responseArray = html.split("**");
		    jQuery('#update').hide();
            jQuery('#cart').html(responseArray[0]);
			jQuery('#totalcount').html(responseArray[2]);
			jQuery('#totaltime').html(responseArray[1]);
			jQuery('#totalmuscles').html(responseArray[3]);
            var lastelemnt = jQuery('#cart div').last();
			lastelemnt.scrollintoview();
			//lastelemnt[0].scrollIntoView(true);
			
          }
        });
	
	});	
		
    jQuery("#cart").droppable({
      accept: '.items workouts,.intermediate div',
      drop: function( event, ui ) {
        jQuery('#update').show();
       
        prod_id = ui.draggable.attr("id");
		 user_id = jQuery("#userid").html();
	     level    = jQuery("#userlevel").html();
		 
		   result ="";
		  $('#cart div').each(function() {
		    result += $(this).attr("id") + ","; 
		   });
		   
        jQuery.ajax({
          type: "POST",
          url: "../../../../player/update.php",
          data: "id=" + prod_id + "&act=insert&list=" + result+ "&level="+ level,
          cache: false,
          success: function(html){
		    var responseArray = html.split("**");
		    jQuery('#update').hide();
            jQuery('#cart').html(responseArray[0]);
			jQuery('#totalcount').html(responseArray[2]);
			jQuery('#totaltime').html(responseArray[1]);
			jQuery('#totalmuscles').html(responseArray[3]);
            var lastelemnt = jQuery('#cart div').last();
			lastelemnt.scrollintoview();
			//lastelemnt[0].scrollIntoView(true);
			
          }
        });
            }
    });
    jQuery("#trash").droppable({
      accept: '#cart div',
      hoverClass: 'trashhover',
      drop: function( event, ui ) {
	   prod_id = ui.draggable.attr("id");
	   level    = jQuery("#userlevel").html();
	  result ="";
		  $('#cart div').each(function() {
		    if(!(isNaN($(this).attr("id"))) || ($(this).attr("id")=='rec15')|| ($(this).attr("id")=='rec30'))
			{
				result += $(this).attr("id") + ","; 
			}
		    
		   });
        ui.draggable.remove();
		
		  
        jQuery.ajax({
          type: "POST",
          url: "../../../../player/update.php",
          data: "list=" + result +"&id=" + prod_id  + "&act=delete&level="+level,
          cache: false,
          success: function(html){
            var responseArray = html.split("**");
            jQuery('#cart').html(responseArray[0]);
			jQuery('#totalcount').html(responseArray[2]);
			jQuery('#totaltime').html(responseArray[1]);
			jQuery('#totalmuscles').html(responseArray[3]);
            jQuery("#cart div").draggable();
			var target = jQuery('#cart div').last();
           target.scrollintoview();
            
          }
        });
            }
    });
  });