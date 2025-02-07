<h1 class="nombre-pagina">Recuperar su contraseña</h1>
<p class="descripcion-pagina">Ingrese y confirme su nueva contraseña</p>

<?php 
include_once __DIR__."/../templates/alertas.php";

if($error) return;
?>
<form class="formulario" method="POST">
    
    <div class="campo">
        <label for="password">Nueva Contraseña</label>
        <input type="password" id="password" placeholder="Tu nueva contraseña" name="password">
    </div>

    <input type="submit" class="boton" value="Cambiar contraseña">
</form>

<div class="acciones">
    <a href="/">Iniciar sesion</a>
    <a href="/crear-cuenta">Crear una nueva cuenta</a>
</div>