<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Hostel Management</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="dashboard.html" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('registration.index') }}"
                        class="nav-link {{ Request::is('registration*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Registration</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('fee.index') }}" class="nav-link {{ Request::is('fee*') ? 'active' : '' }}">
                        <i class="fas fa-dollar-sign"></i>
                        <p>Apply Fees</p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('user_fee.list') }}"
                        class="nav-link {{ Request::is('user_fee*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <p>Fee List</p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('expense.index') }}"
                        class="nav-link {{ Request::is('expense*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <p>Expenses</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('staff.index') }}" class="nav-link {{ Request::is('staff*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <p>Staff</p>
                    </a>
                </li>

                <!-- Settings Menu -->
                <li
                    class="nav-item has-treeview {{ Request::is('floor*') || Request::is('rooms*') || Request::is('expense_head*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Request::is('floor*') || Request::is('rooms*') || Request::is('expense_head*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('floor.index') }}"
                                class="nav-link {{ Request::is('floor*') ? 'active' : '' }}">
                                <i class="fas fa-hotel nav-icon"></i>
                                <p>Floors</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rooms.index') }}"
                                class="nav-link {{ Request::is('rooms*') ? 'active' : '' }}">
                                <i class="fas fa-door-open nav-icon"></i>
                                <p>Rooms</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('expense_head.index') }}"
                                class="nav-link {{ Request::is('expense_head*') ? 'active' : '' }}">
                                <i class="fas fa-tags nav-icon"></i>
                                <p>Expense Head</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
