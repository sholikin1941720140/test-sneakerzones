<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#1166d8;">
  <!-- Brand Logo -->
  <a href="/dashboard" class="brand-link" style="border-color: white;">
    <img src="{{url('dist/img/snz.jpeg')}}" alt="SIMAS Logo" class="brand-image img-circle elevation-3" 
    style="opacity: .8;background-color: white;">
    <span class="brand-text font-weight-light">SIMAL</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="border-color: white;">
      <div class="image" style="opacity: 0">
      User:
      </div>
      <div class="info d-flex flex-column" style="white-space: normal;">
        <a href="/dashboard" class="d-block">
          <b>{{ Auth::user()->name }}</b> - {{ ucfirst(Auth::user()->role) }}
        </a>
      </div>
    </div>
    <nav class="mt-2">
      @include('layouts.wrapper.role.'.$role)
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

<style>
  .nav-link {
    padding: 10px 10px;
    color: #4b4b4b;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
  }

  .nav-link i {
    min-width: 24px;
    text-align: center;
    vertical-align: middle;
  }

  .nav-link p {
    margin-bottom: 0;
    line-height: 1.5;
    vertical-align: middle;
  }

  .nav-link:hover {
    background-color: #005fc5;
    color: #fff;
  }

  .nav-link.active {
    background-color: #007bff;
    color: white;
  }

  .nav-link.active i {
    color: white;
  }
</style>
