<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $apellido;
    public $token;

    public function __construct($email, $nombre,$apellido, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        //crear el objeto email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->SMTPSecure = 'tls';
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];   
        
        $mail->setFrom('santiagotutistar289@gmail.com','Appsalon.com');
        $mail->addAddress(''.$this->email.'');
        $mail->Subject = 'confirma tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<hmtl>";
        $contenido .= "<p><strong>Hola ".$this->nombre." ".$this->apellido."</strong> Has creado tu cuenta en APPSalon</p>";
        $contenido .= "<p>Solo debes confirmarla</p>";
        $contenido .= "<p>preciona el siguiente enlace</p>";
        $contenido .= "<p>presiona aqui: <a href='".$_ENV['APP_URL']."/confirmar-cuenta?token=".$this->token."'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste la cuenta, ignora y elimina el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar correo
        $mail->send();
    }

    public function olvideContraseña(){
        //crear el objeto email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->SMTPSecure = 'tls';
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'] ;   
        
        $mail->setFrom('santiagotutistar289@gmail.com','Appsalon.com');
        $mail->addAddress(''.$this->email.'');
        $mail->Subject = 'Olvidaste tu contraseña';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<hmtl>";
        $contenido .= "<p><strong>Hola ".$this->nombre." ".$this->apellido."</strong> Has olvidado tu contraseña</p>";
        $contenido .= "<p>preciona el siguiente enlace</p>";
        $contenido .= "<p>presiona aqui: <a href='".$_ENV['APP_URL']."/recuperar?token=".$this->token."'> Para cambiar tu contraseña</a></p>";
        $contenido .= "<p>Si tu no solicitaste, ignora y elimina el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar correo
        $mail->send();
    }
}

?>