<h1 class="nombre-pagina">Confirmar Cuenta ✔</h1>

<?php include __DIR__ . '/../templates/alertas.php'; ?>

<?php if ($usuario && $usuario->confirmado == 1) : ?>
    <p class="descripcion-pagina">Tu cuenta ya ha sido confirmada</p>

<?php else : ?>
    <?php if ($usuario) : ?>
        <p class="descripcion-pagina">Hemos enviado las instrucciones para confirmar tu cuenta a tu e-mail 😁</p>
    <?php else : ?>
        <p class="descripcion-pagina">Hubo un error al enviar el correo de confirmación, por favor intenta de nuevo 😢</p>
    <?php endif; ?>
<?php endif; ?>

<div class="acciones">
    <a href="/login" class="boton boton-verde">Iniciar Sesión</a>
</div>
