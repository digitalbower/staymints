 <header class="pc-header">
  <div class="header-wrapper flex max-sm:px-[15px] px-[25px] grow"><!-- [Mobile Media Block] start -->
<div class="me-auto pc-mob-drp">
  <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
    <!-- ======= Menu collapse Icon ===== -->
    <li class="pc-h-item pc-sidebar-collapse max-lg:hidden lg:inline-flex">
      <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="sidebar-hide">
        <i data-feather="menu"></i>
      </a>
    </li>
    <li class="pc-h-item pc-sidebar-popup lg:hidden">
      <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="mobile-collapse">
        <i data-feather="menu"></i>
      </a>
    </li>
    <li class="dropdown pc-h-item">
      <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#" role="button"
        aria-haspopup="false" aria-expanded="false">
        <i data-feather="search"></i>
      </a>
      <div class="dropdown-menu pc-h-dropdown drp-search">
        <form class="px-2 py-1">
          <input type="search" class="form-control !border-0 !shadow-none" placeholder="Search here. . ." />
        </form>
      </div>
    </li>
  </ul>
</div>
<!-- [Mobile Media Block end] -->
<div class="ms-auto">
  <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
    <li class="dropdown pc-h-item">
      <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
        @php
            $logoutRoute = route('admin.logout'); // default
            if (auth('admin')->check() && auth('admin')->user()->user_role_id === 4) {
                $logoutRoute = route('admin.logout.sales');
            }
        @endphp 
        <form id="logout-form" action="{{ $logoutRoute }}" method="POST" class="d-none">
        @csrf
        </form>
        <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="ti ti-power"></i>
          <span>Logout</span>
        </a>
      </div>
    </li>
    <li class="dropdown pc-h-item header-user-profile">
      <a class="pc-head-link dropdown-toggle arrow-none me-0" data-pc-toggle="dropdown" href="#" role="button"
        aria-haspopup="false" data-pc-auto-close="outside" aria-expanded="false">
        <i data-feather="user"></i>
      </a>
      <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown p-2 overflow-hidden">
        <div class="dropdown-header flex items-center justify-between py-4 px-5 bg-primary-500">
          <div class="flex mb-1 items-center">
            <div class="grow ms-3">
              <h6 class="mb-1 text-white">{{auth()->guard('admin')->user()->name}}</h6>
              <span class="text-white">{{auth()->guard('admin')->user()->email}}</span>
            </div>
          </div>
        </div>
        <div class="dropdown-body py-4 px-5">
          <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
              @php
                $logoutRoute = route('admin.logout'); // default
                if (auth('admin')->check() && auth('admin')->user()->user_role_id === 4) {
                    $logoutRoute = route('admin.logout.sales');
                }
              @endphp 
            <div class="grid my-3">
              <form id="logout-form" action="{{ $logoutRoute }}" method="POST" class="d-none">
              @csrf
              </form>
              <button class="btn btn-primary flex items-center justify-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg class="pc-icon me-2 w-[22px] h-[22px]">
                  <use xlink:href="#custom-logout-1-outline"></use>
                </svg>
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div></div>
</header>
<!-- [ Header ] end -->