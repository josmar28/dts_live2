
<nav id="main-nav" role="navigation" class="text-center container-fluid hidden-lg">
    <input id="main-menu-state" type="checkbox" />
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon" style="font-weight: bolder;"></span>
    </label>
    <ul  id="main-menu" class="sm sm-mint">
    <li><a href="{{ url('/home') }}"><i class="fa fa-home"></i> Dashboard</a></li>
    <li><a href="{{ URL::to('document/accept') }}"><i class="fa fa-plus"></i> Accept Document</a></li>
    <li><a href="{{ URL::to('document') }}"><i class="fa fa-file"></i> Create Document</a></li>
    <li>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i> Print<span class="caret"></span></a>
        <ul>
            <li><a href="{{ URL::to('document/delivered') }}"><i class="fa fa-calendar"></i>&nbsp;&nbsp; Delivered Documents</a></li>
            <li><a href="{{ URL::to('document/received') }}"><i class="fa fa-hourglass-half"></i>&nbsp;&nbsp; Received Documents</a></li>
        </ul>
    </li>
    @if(Session::get('auth')->user_priv==1)
        <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gear"></i> Settings<span class="caret"></span></a>
            <ul>
                <li><a href="{{ asset('/users')  }}"><i class="fa fa-users"></i>&nbsp;&nbsp; Users</a></li>
                <li class="divider"></li>
                <li><a href="{{ asset('/designation') }}"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp; Designation</a></li>
                <li><a href="{{ asset('/section') }}"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp; Section</a></li>
                <li><a href="{{ asset('/division') }}"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp; Division</a></li>
                <li class="divider"></li>
                <li><a href="{{ asset('document/filter') }}"><i class="fa fa-filter"></i>&nbsp;&nbsp; Filter Documents</a></li>
            </ul>
        </li>
    @endif
    <li>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Account<span class="caret"></span></a>
        <ul>
            <li><a href="{{ asset('/change/password')  }}"><i class="fa fa-unlock"></i>&nbsp;&nbsp; Change Password</a></li>
            <li class="divider"></li>
            <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i>&nbsp;&nbsp; Logout</a></li>
        </ul>
    </li>
        <li class="active"><a href="#trackDoc" data-toggle="modal"><i class="fa fa-search"></i> Track Document</a></li>
</ul>
</nav>

