<aside class="dashboard-sidebar">
    <a class="dashboard-brand" href="{{ route('pages.dashboard') }}">DashOne</a>

    <nav class="dashboard-nav" aria-label="Menu dashboard">
        <a class="dashboard-nav-link is-active" href="{{ route('pages.dashboard') }}" aria-current="page">
            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M2 2h6v6H2zm10 0h6v6h-6zM2 12h6v6H2zm10 0h6v6h-6z" /></svg>
            <span>Service Dashboard</span>
        </a>
        <a class="dashboard-nav-link" href="#analytics">
            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M2 2h16v16H2zM5 14l3-4 2 2 4-5M5 15h10" /></svg>
            <span>Analytics</span>
        </a>
    </nav>

    <button class="dashboard-collapse" type="button" aria-label="Ciutkan sidebar">
        <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M3 4h14M3 10h8M3 16h14M14 8l3 2-3 2" /></svg>
    </button>
</aside>
