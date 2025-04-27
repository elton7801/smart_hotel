<div class="d-flex align-items-stretch">
    <!-- Sidebar Navigation-->
    <nav id="sidebar">
      <!-- Sidebar Header-->
      <div class="sidebar-header d-flex align-items-center">
        <div class="title">
          <h1 class="h5">Admin Control Panel</h1>
        </div>
      </div>

        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ url('dashboard') }}"> <i class="icon-home"></i>Dashboard </a>
        </li>

        <li class="{{ Request::is('create_room') || Request::is('view_room') ? 'active' : '' }}">
            <a href="#hotelRoomsDropdown" aria-expanded="false" data-toggle="collapse">
                <i class="icon-windows"></i>Hotel Rooms
            </a>
            <ul id="hotelRoomsDropdown" class="collapse list-unstyled">
                <li class="{{ Request::is('create_room') ? 'active' : '' }}"><a href="{{ url('create_room') }}">Add Rooms</a></li>
                <li class="{{ Request::is('view_room') ? 'active' : '' }}"><a href="{{ url('view_room') }}">View Rooms</a></li>
            </ul>
        </li>

        <li class="{{ Request::is('add_staff') || Request::is('view_staff') ? 'active' : '' }}">
            <a href="#staffManagementDropdown" aria-expanded="false" data-toggle="collapse">
                <i class="icon-windows"></i>Staff Management
            </a>
            <ul id="staffManagementDropdown" class="collapse list-unstyled">
                <li class="{{ Request::is('add_staff') ? 'active' : '' }}"><a href="{{ url('add_staff') }}">Add Staff</a></li>
                <li class="{{ Request::is('view_staff') ? 'active' : '' }}"><a href="{{ url('view_staff') }}">View Staff</a></li>
            </ul>
        </li>

        <li class="{{ Request::is('bookings') ? 'active' : '' }}">
            <a href="{{ url('bookings') }}"> <i class="bi bi-book"></i>Bookings </a>
        </li>

        <li class="{{ Request::is('view_gallary') ? 'active' : '' }}">
            <a href="{{ url('view_gallary') }}"> <i class="bi bi-book"></i>Gallery </a>
        </li>

        <li class="{{ Request::is('message') ? 'active' : '' }}">
            <a href="{{ url('message') }}"> <i class="bi bi-book"></i>Messages </a>
        </li>
      </ul>
    </nav>
