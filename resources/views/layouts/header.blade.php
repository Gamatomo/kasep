<header class="modern-header">
    <div class="modern-header-brand">
        <button class="modern-sidebar-toggle" data-action="toggle-sidebar" aria-label="Toggle sidebar">
            <i class="fa fa-bars"></i>
        </button>
        <a href="{{ route('dashboard') }}" class="modern-brand-link">
            <img src="{{ url($setting->path_logo) }}" alt="Logo" class="modern-brand-logo">
            <span>{{ $setting->nama_perusahaan }}</span>
        </a>
    </div>

    <div class="modern-header-actions">
        <div class="dropdown modern-user-dropdown">
            <button class="btn modern-user-button dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="modern-user-avatar" alt="User Image">
                <span>{{ auth()->user()->name }}</span>
                <i class="fa fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right modern-user-menu">
                <li class="modern-user-card">
                    <img src="{{ url(auth()->user()->foto ?? '') }}" class="modern-user-card-avatar" alt="User Image">
                    <div>
                        <strong>{{ auth()->user()->name }}</strong>
                        <p>{{ auth()->user()->email }}</p>
                    </div>
                </li>
                <li class="divider"></li>
                <li><a href="{{ route('user.profil') }}"><i class="fa fa-user-circle"></i> Profil</a></li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Keluar</a></li>
            </ul>
        </div>
    </div>
</header>

<form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
    @csrf
</form>