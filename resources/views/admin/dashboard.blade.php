@extends('layouts.admin')

@section('title', 'Dashboard - Courtify Arena')

@section('content')
<div class="dashboard-wrapper">
    {{-- Page Title --}}
    <h1 class="dashboard-title">Dashboard Overview</h1>

    {{-- Row 1: Stats + Chart --}}
    <div class="row g-4">
        {{-- Revenue Growth Chart (kiri besar) --}}
        <div class="col-lg-5">
            <div class="dashboard-card chart-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="card-section-title mb-0">Revenue Growth</h3>
                    <button class="btn btn-icon" title="Options">
                        <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                    </button>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Total Revenue + Today Revenue + Active Courts --}}
        <div class="col-lg-7">
            <div class="row g-3">
                {{-- Total Revenue --}}
                <div class="col-md-6">
                    <div class="dashboard-card stat-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="stat-label">Total Revenue</span>
                            <div class="stat-icon bg-primary-subtle">
                                <i class="fa-solid fa-wallet text-primary"></i>
                            </div>
                        </div>
                        <div class="stat-value">Rp{{ number_format($pendapatanMingguIni + $pendapatanHariIni, 0, ',', '.') }}</div>
                        <div class="stat-sub-label">Total Bookings</div>
                        <div class="stat-sub-value">{{ $totalBookingMingguIni + $totalBookingHariIni }}</div>
                    </div>
                </div>

                {{-- Today Revenue --}}
                <div class="col-md-6">
                    <div class="dashboard-card stat-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="stat-label">Today Revenue</span>
                            <div class="stat-icon bg-danger-subtle">
                                <i class="fa-solid fa-calendar text-danger"></i>
                            </div>
                        </div>
                        <div class="stat-value">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                        <div class="stat-sub-label">Today's Bookings</div>
                        <div class="stat-sub-value">{{ $totalBookingHariIni }}</div>
                    </div>
                </div>

                {{-- Active Courts --}}
                <div class="col-12">
                    <div class="dashboard-card stat-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="stat-label">Active Courts</span>
                            <div class="stat-icon bg-warning-subtle">
                                <i class="fa-solid fa-futbol text-warning"></i>
                            </div>
                        </div>
                        <div class="stat-value-large">{{ $venueAktif }}/{{ $totalVenue }}</div>
                        <div class="stat-sub-label">{{ $totalVenue - $venueAktif }} under maintenance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Recent Booking Requests --}}
    <div class="dashboard-card table-card mt-4">
        <div class="table-card-header">
            <h3 class="card-section-title mb-0">Recent Booking Requests</h3>
        </div>
        <div class="table-responsive">
            <table class="table dashboard-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Court</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                        @php
                            $now = \Carbon\Carbon::now();
                            $bookingStart = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->start_time);
                            $bookingEnd = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->end_time);
                            
                            if ($now->between($bookingStart, $bookingEnd)) {
                                $status = 'ONGOING';
                                $statusClass = 'bg-secondary-subtle text-dark';
                            } elseif ($bookingStart->isFuture() && $bookingStart->diffInDays($now) <= 1) {
                                $status = 'SOON';
                                $statusClass = 'bg-warning-subtle text-warning';
                            } else {
                                $status = 'PAST';
                                $statusClass = 'bg-success-subtle text-success';
                            }
                            
                            $avatarColors = ['#dbeafe', '#d1fae5', '#fef3c7', '#fce7f3', '#e0e7ff'];
                            $avatarColor = $avatarColors[$loop->index % count($avatarColors)];
                            $avatarTextColors = ['#1d4ed8', '#065f46', '#92400e', '#9d174d', '#3730a3'];
                            $avatarTextColor = $avatarTextColors[$loop->index % count($avatarTextColors)];
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar-mini" style="background: {{ $avatarColor }}; color: {{ $avatarTextColor }};">
                                        {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="user-name-text">{{ $booking->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>{{ $booking->venue->name ?? 'N/A' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d') }}, 
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $statusClass }} px-3 py-2">{{ $status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                   class="btn btn-sm btn-outline-secondary rounded-2" 
                                   title="Detail">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada booking terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-wrapper {
        padding: 24px 28px;
    }
    .dashboard-title {
        font-weight: 800;
        font-size: 28px;
        color: #111827;
        margin-bottom: 24px;
    }
    .dashboard-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .card-section-title {
        font-weight: 700;
        font-size: 18px;
        color: #111827;
    }
    .btn-icon {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .btn-icon:hover {
        background: #f3f4f6;
    }

    /* Chart */
    .chart-container {
        height: 280px;
        position: relative;
    }
    .chart-card {
        min-height: 100%;
    }

    /* Stat Cards */
    .stat-label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .bg-primary-subtle { background: #dbeafe; }
    .bg-danger-subtle { background: #fee2e2; }
    .bg-warning-subtle { background: #fef3c7; }
    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }
    .stat-sub-label {
        font-size: 12px;
        color: #6b7280;
    }
    .stat-sub-value {
        font-size: 22px;
        font-weight: 700;
        color: #374151;
    }
    .stat-value-large {
        font-size: 34px;
        font-weight: 800;
        color: #111827;
    }

    /* Table */
    .table-card {
        padding: 0;
        overflow: hidden;
    }
    .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f3f4f6;
    }
    .dashboard-table {
        margin: 0;
    }
    .dashboard-table thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        font-weight: 600;
        border-bottom: 1px solid #f3f4f6;
        padding: 14px 24px;
        background: #fafbfc;
    }
    .dashboard-table tbody td {
        padding: 16px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f9fafb;
        font-size: 14px;
        color: #374151;
    }
    .dashboard-table tbody tr:hover {
        background: #fafeff;
    }
    .user-avatar-mini {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    .user-name-text {
        font-weight: 500;
    }
    .bg-secondary-subtle { background: #e5e7eb; }
    .bg-warning-subtle { background: #fef3c7; color: #92400e !important; }
    .bg-success-subtle { background: #d1fae5; color: #065f46 !important; }

    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 16px;
        }
        .dashboard-title {
            font-size: 22px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Revenue Growth Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    const weeklyData = @json($weeklyBookings);
    const labels = weeklyData.map(item => item.day);
    const data = weeklyData.map(item => item.total);
    
    // Cari index nilai tertinggi
    const maxIndex = data.indexOf(Math.max(...data));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bookings',
                data: data,
                backgroundColor: data.map((val, i) => 
                    i === maxIndex ? '#0d6efd' : '#bfdbfe'
                ),
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `${ctx.parsed.y} bookings`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: { 
                        font: { size: 11 },
                        color: '#6b7280',
                        stepSize: 1
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        font: { size: 11 },
                        color: '#6b7280'
                    }
                }
            }
        }
    });
</script>
@endpush