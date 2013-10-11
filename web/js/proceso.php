<?php
// Guardar los datos recibidos en variables:
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$asunto = $_POST['asunto'];
$mensaje = $_POST['mensaje'];
// Definir el correo de destino:
$dest = "sjimenez77@gmail.com"; 
 
// Estas son cabeceras que se usan para evitar que el correo llegue a SPAM:
$headers = "From: $nombre $email\r\n";
$headers .= "X-Mailer: PHP5\n";
$headers .= 'MIME-Version: 1.0' . "\n";
$headers .= 'Content-type: text/html; charset=utf8' . "\r\n";
 
// Aqui definimos el asunto y armamos el cuerpo del mensaje
$cuerpo = "<h1>".$asunto." - iContraincendios</h1><hr>";
$cuerpo .= "Nombre: <strong>".$nombre."</strong><br>";
$cuerpo .= "Email: <strong>".$email."</strong><hr>";
$cuerpo .= "".$mensaje;

// Esta es una pequena validaci¨®n, que solo envie el correo si todas las variables tiene algo de contenido:
if($nombre != '' && $email != '' && $asunto != '' && $mensaje != ''){
    mail($dest,$asunto,$cuerpo,$headers); //ENVIAR!
}
?>