<h1 class="nombre-pagina">Panel de Administración</h1>

<h2>Buscar Citas</h2>
<?php
    include_once __DIR__ . '/../templates/barra.php';

?>

<div id="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
            type="date"
            id="fecha"
            name="fecha">
        </div>
    </form>
</div>

<div id="citas-admin">
    <!-- <h2>Citas</h2> -->
    <ul class="citas">

    
    <?php 
        $idCita = 0;
        foreach($citas as $cita){
            if($idCita != $cita->id){
                
    ?>
    <li>
        <p>ID: <span><?php echo $cita->id; ?></span></p>
        <p>Hora: <span><?php echo $cita->hora; ?></span></p>
        <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
        <p>Email: <span><?php echo $cita->email; ?></span></p>
        <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>
        <h3>Servicios</h3>
        
        <?php $idCita = $cita->id; } //Fin de IF?>
        <p class="servicio"><?php echo $cita->servicio . " " .$cita->precio; ?></p>
    
    <?php }//Fin de Foreach ?>
    </ul>
</div>