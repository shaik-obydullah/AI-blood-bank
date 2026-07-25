<header class="header">
    <div class="header-left">
        <h2>@yield('page-title', 'Dashboard Overview')</h2>
        <p>@yield('page-subtitle', 'Welcome to BloodBank Management System')</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ url('/admin-profile') }}" class="logout-btn" style="color: white;background: #5858d9;">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
        <a href="{{ url('/admin-logout') }}" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</header>