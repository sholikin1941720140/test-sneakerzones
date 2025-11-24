<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #1166d8">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color: rgb(255, 255, 255)"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" role="button">
                <b id="clock" style="color: rgb(255, 255, 255)">
                </b> &nbsp;
                <i class="fas fa-clock" style="color: rgb(255, 255, 255)"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-controlsidebar-slide="true" style="cursor: pointer;color: rgb(255, 255, 255);" 
                href="{{ url('/logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" role="button">
                <b>Keluar</b> &nbsp;
                <i class="fas fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{url('/logout')}}" method="GET" class="d-none">
            @csrf
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </form>
        </li>
    </ul>
</nav>
<!-- /.navbar -->