@php
    $menu = [
        ['label'=>'Dashboard','icon'=>'bi-speedometer2','url'=>url('/admin/dashboard'),'match'=>'admin/dashboard'],
        ['heading'=>'Commerce'],
       // ['label'=>'Orders','icon'=>'bi-bag-check','url'=>url('/admin/orders'),'match'=>'admin/orders'],
        ['label'=>'Products','icon'=>'bi-box-seam','url'=>url('/admin/products'),'match'=>'admin/products'],
         ['label'=>'Customers','icon'=>'bi-people','url'=>url('/admin/customers'),'match'=>'admin/customers'],
        // ['heading'=>'CRM'],
        // ['label'=>'Leads','icon'=>'bi-funnel','url'=>url('/admin/crm/leads'),'match'=>'admin/crm/leads'],
        // ['label'=>'Follow-ups','icon'=>'bi-calendar-check','url'=>url('/admin/crm/followups'),'match'=>'admin/crm/followups'],
        // ['heading'=>'People'],
        // ['label'=>'Agents','icon'=>'bi-person-badge','url'=>url('/admin/customers'),'match'=>'admin/agents'],
        // ['heading'=>'System'],
        // ['label'=>'Settings','icon'=>'bi-gear','url'=>url('/admin/settings'),'match'=>'admin/settings'],
        // ['label'=>'Activity Logs','icon'=>'bi-clock-history','url'=>url('/admin/logs'),'match'=>'admin/logs'],
    ];
@endphp

<aside class="z-sidebar" id="zSidebar">
     <div class="z-sb-brand">
        <a href="{{ url('/admin/dashboard') }}">
            <span class="z-sb-brand-text">store<span>Admin</span></span>
        </a>
        <button class="z-sb-close d-lg-none" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
    </div>

    <nav class="z-sb-nav">
        <ul>
            @foreach($menu as $item)
                @if(isset($item['heading']))
                    <li class="z-sb-heading">{{ $item['heading'] }}</li>
                @else
                    @php $isActive = request()->is($item['match'].'*'); @endphp
                    <li>
                        <a href="{{ $item['url'] }}" class="z-sb-link {{ $isActive ? 'active' : '' }}">
                            <i class="bi {{ $item['icon'] }}"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    <div class="z-sb-footer">
        <div class="z-sb-user">
            <div class="z-sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>

            </div>
        </div>
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button class="z-sb-logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>
</aside>

<div class="z-sb-backdrop" id="sbBackdrop"></div>
