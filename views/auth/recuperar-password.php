<h1 class="nombre-pagina">Recuperar Password ╰(*°▽°*)╯</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php include __DIR__ . '/../templates/alertas.php'; ?>

<?php if($error) return; ?>

<!-- Si pongo el action va a borrar el token de la url -->
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            placeholder="Tu Password"
            name="password">
    </div>
    <div class="boton-login">
        <input type="submit" class="boton " value="Guardar nuevo Password">
    </div>

</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuentas? Inicia sesión😎</a>
    <a href="/crear-cuenta">¿Aún no tienes cuenta?, Registrate 😁</a>
</div>
