<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="/img/ctaeLogo.jpeg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">CTAE Guest House</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ 
                    Auth::user()->profile_picture 
                        ? '/img/uploads/profile_pictures/' . Auth::user()->profile_picture 
                        : '/img/user.png'
                }}" class="img-circle elevation-2" style="width: 36px; height: 36px; object-fit: cover;">
            </div>
            <div class="info">
                <router-link to="{{ '/profile/' . Auth::user()->username }}" class="d-block">
                    {{ Auth::user()->first_name .  ' ' . Auth::user()->last_name }}
                </router-link>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link sidebar-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </router-link>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link sidebar-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/users" class="nav-link sidebar-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Users</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/guests" class="nav-link sidebar-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Guests</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <router-link to="/rooms" class="nav-link sidebar-link">
                        <i class="fas fa-hotel nav-icon"></i>
                        <p>Rooms</p>
                    </router-link>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link sidebar-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Room Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/room/types" class="nav-link sidebar-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Room Types</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/room/services" class="nav-link sidebar-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Room Services</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link sidebar-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Booking Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/reservations" class="nav-link sidebar-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Reservations
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/reservation/checkIns" class="nav-link sidebar-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Check-In</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                
                <div class="separator" style="width: 100%; height: 1px; background-color: #4f5962; margin: .5rem 0 .75rem;"></div>
                <li class="nav-item">
                    <router-link to="{{ '/profile/' . Auth::user()->id }}" class="nav-link sidebar-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <a href="/logout" onclick="event.preventDefault(); document.getElementById('form').submit()" class="nav-link">
                        <i class="nav-icon fas fa-power-off text-danger"></i>
                        <p>
                            Logout
                        </p>
                    </a>

                    <form action="{{ url('/logout') }}" method="POST" id="form" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>