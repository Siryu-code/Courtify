@extends('layouts.admin')

@section('title', 'Bookings - Courtify Arena')

@section('content')
<div class="bookings-wrapper">
    {{-- Header --}}
    <h2 class="bookings-header">Courtify Arena</h2>

    {{-- Date Navigator --}}
    <div class="date-navigator">
        <a href="{{ route('admin.bookings', ['date' => $date->copy()->subDay()->toDateString()]) }}" 
           class="date-nav-btn">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <span class="date-nav-text">{{ $date->translatedFormat('F d, Y') }}</span>
        <a href="{{ route('admin.bookings', ['date' => $date->copy()->addDay()->toDateString()]) }}" 
           class="date-nav-btn">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    </div>

    {{-- Timeline Table --}}
    <div class="timeline-card">
        <div class="timeline-table-wrapper">
            <table class="timeline-table">
                <thead>
                    <tr>
                        <th class="time-header-cell">Time</th>
                        @php
                            $venues = \App\Models\Venue::orderBy('name')->get();
                        @endphp
                        @foreach($venues as $venue)
                            <th class="court-header-cell">
                                <div class="court-header-name">{{ $venue->name }}</div>
                                <span class="badge court-type-badge">{{ ucfirst($venue->type) }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $timeSlots = [];
                        for ($h = 8; $h <= 23; $h++) {
                            $timeSlots[] = sprintf('%02d:00', $h);
                        }
                        
                        // Map bookings ke slot per venue
                        $bookingMap = [];
                        foreach ($bookings as $booking) {
                            $venueId = $booking->venue_id;
                            $startHour = (int) \Carbon\Carbon::parse($booking->start_time)->format('H');
                            $endHour = (int) \Carbon\Carbon::parse($booking->end_time)->format('H');
                            $duration = $endHour - $startHour;
                            
                            if (!isset($bookingMap[$venueId])) {
                                $bookingMap[$venueId] = [];
                            }
                            $bookingMap[$venueId][$startHour] = [
                                'booking' => $booking,
                                'duration' => $duration,
                            ];
                        }
                    @endphp

                    @foreach($timeSlots as $slotIndex => $timeLabel)
                        @php $hour = (int) substr($timeLabel, 0, 2); @endphp
                        <tr class="timeline-row">
                            <td class="time-cell">
                                <span class="time-text">{{ \Carbon\Carbon::createFromFormat('H:i', $timeLabel)->format('h:i A') }}</span>
                            </td>
                            @foreach($venues as $venue)
                                @php
                                    $venueId = $venue->id;
                                    $isMaintenance = $venue->status === 'maintenance';
                                    $hasBooking = isset($bookingMap[$venueId][$hour]);
                                    $bookingData = $hasBooking ? $bookingMap[$venueId][$hour] : null;
                                    
                                    // Cek apakah slot ini bagian dari rowspan booking di atasnya
                                    $isCovered = false;
                                    if (!$hasBooking && !$isMaintenance) {
                                        for ($checkHour = 8; $checkHour < $hour; $checkHour++) {
                                            if (isset($bookingMap[$venueId][$checkHour])) {
                                                $checkData = $bookingMap[$venueId][$checkHour];
                                                if ($hour < $checkHour + $checkData['duration']) {
                                                    $isCovered = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                @endphp

                                @if($slotIndex === 0 && $isMaintenance)
                                    {{-- Full Maintenance Column --}}
                                    <td class="court-cell maintenance-cell" rowspan="{{ count($timeSlots) }}">
                                        <div class="maintenance-content">
                                            <div class="maintenance-court-name">{{ $venue->name }}</div>
                                            <span class="badge maintenance-badge">Perbaikan</span>
                                        </div>
                                    </td>
                                @elseif(!$isMaintenance)
                                    @if(!$isCovered)
                                        @if($hasBooking)
                                            <td class="court-cell booking-cell" 
                                                rowspan="{{ $bookingData['duration'] }}"
                                                style="border-left: 3px solid #0d6efd;">
                                                <div class="booking-block">
                                                    <div class="booking-title">
                                                        {{ $bookingData['booking']->customer_name ?? 'Booking' }}
                                                    </div>
                                                    <div class="booking-time">
                                                        {{ \Carbon\Carbon::parse($bookingData['booking']->start_time)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::parse($bookingData['booking']->end_time)->format('H:i') }}
                                                    </div>
                                                    <div class="booking-user">
                                                        <i class="fa-solid fa-user me-1"></i>
                                                        {{ $bookingData['booking']->user->name ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </td>
                                        @else
                                            <td class="court-cell empty-cell"></td>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bookings-wrapper {
        padding: 24px 28px;
    }
    .bookings-header {
        font-weight: 700;
        font-size: 20px;
        color: #111827;
        margin-bottom: 16px;
    }

    /* Date Navigator */
    .date-navigator {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        margin-bottom: 20px;
    }
    .date-nav-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #374151;
        text-decoration: none;
        transition: 0.15s;
        font-size: 16px;
    }
    .date-nav-btn:hover {
        background: #e5e7eb;
        color: #111827;
    }
    .date-nav-text {
        font-weight: 700;
        font-size: 24px;
        color: #111827;
        min-width: 220px;
        text-align: center;
    }

    /* Timeline Card */
    .timeline-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .timeline-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .timeline-table {
        width: 100%;
        min-width: 700px;
        border-collapse: collapse;
    }

    /* Header */
    .time-header-cell {
        width: 90px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        font-weight: 700;
        padding: 16px 14px;
        background: #fafbfc;
        border-bottom: 2px solid #e5e7eb;
        position: sticky;
        left: 0;
        z-index: 2;
    }
    .court-header-cell {
        padding: 14px 16px;
        border-bottom: 2px solid #e5e7eb;
        background: #fafbfc;
        text-align: center;
        min-width: 160px;
    }
    .court-header-name {
        font-weight: 700;
        font-size: 16px;
        color: #111827;
        margin-bottom: 4px;
    }
    .court-type-badge {
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 50px;
    }

    /* Body Rows */
    .timeline-row {
        border-bottom: 1px solid #f3f4f6;
    }
    .time-cell {
        padding: 0 14px;
        vertical-align: middle;
        background: #fff;
        position: sticky;
        left: 0;
        z-index: 1;
        border-right: 1px solid #f3f4f6;
        width: 90px;
        height: 64px;
    }
    .time-text {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
    }
    .court-cell {
        padding: 4px 8px;
        vertical-align: top;
        height: 64px;
        min-width: 160px;
    }
    .empty-cell {
        background: #fff;
    }

    /* Booking Cell */
    .booking-cell {
        background: #eff6ff;
        padding: 8px 12px;
    }
    .booking-block {
        padding: 2px 0;
    }
    .booking-title {
        font-weight: 700;
        font-size: 14px;
        color: #0d6efd;
        margin-bottom: 2px;
    }
    .booking-time {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }
    .booking-user {
        font-size: 12px;
        color: #374151;
    }

    /* Maintenance Cell */
    .maintenance-cell {
        background: #fee2e2;
        text-align: center;
        vertical-align: middle;
        width: 160px;
    }
    .maintenance-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .maintenance-court-name {
        font-weight: 700;
        font-size: 16px;
        color: #991b1b;
    }
    .maintenance-badge {
        background: rgba(255,255,255,0.7);
        color: #dc2626;
        font-weight: 600;
        font-size: 12px;
        padding: 4px 14px;
        border-radius: 50px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .bookings-wrapper {
            padding: 16px;
        }
        .date-nav-text {
            font-size: 18px;
            min-width: 160px;
        }
        .court-header-cell {
            min-width: 130px;
        }
        .court-cell {
            min-width: 130px;
        }
    }
</style>
@endpush