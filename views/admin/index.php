<h1 class="nombre-pagina">Panel de administracion</h1>
<?php

use Model\Cita;

include_once __DIR__ . '/../templates/barra.php' ?>

<h2>Buscar citas</h2>
<div class="busqueda">
    <form class="formulario" action="">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo $fecha?>">
        </div>
    </form>
</div>
<?php if(count($citas) === 0){
    echo "<h2>No hay citas en esta fecha </h2>";
} ?>
<div id="citas-admin">
    <ul class="citas">
        <?php foreach ($citas as $key => $cita) {
            if ($idcitas !== $cita->id) { $total=0;?>
                <li>
                    <p>Id: <span><?php echo $cita->id ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora ?></span></p>
                    <p>Email: <span><?php echo $cita->email ?></span></p>
                    <p>Telefono: <span><?php echo $cita->telefono ?></span></p>
                    <h3>Servicios</h3>
                <?php $idcitas = $cita->id;
            } //fin if;
            $total += $cita->precio;
                ?>
                    <p class="servicio"><?php echo $cita->servicio ."  ". $cita->precio ?></p>
                    <?php 
                    $actual = $cita->id;
                    $proximo = $citas[$key + 1]->id ?? 0;

                    if(esUltimo($actual,$proximo)){ ?>
                        <p class="total">Total: <span>$ <?php echo $total; ?></span></p>
                        <form action="/api/eliminar" method="POST">
                            <input type="hidden" name="id" value="<?php echo $cita->id ?>">
                            <input type="submit" class="boton-eliminar" value="Eliminar">
                        </form>
                    <?php } ?>
        <?php } //fin for each;?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>";
?>

