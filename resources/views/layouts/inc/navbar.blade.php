
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <button class="btn btn-dark me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard.index') }}">Asset Inventory Management</a>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
    
            </li>
        </ul>
    </nav>
    
    <div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="staticBackdropLabel" style="color: fff;">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="{{ route('dashboard.index') }}" class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <i class="bi bi-house"></i>
                    <span class="ms-2">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('inventory.index') }}" class="sidebar-link {{ request()->routeIs('inventory.index', 'inventory.create') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span class="ms-2">Assets</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="" class="sidebar-link">
                    <i class="bi bi-boxes"></i>
                    <span class="ms-2">Components</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="" class="sidebar-link">
                    <i class="bi bi-headphones"></i>
                    <span class="ms-2">Accessories</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.index', 'users.create', 'users.edit') ? 'active' : '' }}"">
                    <i class="bi bi-people"></i>
                    <span class="ms-2">Users</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="#settingsCollapse" class="sidebar-link d-flex align-items-center" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('customfields.index') || request()->routeIs('categories.index') || request()->routeIs('departments.index') ? 'true' : 'false' }}" aria-controls="settingsCollapse">
                    <i class="bi bi-gear"></i>
                    <span class="ms-2">Settings</span>
                    <i class="bi bi-chevron-left ms-auto chevron-icon"></i>
                </a>
                <div class="collapse sidebar-collapse {{ request()->routeIs('customfields.index') || request()->routeIs('categories.index') || request()->routeIs('departments.index') ? 'show' : '' }}" id="settingsCollapse">
                    <ul class="sidebar-submenu">
                        <li class="sidebar-item">
                            <a href="{{ route('customfields.index') }}" class="sidebar-link {{ request()->routeIs('customfields.index') ? 'active' : '' }}">
                                <i class="bi bi-wrench"></i>
                                <span class="ms-2">Custom Fields</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                <i class="bi bi-folder"></i>
                                <span class="ms-2">Categories</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('departments.index') }}" class="sidebar-link {{ request()->routeIs('departments.index') ? 'active' : '' }}">
                                <i class="bi bi-building"></i>
                                <span class="ms-2">Departments</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="sidebar-item">
                <a href="#settingsACollapse" class="sidebar-link d-flex align-items-center" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="settingsACollapse">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span class="ms-2">Reports</span>
                    <i class="bi bi-chevron-left ms-auto chevron-icon"></i>
                </a>
                <div class="collapse sidebar-collapse" id="settingsACollapse">
                    <ul class="sidebar-submenu">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-bootstrap-fill"></i>
                                <span>Sample</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="bi bi-bootstrap-fill"></i>
                                <span>Sample</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>                      
                    
        </ul>
    
        <div class="sidebar-footer">
            <a href="#" class="sidebar-link" id="logout-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-in-left"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
    
     <!-- Logout Confirmation Modal -->
     <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true" >
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                        </div>
                        <div class="modal-body">
                            <p class="pt-4 pb-4">Are you sure you want to log out?</p>
                            <!-- Align buttons to the right -->
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirm-logout-btn">Logout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <form id="logout-form" action="#" method="POST" style="display: none;">
                @csrf
            </form>