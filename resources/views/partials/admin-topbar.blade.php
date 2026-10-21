<header class="z-topbar-admin">
    <div class="z-tb-left">
        <button class="z-tb-toggle d-none" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="z-tb-search d-none d-md-block">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search...">
        </div>
    </div>

    <div class="z-tb-right">
        <a href="{{ url('/') }}" target="_blank" class="z-tb-btn" title="View Store">
            <i class="bi bi-shop"></i>
        </a>

        <div class="dropdown">
            <button class="z-tb-btn" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <span class="z-tb-dot"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end z-notif-dropdown">
                <div class="z-notif-head">
                    <strong>Notifications</strong>
                    <a href="#" class="z-link">Mark all read</a>
                </div>
                <div class="z-notif-list">
                    @for($i=0;$i<4;$i++)
                    <a href="#" class="z-notif-item">
                        <div class="z-ni-icon bg-{{ ['primary','success','warning','danger'][$i] }}-subtle">
                            <i class="bi bi-{{ ['bag-check','person-plus','exclamation-triangle','cash-coin'][$i] }}"></i>
                        </div>
                        <div>
                            <strong>{{ ['New order received','New customer','Low stock alert','Payment received'][$i] }}</strong>
                            <small>{{ ['#ORD-10021','Ravi Sharma','iPhone 15 Pro','₹129,900'][$i] }}</small>
                        </div>
                    </a>
                    @endfor
                </div>
            </div>
        </div>

        <div class="dropdown">
            <button class="z-tb-profile" data-bs-toggle="dropdown">
                <div class="z-tbp-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <div class="z-tbp-text d-none d-md-block">
                    <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>

                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end z-dropdown">
                <li>
                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
