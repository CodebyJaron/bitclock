@component('mail::message')
# Wachtwoord opnieuw instellen

Beste {{ $user['name'] }},

Je hebt ons gevraagd om je wachtwoord van {{ config('app.name') }} te resetten.
Klik op de onderstaande knop om je wachtwoord te veranderen:

@component('mail::button', ['url' => url("/wachtwoord-vergeten/reset?reset_token=$user[reset_token]"), 'color' => 'success'])
    Wachtwoord veranderen
@endcomponent

Als je niet van plan was om je wachtwoord opnieuw in te stellen, kun je
deze e-mail gewoon negeren. Je wachtwoord zal niet worden veranderd.

@component('mail::subcopy')
    Met vriendelijke groeten,<br>
    Beheerders van {{ config('app.name') }}
@endcomponent
@endcomponent
