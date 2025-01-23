<h1 class="nombre-pagina">Olvidé Password</h1>
<p class="descripcion-pagina">Reestablece tu Password escribiendo tu correo a continuación</p>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email"
            id="email"
            placeholder="Tu Email"
            name="email"
            >
    </div>
    <div class="boton-login">
        <input type="submit" class="boton" value="Reestablecer Password">
    </div>
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta?. Inicia sesión👌</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta?. Crear una 😂</a>
</div>