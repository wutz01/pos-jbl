<div class="sidebar-wrapper">
  <div class="logo">
    <a href="{{ url('/') }}" class="simple-text">
      JBL Pharmacy
    </a>
  </div>

  <ul class="nav">
    @if(Auth::user()->hasRole(['admin', 'owner']))
    <li class="active">
      <a href="{{ route('dashboard') }}">
        <i class="ti-panel"></i>
        <p>Dashboard</p>
      </a>
    </li>
    <!-- <li class="side-nav-user">
      <a href="user.html">
        <i class="ti-user"></i>
        <p>User Profile</p>
      </a>
    </li> -->
    <li class="side-nav-inventory">
      <a href="{{ route('inventory.index') }}">
        <i class="ti-folder"></i>
        <p>Inventory</p>
      </a>
    </li>
    @endif
    <li class="side-nav-sales">
      <a href="{{ route('sales') }}">
        <i class="ti-shopping-cart"></i>
        <p>Sales</p>
      </a>
    </li>
    <li class="side-nav-reports">
      <a href="{{ route('reports') }}">
        <i class="ti-book"></i>
        <p>Reports</p>
      </a>
    </li>
    <li class="side-nav-profile">
      <a href="{{ route('profile') }}">
        <i class="ti-user"></i>
        <p>My Profile</p>
      </a>
    </li>
    <!-- <li>
      <a href="icons.html">
        <i class="ti-pencil-alt2"></i>
        <p>Icons</p>
      </a>
    </li>
    <li>
      <a href="maps.html">
        <i class="ti-map"></i>
        <p>Maps</p>
      </a>
    </li>
    <li>
      <a href="notifications.html">
        <i class="ti-bell"></i>
        <p>Notifications</p>
      </a>
    </li> -->
  </ul>
</div>
