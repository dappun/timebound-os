<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('SITE_NAME', '') }}</title>

    <link rel='stylesheet' href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" type='text/css'>
    <link rel="stylesheet" href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap/css/bootstrap-theme.min.css') }}">
    
    <script type="text/javascript" src="{{ asset('dist/main.js') }}"></script>
    <script type="text/javascript">
        /* All core configurations from backend */
        var core = {};
        core.url = function(uri)
        {
            return '{{ url('/') }}' + uri;
        }

        core.userTimezone = '{!! userTimezone() !!}';

        core.pad = function (d) {
            return (d < 10) ? '0' + d.toString() : d.toString();
        }

        core.formatGMH = function(seconds) {
            hours = Math.floor(seconds / 3600);
            minutes = Math.floor(seconds / 60 % 60);
            secs = Math.floor(seconds % 60);
            
            return core.pad(hours) + ':' + core.pad(minutes) + ':' + core.pad(secs);
        }

        core.setting = {
            alert_notice: '{{ Config::get('timesheet.overtime.notice') }}',
            alert_warning: '{{ Config::get('timesheet.overtime.warning') }}'
        }
    </script>

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('style')
</head>
<body id="app-layout">

<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" alt="{{ env('SITE_NAME', '') }}">
                
            </a>
        </div>

        @if (Auth::guest())
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ url('/login') }}">Login</a></li>
                <li><a href="{{ url('/register') }}">Register</a></li>
            </ul>
        </div>
        @else
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @include('core::layouts.menu')
            </ul>

            <div class="pull-right">
                <ul class="nav navbar-nav">
                <li class="{{ \Request::is('report*') ? 'active' : '' }}">
                    <a class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src="{{ url(\Auth::user()->profile_image) }}" class="img-circle special-img" style="height:25px;">
                    <span class="name">{{\Auth::user()->name}}</span>
                    <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                        <li><a href="{{ route('account.view') }}">My Account</a></li>
                        <li><a href="{{ route('account.setting') }}">Settings</a></li>
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    </ul>
                </li>
                </ul>
            </div>
        </div>

        @endif
    </div>
</nav>

<!-- Page Content -->
<div id="page-content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->

@yield('scripts')

</body>
</html>