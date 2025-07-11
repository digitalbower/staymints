<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header flex items-center py-4 px-6 h-header-height">
      <a href="../dashboard/index.html" class="b-brand flex items-center gap-3">
        <!-- ========   Change your logo from here   ============ -->
        <img src="{{asset('assets/images/logo.svg')}}" class="img-fluid logo logo-lg" alt="logo" />
        <img src="{{asset('assets/images/favicon.svg')}}" class="img-fluid logo logo-sm" alt="logo" />
      </a>
    </div>
    <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
      <ul class="pc-navbar">
        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>
        <li class="pc-item">
        <li class="pc-item">
          <a href="{{route('admin.index')}}" class="pc-link">
            <span class="pc-micon">
              <i data-feather="home"></i>
            </span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>
        <li class="pc-item pc-caption">
          <label>Contents</label>
          <i data-feather="feather"></i>
        </li>
       
        <li class="pc-item pc-hasmenu">
          <a href="{{route('admin.footers.index')}}" class="pc-link">
            <span class="pc-micon"> <i data-feather="type"></i></span>
            <span class="pc-mtext">Footers</span>
          </a>
        </li>
       

        <li class="pc-item pc-caption">
          <label>Users</label>
          <i data-feather="monitor"></i>
        </li>
        <li class="pc-item pc-hasmenu">
        <a href="{{route('admin.users.index')}}" class="pc-link">
          <span class="pc-micon"> <i data-feather="edit"></i></span>
          <span class="pc-mtext">Admin Users</span>
        </a>
        </li>
        </li>
        <li class="pc-item pc-caption">
          <label>Other</label>
          <i data-feather="sidebar"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="{{route('admin.packages.index')}}" class="pc-link">
            <span class="pc-micon"> <i data-feather="feather"></i></span>
            <span class="pc-mtext">Packages</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>