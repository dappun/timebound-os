@extends('core::layouts.master')

@section('content')


    <section class="header header-login page" id="HOME">
        <h2></h2>
        <div class="section_overlay">
            
            <div class="container home-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="logo text-center">
                            <!-- LOGO -->
                            <a href="{{ url('/') }}"><img src="{{ asset('logo.png') }}" alt="{{ env('SITE_NAME', '') }}"></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-md-offset-3">
                        <div class="page-box home_text">
                            @if (isset($status) && $status)
                                @if ($status == 'error')
                                    <h1>Verification Error</h1>
                                    <p class="error">The link seems to be broken. Please try again.</p>
                                @elseif ($status == 'success')
                                    <h1>Thank you!</h1>
                                    <p>You've been verified. Please continue to the login page.</p>
                                    <p><a href="{{ url('/login') }}" class="btn btn-default btn-primary">Login</a></p>
                                @endif
                            @else
                            <h1>Verify it's you</h1>
                            <p>You're almost there. We sent you an email to verify your identity. Please check your email to continue.</p>
                            <p><a href="{{ url('/') }}" class="btn btn-default">Go Back</a></p>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
