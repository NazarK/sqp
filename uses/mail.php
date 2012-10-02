<?php

function mail_attach($from,$to,$subject,$text,$attach_path,$attach_name) {
	$random_hash = md5(@date('r', time()));
	//define the headers we want passed. Note that they are separated with \r\n
	$mime_boundary = "<<<--==+X[".md5(time())."]";

	$fileContent =  chunk_split(base64_encode(file_get_contents($attach_path)));

	$headers = "From: $from\r\n";  

	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: multipart/mixed;\r\n";
	$headers .= " boundary=\"".$mime_boundary."\"";

	$message = "This is a multi-part message in MIME format.\r\n";
	$message .= "\r\n";
	$message .= "--".$mime_boundary."\r\n";

	$message .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
	$message .= "Content-Transfer-Encoding: 7bit\r\n";
	$message .= "\r\n";
	$message .= "$text\r\n";
	$message .= "--".$mime_boundary."" . "\r\n";

	$message .= "Content-Type: application/octet-stream;\r\n";
	$message .= " name=\"$attach_name\"" . "\r\n";
	$message .= "Content-Transfer-Encoding: base64 \r\n";
	$message .= "Content-Disposition: attachment;\r\n";
	$message .= " filename=\"$attach_name\"\r\n";
	$message .= "\r\n";
	$message .= $fileContent;
	$message .= "\r\n";
	$message .= "--".$mime_boundary."\r\n";


	$parts = explode(";",$to);
	foreach($parts as $to) {
		if(!mail($to, $subject, $message, $headers)) {
		  die("error sending email");	
		};
	}
}


function page_mail_test() {
	echo "sending email";
    mail_attach("no-reply@tradecity.kz","nazar.kuliev@gmail.com;chilavek@mail.ru","mail attach test","test",
		"bios.php","bios.php");
	mail_from("nazar.kuliev@gmail.com;chilavek@mail.ru","subj","message","noreply@nkcomp.com");

}

function mail_from($to,$subject,$message,$from) {
  $headers = "From: $from" . "\r\n" .
	     "Reply-To: $from" . "\r\n" .
	     'X-Mailer: PHP/' . phpversion();

  $parts = explode(";",$to);
  foreach($parts as $to) {
    if(!mail($to,$subject,$message,$headers)) {
      echo "error while sending email";
    }
  }
}


?>