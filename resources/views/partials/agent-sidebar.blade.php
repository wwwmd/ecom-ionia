<aside class="z-sidebar" id="zSidebar">
    <div class="z-sb-brand">
        <a href="{{ url('/agent/dashboard') }}">
            <span class="z-sb-brand-text">store<span>Agent</span></span>
        </a>
        <button class="z-sb-close d-lg-none" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
    </div>

    <nav class="z-sb-nav">
        <ul>
            <li><a href="{{ url('/agent/dashboard') }}" class="z-sb-link {{ request()->is('agent/dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
            <li><a href="{{ url('/agent/leads') }}" class="z-sb-link {{ request()->is('agent/leads*') ? 'active' : '' }}"><i class="bi bi-funnel"></i><span>My Leads</span></a></li>
            <li><a href="{{ url('/agent/followups') }}" class="z-sb-link {{ request()->is('agent/followups*') ? 'active' : '' }}"><i class="bi bi-calendar-check"></i><span>Follow-ups</span></a></li>
            <li><a href="{{ url('/agent/customers') }}" class="z-sb-link {{ request()->is('agent/customers*') ? 'active' : '' }}"><i class="bi bi-people"></i><span>My Customers</span></a></li>
            <li><a href="{{ url('/agent/orders') }}" class="z-sb-link {{ request()->is('agent/orders*') ? 'active' : '' }}"><i class="bi bi-bag-check"></i><span>Orders</span></a></li>
            <li><a href="{{ url('/agent/commission') }}" class="z-sb-link {{ request()->is('agent/commission*') ? 'active' : '' }}"><i class="bi bi-cash-coin"></i><span>Commission</span></a></li>
            <li><a href="{{ url('/agent/profile') }}" class="z-sb-link {{ request()->is('agent/profile*') ? 'active' : '' }}"><i class="bi bi-person"></i><span>My Profile</span></a></li>
        </ul>
    </nav>

    <div class="z-sb-footer">
        <div class="z-sb-user">
            <div class="z-sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}</div>
            <div>
                <strong>{{ auth()->user()->name ?? 'Agent' }}</strong>
                <small>Sales Agent</small>
            </div>
        </div>
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button class="z-sb-logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>
</aside>

<div class="z-sb-backdrop" id="sbBackdrop"></div>
