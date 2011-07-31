<?php

//to put inline
$template_call['frm'] = true;
function frm($name) {
  if(form_post("submit")) {
	    $message = "";
        foreach($_REQUEST as $key=>$value) {
		  
		  if(str_beg($key,"cb_")) {
			  $message .= "$value\r\n";
		  }

          if(str_beg($key,"f_")) {
			 $caption = $_REQUEST["c_".str_replace("f_","",$key)];
             $message .= "$caption:\r\n$value\r\n\r\n";
		  }
		}

		$from = "no-reply-site-form@".$_SERVER['HTTP_HOST'];
		$to = setting("admin_email");
		$subject = form_post("subject");
		$local = ($_SERVER['REMOTE_ADDR'] == "127.0.0.1");

		if(form_file_uploaded("uploadedfile")) {
		   $tmp = $_FILES['uploadedfile']['tmp_name'];
		   $fname = $_FILES['uploadedfile']['name'];
/*           if($local) {
             die("<pre>$message tmp[$tmp] fname[$fname]</pre>");
		   } else {*/
             mail_attach($from,$to,$subject,$message,$tmp,$fname);
//		   }
		} else {
/*           if($local) {
             die("<pre>$message</pre>");
		   } else*/
		     mail_from($to,$subject,$message,$from);
		}


		return form_post("success");

  }

  return template("form_email","content",template("form_email_".$name));
}

function page_form($name) {
  return frm($name);
}

?>