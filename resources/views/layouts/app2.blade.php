<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Laravel
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Session::get('auth')->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
<ul class="dropdown-menu">
                <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file"></i>&nbsp;&nbsp; Contract/MOA/Memorandum of Undertaking</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('contract/salary') }}">Salary, Honoraria, Stipend, Remittances, CHT Mobilization</a></li>
                        <li><a href="{{ url('contract/') }}">TEV</a></li>
                        <li><a href="{{ url('contract/') }}">Bills, Cash Advance Replenishment, Grants/Fund Transfer</a></li>
                        <li><a href="{{ url('contract/') }}">Supplier (Payment of Transactions with PO)</a></li>
                        <li><a href="{{ url('contract/') }}">Infra - Contractor</a></li>  
                        <li class="divider"></li>
                        <li><a href="{{ url('contract/') }}">Resolutions</a></li>
                        <li><a href="{{ url('contract/') }}">Appointment</a></li>
                    </ul>
                </li>
                
                <li><a href="{{ url('') }}"><i class="fa fa-file"></i>&nbsp;&nbsp; Disbursement Voucher</a></li>
                <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file"></i>&nbsp;&nbsp; Letter/Mail/Communication</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('') }}">Incoming</a></li>
                        <li><a href="{{ url('') }}">Outgoing</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('') }}">Service Record</a></li>
                        <li><a href="{{ url('') }}">SALN</a></li>
                        <li><a href="{{ url('') }}">Plans (includes Allocation List)</a></li>
                        <li><a href="{{ url('') }}">Routing Slip</a></li>
                    </ul>
                </li>
                <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file"></i>&nbsp;&nbsp; Memorandum</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('') }}">Office Order</a></li>
                        <li><a href="{{ url('') }}">ISO Documents</a></li>
                    </ul>
                </li>
                <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file"></i>&nbsp;&nbsp; Miscellaneous</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('') }}">Activity Worksheet</a></li>
                        <li><a href="{{ url('') }}">Justification</a></li>
                        <li><a href="{{ url('') }}">Certificate of Appearance</a></li>
                        <li><a href="{{ url('') }}">Certificate of Employment</a></li>
                        <li><a href="{{ url('') }}"> Certificate of Clearance</a></li>
                    </ul>
                </li>                
                <li class="dropdown-submenu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file"></i>&nbsp;&nbsp; Personnel Related Documents</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('') }}">Office Order</a></li>
                        <li><a href="{{ url('') }}">DTR</a></li>
                        <li><a href="{{ url('') }}">Application for Leave</a></li>
                        <li><a href="{{ url('') }}">Certificate of Overtime Credit</a></li>
                        <li><a href="{{ url('') }}">Compensatory Time Off</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('') }}"><i class="fa fa-file"></i>&nbsp;&nbsp; Purchase Order</a></li>
                <li><a href="{{ url('') }}"><i class="fa fa-file"></i>&nbsp;&nbsp; Purchase Request - Cash Advance Purchase</a></li>
                <li><a href="{{ url('') }}"><i class="fa fa-file"></i>&nbsp;&nbsp; Purchase Request - Regular Purchase</a></li>
                <li><a href="{{ url('') }}"><i class="fa fa-file"></i>&nbsp;&nbsp; Reports</a></li>
              </ul>
