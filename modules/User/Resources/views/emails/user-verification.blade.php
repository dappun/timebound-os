<h1>You're nearly there!</h1>

<p>Please click the link below to activate your account.</p>

<p><a href="{{ $link = url('verification', $user->verification_token) . '?email=' . urlencode($user->email) }}">Verify email address</a></p> 

<p>You may copy/paste this link into your browser:<br/>
{{ $link }}</p>

<p>Please note that this link will expire in 5 days.</p>

<p>If you have not signed up to {{ env('SITE_NAME') }}, please ignore this email.</p>