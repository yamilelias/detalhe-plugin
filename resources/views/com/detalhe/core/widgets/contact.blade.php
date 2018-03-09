<?php
/**
 * Author: yamilelias
 * Author URI: <yamileliassoto@gmail.com>
 * Date: 5/10/17
 * Time: 11:24 AM
 */
use Com\Detalhe\Core\Controllers\Mailer;
use Themosis\Facades\Form;

?>

<h3>Contacto</h3>
{!! Form::open(get_template_directory_uri() . '/mailing.php', 'post', false, [
    'class'       => 'footer-form',
    'id'          => 'register-form'
]) !!}
    <div style="display:none;" class="newsletter-success alert alert-success alert-dismissable fade in">

        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

        <strong>¡Gracias!</strong> Hemos recibido tu correo y nos pondremos en contacto pronto.
    </div>
    {!! Form::text('name', '', [
    'class'       => 'input-name',
    'placeholder' => 'Nombre'
    ]) !!}
    {!! Form::text('email', '', [
        'class'       => 'input-email',
        'placeholder' => 'nombre@tucorreo.com'
    ]) !!}
    {!! Form::textarea('message', '', [
        'class' => 'input-message',
        'placeholder' => 'Mensaje',
        'cols' => 50,
        'rows' => 2
        ]) !!}
    {!! Form::submit('submit', 'Enviar', [
        'id' => 'awesome',
        'class' => 'submit',]) !!}
{!! Form::close() !!}
