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

<h3>Contact</h3>
{!! Form::open(get_template_directory_uri() . '/mailing.php', 'post', false, [
    'class'       => 'footer-form',
    'id'          => 'register-form'
]) !!}
    <div style="display:none;" class="newsletter-success alert alert-success alert-dismissable fade in">

        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

        <strong>Thank you!</strong> Your subscription is complete.
    </div>
    {!! Form::text('name', '', [
    'class'       => 'input-name',
    'placeholder' => 'Name'
    ]) !!}
    {!! Form::text('email', '', [
        'class'       => 'input-email',
        'placeholder' => 'your@mail.com'
    ]) !!}
    {!! Form::textarea('message', '', [
        'class' => 'input-message',
        'placeholder' => 'Message',
        'cols' => 50,
        'rows' => 4
        ]) !!}
    {!! Form::submit('submit', 'Submit', [
        'id' => 'awesome',
        'class' => 'submit',]) !!}
{!! Form::close() !!}


{{--<form class="footer-form" action="mailto:hola@detalhe.com.mx" method="post" enctype="text/plain">--}}
    {{--<div style="display:none;" class="newsletter-success alert alert-success alert-dismissable fade in">--}}

        {{--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>--}}

        {{--<strong>Thank you!</strong> Your subscription is complete.--}}
    {{--</div>--}}
    {{--<input class="input-name" type="text" name="name" placeholder="name" size="12">--}}
    {{--<input class="input-email" type="email" name="email" placeholder="your@mail.com" size="12">--}}
    {{--<br>--}}
    {{--<textarea class="input-message" name="message" placeholder="Message" rows="4" cols="10"></textarea>--}}
    {{--<br>--}}
    {{--<button class="submit">Submit</button>--}}
{{--</form>--}}


