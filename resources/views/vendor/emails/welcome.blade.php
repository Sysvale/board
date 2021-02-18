@component('mail::message')
<div>
    <h2>
    Eae {{ $user->name }},
    </h2>
    <br>
    <p>
        Seu email foi cadastrado no {{ config('app.name') }}<br>
        Clique no botão abaixo para definir sua senha para acessar a plataforma.
    </p>
<br>
@component('mail::button', ['url' => $url])
    Cadastrar senha
@endcomponent

<br>
    <p style="text-align: center;">
        Atenciosamente, <strong>Sysvale Team</strong>.
    </p>
</div>
@endcomponent
