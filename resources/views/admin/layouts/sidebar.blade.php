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
          <a href="{{route('admin.index')}}" class="pc-link">
            <span class="pc-micon">
              <i data-feather="home"></i>
            </span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>
        @if(auth()->guard('admin')->user()->hasPermission('view_footer'))
        <li class="pc-item pc-caption">
          <label>Contents</label>
          <i data-feather="feather"></i>
        </li>
        @if(auth()->guard('admin')->user()->hasPermission('view_footer'))
        <li class="pc-item pc-hasmenu">
          <a href="{{route('admin.footers.index')}}" class="pc-link">
            <span class="pc-micon"> <i data-feather="type"></i></span>
            <span class="pc-mtext">Footers</span>
          </a>
        </li>
       @endif
       @endif
        @if(auth()->guard('admin')->user()->hasPermission('view_adminusers'))
        <li class="pc-item pc-caption">
          <label>Users</label>
          <i data-feather="monitor"></i>
        </li>
        @if(auth()->guard('admin')->user()->hasPermission('view_adminusers'))
        <li class="pc-item pc-hasmenu">
        <a href="{{route('admin.users.index')}}" class="pc-link">
          <span class="pc-micon"> <i data-feather="edit"></i></span>
          <span class="pc-mtext">Admin Users</span>
        </a>
        </li>
        @endif
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('view_packages') || (auth()->guard('admin')->user()->hasPermission('view_seo'))||auth()->guard('admin')->user()->hasPermission('view_categories'))
        <li class="pc-item pc-caption">
          <label>Other</label>
          <i data-feather="sidebar"></i>
        </li>
        @if(auth()->guard('admin')->user()->hasPermission('view_categories'))
        <li class="pc-item pc-hasmenu">
          <a href="{{route('admin.categories.index')}}" class="pc-link">
            <span class="pc-micon"> <i data-feather="feather"></i></span>
            <span class="pc-mtext">Categories</span>
          </a>
        </li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('view_packages'))
        <li class="pc-item pc-hasmenu">
          <a href="{{route('admin.packages.index')}}" class="pc-link">
            <span class="pc-micon"> <i data-feather="feather"></i></span>
            <span class="pc-mtext">Packages</span>
          </a>
        </li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('view_seo'))
        <li class="pc-item pc-hasmenu">
          <a href="{{route('admin.seo.index')}}" class="pc-link">
            <span class="pc-micon"> <i data-feather="feather"></i></span>
            <span class="pc-mtext">Main Seo</span>
          </a>
        </li>
        @endif
        @endif
      </ul>
    </div>
  </div>
</nav>