<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" 
   data-accordion="false">
   <li class="nav-item">
      <a href="{{url('/dashboard')}}"
         class="nav-link {{Request::segment(1) == 'dashboard' ? 'active' : ''}}">
         <i class="fas fa-home"></i>
         <p> &nbsp;
            Dashboard
         </p>
      </a>
   </li>

   <li class="nav-item">
      <a href="{{ url('/spv-pengajuan') }}"
         class="nav-link {{ Request::segment(1) == 'spv-pengajuan' ? 'active' : '' }}">
         <i class="fas fa-calendar-check"></i>
         <p>&nbsp; 
            Pengajuan Lembur
         </p>
      </a>
   </li>
</ul>

<style>
   .nav-sidebar .nav-item > .nav-link.active, .nav-treeview > .nav-item > .nav-link.active {
      background-color: #007bff !important;
      color: white;
   }
</style>
