<?php
include_once('class.connection.php'); // Clase de conexión
include_once('class.smtp.php'); // Clase para habilitar el envío de emails usando el protocolo smtp
include_once('class.phpmailer.php'); // Clase de envío de emails

if (!$_POST){
?>

<?php
}else{
	
	// Nos conectamos a la base de datos usando la clase de conexión alternativa
	$db = new Conexion();
	
	if($db->conectar()){
		
		if($query = $db->insert_query($_POST)){
			
			// Indico destinatario
			$name = $_POST["name"];
			$address = $_POST["email"];
			
			// Preparamos los datos de contexto a ser transmitidos a la plantilla de correo
			$postdata = http_build_query($_POST);
			
			$opts = array(
				'http' => array(
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => $postdata
				)
			);
			
			$context = stream_context_create($opts);
			
			$respuesta = file_get_contents($db->base_url().'email_contents.php', false, $context);

			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 0;
			//Set the hostname of the mail server
			$mail->Host = 'smtp.gmail.com';
			//Set the SMTP port number - likely to be 25, 465 or 587
			$mail->Port = 587;
			//Set the encryption system to use - ssl (deprecated) or tls
			$mail->SMTPSecure = 'tls';
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			//Username to use for SMTP authentication
			$mail->Username = 'youremail@gmail.com';
			//Password to use for SMTP authentication
			$mail->Password = 'password';
			//Set who the message is to be sent from
			$mail->setFrom('solorzano202009@gmail.com', 'Formulario');
			//Set an alternative reply-to address
			$mail->addReplyTo('solorzano202009@gmail.com', 'Formulario');
			//Set who the message is to be sent to
			$mail->addAddress($address, $name);
			//Fix collation
			$mail->CharSet = 'UTF-8';
			//Set the subject line
			$mail->Subject = 'Consulta enviada';
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			//~ $mail->IsHTML(false);
			$mail->Body = $respuesta;
			//~ $mail->Body = $respuesta_curl;  // Activar si usamos la alternativa con CURL
			//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';
			//Attach an image file
			$mail->addAttachment('assets/Profile.pdf');
			//send the message, check for errors
			if(!$mail->send()) {
				echo 'Mailer Error: '.$mail->ErrorInfo;
			} else {
				echo 'Message sent!';
			}
				
			include 'confirma.html'; //se debe crear un html que confirma el envío
			
		}else{
			
			echo "Ha ocurrido un error al registrar su consulta.";
			
		}
		
		$db->desconectar();
		
		return $query;
		
	}
	 
}
?>
