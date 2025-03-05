<div class="d-flex align-items-stretch">
    <!-- Sidebar Navigation-->
    <nav id="sidebar">
      <!-- Sidebar Header-->
      <div class="sidebar-header d-flex align-items-center">
        <div class="title">
          <h1 class="h5">Admin Control Panel</h1>
        </div>
      </div>
      <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
      <ul class="list-unstyled">
              <li class="active"><a href="index.html"> <i class="icon-home"></i>Home </a></li>
              <li>
                <a href="#hotelRoomsDropdown" aria-expanded="false" data-toggle="collapse">
                  <i class="icon-windows"></i>Hotel Rooms
                </a>
                <ul id="hotelRoomsDropdown" class="collapse list-unstyled">
                  <li><a href="{{ url('create_room') }}">Add Rooms</a></li>
                  <li><a href="{{ url('view_room') }}">View Rooms</a></li>
                </ul>
              </li>

              <li>
                <a href="#staffManagementDropdown" aria-expanded="false" data-toggle="collapse">
                  <i class="icon-windows"></i>Staff Management
                </a>
                <ul id="staffManagementDropdown" class="collapse list-unstyled">
                  <li><a href="{{ url('add_staff') }}">Add Staff</a></li>
                  <li><a href="{{ url('view_staff') }}">View Staff</a></li>
                </ul>
              </li>
              <li>
                <li>
                    <a href="{{ url('bookings') }}"> <i class="bi bi-book"></i>Bookings </a>
                </li>
                <li>
                    <a href="{{ url('view_gallary') }}"> <i class="bi bi-book"></i>Gallary </a>
                </li>
                <li>
                    <a href="{{ url('message') }}"> <i class="bi bi-book"></i>Messages </a>
                </li>
              </li>
      </ul>
    </nav>
