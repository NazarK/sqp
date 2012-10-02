$(function() {
  $("file").each( function() {
    var size = $(this).attr("size");
    $(this).replaceWith("<input type='hidden' name='MAX_FILE_SIZE' value='"+size+"' >"+
                        "<input mandatory="+$(this).attr("mandatory")+" name='uploadedfile' type='file'>");
   });

  $("#form_email").wrap("<form enctype='multipart/form-data' method=post></form>");

	$("#form_email input, #form_email textarea").each( function(index) {
	   $(this).addClass("inputField");
	   var type = $(this).attr("type");
       if(type == 'text' || type=='textarea') {
           $(this).attr("name","f_"+index);
		   var caption = $(this).parents("tr").find("td:eq(0)").html();		   
		   $("<input type=hidden name=c_"+index+" value='"+caption+"'></input>").insertBefore($(this));
 
	   }
    });

 $("#form_email input[mandatory=1], #form_email textarea[mandatory=1]").each( function() {
		   $(this).parents("tr").find("td:eq(0)").each( function() {
               $(this).html($(this).html()+"<span class=mandatoryStar>*</span>");
		   });
  });

  $("#form_email input[hint], #form_email textarea[hint]").blur( function() {
    if($(this).val()=='') { 
	  $(this).val($(this).attr("hint"));
	  $(this).addClass("hint");
	}
  })
  .focus( function() {
    if($(this).val()==$(this).attr("hint")) {
	  $(this).val('');
	  $(this).removeClass("hint");
	}
  })
  .each( function() {
    $(this).val($(this).attr("hint"));
	$(this).addClass("hint");
  })
  ;

  $("#form_email input[type='submit']").attr("name","submit").click( function() {
	  $(".mandatoryRemind").removeClass("mandatoryRemind");
      var ok = true;
	  $("#form_email input[mandatory=1], #form_email textarea[mandatory=1]").each( function() {

         var hintrestore = false;
	     if($(this).attr("hint")) {
           if($(this).attr("hint")==$(this).val()) {
              $(this).val('');
			  hintrestore = true;
		   }
		 }

		 if($(this).val()=="" || $(this).val()==null) {
		   if(ok==true) {
			 $(this).focus();
			 $(this).removeClass("hint");
			 hintrestore = false;
		   }
		   ok = false;
		   $(this).addClass("mandatoryRemind");
		 }

	     if(hintrestore) {
		    $(this).val($(this).attr("hint"));
		 }

	  });

	  if(!ok) return false;

  });

  $("#form_email checkbox").each(function(index) {
     $(this).replaceWith("<input class=inputCheckbox type=checkbox name=cb_"+index+" value='"+$(this).attr("value")+"'>"+$(this).attr("value"));
  });


});
