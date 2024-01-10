@component('mail::message')
# U bent uitgenodigd voor BitClock

Beste {{ $user['name'] }},

Je hebt een uitnodiging ontvangen van {{config('app.name')}}. Klik hieronder om je account te activeren.

@component('mail::button', ['url' => url("/registreren/" . $user->invite_token), 'color' => 'success'])
    Account activeren
@endcomponent

@component('mail::subcopy')
    Met vriendelijke groeten,<br>
    Beheerders van {{ config('app.name') }}
@endcomponent
@endcomponent
