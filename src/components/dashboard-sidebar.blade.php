<aside class="dashboard-sidebar">
    <a class="dashboard-brand" href="{{ route('pages.dashboard') }}">DashOne</a>

    <nav class="dashboard-nav" aria-label="Menu dashboard">
        <a class="dashboard-nav-link {{ request()->routeIs('pages.dashboard') ? 'is-active' : '' }}" href="{{ route('pages.dashboard') }}" @if (request()->routeIs('pages.dashboard')) aria-current="page" @endif>
            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M2 2h6v6H2zm10 0h6v6h-6zM2 12h6v6H2zm10 0h6v6h-6z" /></svg>
            <span>Dashboard</span>
        </a>
        <a class="dashboard-nav-link {{ request()->routeIs('barangs.*') ? 'is-active' : '' }}" href="{{ route('barangs.index') }}" @if (request()->routeIs('barangs.*')) aria-current="page" @endif>
            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="m2 6 8-4 8 4v8l-8 4-8-4zM2 6l8 4 8-4M10 10v8" /></svg>
            <span>Barang</span>
        </a>
        <a class="dashboard-nav-link {{ request()->routeIs('categories.*') ? 'is-active' : '' }}" href="{{ route('categories.index') }}" @if (request()->routeIs('categories.*')) aria-current="page" @endif>
            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M2 3h7l9 7-8 8-8-9zM6 7h.01" /></svg>
            <span>Kategori</span>
        </a>
    </nav>

    <button class="dashboard-collapse" type="button" aria-label="Ciutkan sidebar">
        <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M3 4h14M3 10h8M3 16h14M14 8l3 2-3 2" /></svg>
    </button>
</aside>
