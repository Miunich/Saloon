<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear la cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form action="/crear-cuenta" class="formulario" method="POST">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text"
            id="nombre"
            placeholder="Tu Nombre"
            name="nombre"
            value="<?php echo s($usuario->nombre); ?>"
            >
    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text"
            id="apellido"
            placeholder="Tu Apellido"
            name="apellido"
            value="<?php echo s($usuario->apellido); ?>"
            >
    </div>
    <div class="campo">
        <label for="telefono">Télefono</label>
        <input type="tel"
            id="telefono"
            placeholder="Tu Télefono"
            name="telefono"
            value="<?php echo s($usuario->telefono); ?>"
            >
    </div>
    <div class="campo">
        <label for="email">Email</label>
        <input type="email"
            id="email"
            placeholder="Tu Email"
            name="email"
            value="<?php echo s($usuario->email); ?>"
            >
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password"
            id="password"
            placeholder="Tu Password"
            name="password"
            >
    </div>
    <div class="boton-login">
        <input type="submit" value="Crear Cuenta" class="boton">
    </div>
</form>
<div class="acciones">
    <a href="/">¿Ya tienes una cuenta?. Inicia sesión👌</a>
    <a href="/olvide">¿Olvidaste tu contraseña? 😂</a>
</div>