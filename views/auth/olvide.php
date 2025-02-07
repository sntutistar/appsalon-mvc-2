<h1 class="nombre-pagina">Olvido su contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña con el correo</p>

<?php
include_once __DIR__.'/../templates/alertas.php'
?>
<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">Correo</label>
        <input type="email" id="email" name="email" placeholder="Tu Correo">
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones">
</form>

<div class="acciones">
    <a href="/">Iniciar sesion</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? - Crear una</a>
</div>