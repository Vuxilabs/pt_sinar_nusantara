<header class="dashboard-header">
    <a class="dashboard-cloud-brand" href="{{ route('pages.dashboard') }}" aria-label="Tencent Cloud">
        <svg viewBox="0 0 40 32" aria-hidden="true">
            <path d="M12.2 26.5h17.4a7.1 7.1 0 0 0 .8-14.2A10.7 10.7 0 0 0 10 10a8.3 8.3 0 0 0 2.2 16.5Z" />
        </svg>
        <span>PT Sinar Nusantara</span>
    </a>

    <nav class="dashboard-top-nav" aria-label="Navigasi utama">
        <a href="{{ route('pages.dashboard') }}">Overview</a>
        <a class="dashboard-add-link" href="#add" aria-label="Tambah">+</a>
    </nav>

    <nav class="dashboard-utility-nav" aria-label="Menu akun">
        <a class="dashboard-icon-link dashboard-notifications" href="#notifications" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v13H3zM3 7l9 7 9-7" /></svg>
            <span>0</span>
        </a>
        <details class="dashboard-profile-menu">
            <summary class="dashboard-avatar" aria-label="Buka menu profil">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>
            </summary>
            <div class="dashboard-profile-dropdown">
                <span class="dashboard-profile-name">{{ auth()->user()->name }}</span>
                <a href="{{ route('pages.dashboard.profile') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Log out</button>
                </form>
            </div>
        </details>
        <span class="dashboard-registration">Anda Sudah Login.</span>
    </nav>
</header>
