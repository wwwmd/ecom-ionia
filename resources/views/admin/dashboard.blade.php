@extends('layouts.admin')
@section('title', 'Dashboard')

@section('breadcrumb')
    <i class="bi bi-chevron-right"></i><span>Dashboard</span>
@endsection

@section('content')

<div class="z-page-head">
    <div>
        <h1>Welcome back, {{ auth()->user()->name }}</h1>
        <p>Here's what's happening with your store today.</p>
    </div>
    {{-- <div class="z-page-head-actions">
        <a href="{{ route('admin.products.create') }}" class="z-btn-primary">
            <i class="bi bi-plus-lg"></i> Add Product
        </a>
    </div> --}}
</div>

<!-- KPI Cards -->
{{-- <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="z-kpi z-kpi-blue">
            <div class="z-kpi-top">
                <div class="z-kpi-icon"><i class="bi bi-currency-rupee"></i></div>
            </div>
            <div class="z-kpi-value">₹{{ number_format($stats['revenue'], 0) }}</div>
            <div class="z-kpi-label">Total Revenue</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-kpi z-kpi-teal">
            <div class="z-kpi-top">
                <div class="z-kpi-icon"><i class="bi bi-bag-check"></i></div>
            </div>
            <div class="z-kpi-value">{{ number_format($stats['orders']) }}</div>
            <div class="z-kpi-label">Total Orders</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-kpi z-kpi-purple">
            <div class="z-kpi-top">
                <div class="z-kpi-icon"><i class="bi bi-people"></i></div>
            </div>
            <div class="z-kpi-value">{{ number_format($stats['customers']) }}</div>
            <div class="z-kpi-label">Customers</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="z-kpi z-kpi-orange">
            <div class="z-kpi-top">
                <div class="z-kpi-icon"><i class="bi bi-funnel"></i></div>
            </div>
            <div class="z-kpi-value">{{ number_format($stats['leads']) }}</div>
            <div class="z-kpi-label">Active Leads</div>
        </div>
    </div>
</div> --}}

<!-- Mini Stats -->
{{-- <div class="row g-3 mb-4">
    @php
        $mini = [
            ['Today\'s Sales', '₹'.number_format($stats['today'],0), 'bi-cart-check', 'blue'],
            ['Pending Orders', $stats['pending'], 'bi-hourglass-split', 'orange'],
            ['Completed', $stats['completed'], 'bi-check2-circle', 'green'],
            ['Cancelled', $stats['cancelled'], 'bi-x-circle', 'red'],
            ['Total Agents', $stats['agents'], 'bi-person-badge', 'purple'],
            ['Today Follow-ups', $stats['followups'], 'bi-calendar-check', 'teal'],
            ['Products', number_format($stats['products']), 'bi-box-seam', 'blue'],
            ['Low Stock', $stats['low_stock'], 'bi-exclamation-triangle', 'red'],
        ];
    @endphp
    @foreach($mini as $m)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="z-mini-stat">
            <div class="z-ms-icon z-ms-{{ $m[3] }}"><i class="bi {{ $m[2] }}"></i></div>
            <div>
                <span>{{ $m[0] }}</span>
                <strong>{{ $m[1] }}</strong>
            </div>
        </div>
    </div>
    @endforeach
</div> --}}

<!-- Charts -->
{{-- <div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="z-card">
            <div class="z-card-head">
                <div>
                    <h5>Revenue Overview</h5>
                    <p>Monthly performance</p>
                </div>
            </div>
            <canvas id="revenueChart" height="90"></canvas>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="z-card h-100">
            <div class="z-card-head"><h5>Order Status</h5></div>
            <canvas id="orderStatusChart" height="220"></canvas>
        </div>
    </div>
</div> --}}

{{-- <div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="z-card">
            <div class="z-card-head"><h5>Customer Growth</h5></div>
            <canvas id="customerChart" height="120"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="z-card">
            <div class="z-card-head"><h5>Lead Conversion</h5></div>
            <canvas id="leadChart" height="120"></canvas>
        </div>
    </div>
</div> --}}

<!-- Recent Tables -->
{{-- <div class="row g-3">
    <div class="col-lg-7">
        <div class="z-card">
            <div class="z-card-head">
                <h5>Recent Orders</h5>
                <a href="{{ route('admin.orders') }}" class="z-link">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table z-table mb-0">
                    <thead>
                        <tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    @forelse($recentOrders as $o)
                    <tr>
                        <td><strong>#{{ $o->order_number }}</strong></td>
                        <td>{{ $o->customer_name }}</td>
                        <td><strong>₹{{ number_format($o->grand_total) }}</strong></td>
                        <td><span class="z-status z-status-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No orders yet</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="z-card">
            <div class="z-card-head">
                <h5>Recent Leads</h5>
                <a href="{{ route('admin.leads') }}" class="z-link">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="z-lead-list">
                @forelse($recentLeads as $l)
                <div class="z-lead-item">
                    <div class="z-li-avatar">{{ strtoupper(substr($l->name,0,1)) }}</div>
                    <div class="z-li-info">
                        <strong>{{ $l->name }}</strong>
                        <small><i class="bi bi-telephone"></i> {{ $l->phone }}</small>
                    </div>
                    <div class="z-li-tags">
                        <span class="z-priority z-priority-{{ $l->priority }}">{{ $l->priority }}</span>
                        <span class="z-status z-status-{{ $l->status }}">{{ $l->status }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">No leads yet</div>
                @endforelse
            </div>
        </div>
    </div>
</div> --}}

@endsection

@push('scripts')
<script>
$(function () {
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Revenue',
                data: [45,58,52,68,72,85,92,88,105,118,128,142].map(v => v*1000),
                borderColor: '#2E6DB4',
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                    g.addColorStop(0, 'rgba(46,109,180,.25)');
                    g.addColorStop(1, 'rgba(38,166,154,0)');
                    return g;
                },
                fill: true, tension: .4, borderWidth: 3,
                pointBackgroundColor: '#fff', pointBorderColor: '#2E6DB4',
                pointBorderWidth: 3, pointRadius: 5
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#EEF2F7' }, ticks: { callback: v => '₹'+(v/1000)+'k' } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('orderStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Delivered','Processing','Shipped','Pending','Cancelled'],
            datasets: [{
                data: [{{ $stats['completed'] }}, 20, 15, {{ $stats['pending'] }}, {{ $stats['cancelled'] }}],
                backgroundColor: ['#16A34A','#2E6DB4','#6366F1','#F59E0B','#DC2626'],
                borderWidth: 0, hoverOffset: 8
            }]
        },
        options: { cutout: '72%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } }
    });

    new Chart(document.getElementById('customerChart'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'New Customers',
                data: [22,35,28,42,55,68],
                backgroundColor: '#26A69A',
                borderRadius: 8, barThickness: 28
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#EEF2F7' } }, x: { grid: { display: false } } }
        }
    });

    new Chart(document.getElementById('leadChart'), {
        type: 'bar',
        data: {
            labels: ['New','Contacted','Interested','Negotiation','Converted','Lost'],
            datasets: [{
                data: [24,18,12,8,42,11],
                backgroundColor: ['#2E6DB4','#3B82F6','#8B5CF6','#F59E0B','#16A34A','#DC2626'],
                borderRadius: 6, barThickness: 24
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, grid: { color: '#EEF2F7' } }, y: { grid: { display: false } } }
        }
    });
});
</script>
@endpush
