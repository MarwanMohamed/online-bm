@component('mail::message')
    # Welcome to “StarterKit” System,

    Your email address has been used recently in the system.  If it was used by you kindly confirm that by pressing the button below.
    If you have questions about why you’re receiving this email, or if you’re having any trouble verifying your email via the button above, we’re here to help!

@component('mail::button', ['url' => $url])
    Verify Email
@endcomponent

    Cheers,
    StarterKit Team
@endcomponent
