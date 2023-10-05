<x-mail::message>
# Olá {{ $firstName }}

Bem vindo a plataforma FControl.

Clique no botão abaixo para confirmar seu endereço de e-mail e ter acesso a plataforma.

<x-mail::button :url="$url">
Confirmar e-mail
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}

<hr>

Se você estiver com problemas para clicar no botão "Confirmar e-mail",
copie e cole essa URL no seu navegador: <a href="{{ $confirmEmailUrl }}">{{ $confirmEmailUrl }}</a>
</x-mail::message>
