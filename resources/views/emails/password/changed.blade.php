@component('mail::message')
# Je wachtwoord is veranderd

Beste {{ $user['name'] }},

Je wachtwoord van {{ config('app.name') }} is onlangs gewijzigd.

Als je niet zeker weet of je deze wachtwoordreset hebt uitgevoerd, kun je contact opnemen met de beheerders van het systeem.
@endcomponent

@component('mail::subcopy')
Met vriendelijke groeten,<br>
Beheerders van {{ config('app.name') }}
@endcomponent
@endcomponent
