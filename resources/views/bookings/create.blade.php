@extends('layouts.app')

@section('title', 'Booking - ' . $venue->name)

@section('topbar')
    <div class="back-header">
        <button type="button" onclick="history.back()" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <span class="back-header-brand text-primary">Courtify Arena</span>
        <div class="back-btn invisible">
            <i class="fa-solid fa-arrow-left"></i>
        </div>
    </div>
@endsection

@section('content')
{{-- ======================== SINGLE DAY MODE (Default) ======================== --}}
<div id="singleDayMode">
    {{-- CTA Multi-Day Banner --}}
    <div class="booking-cta-banner">
        <p class="cta-banner-label">BUTUH SEWA UNTUK EVENT BERHARI-HARI?</p>
        <button type="button" class="btn btn-primary cta-banner-btn" id="switchToMultiDay">
            BERALIH KE MULTI-DAY <i class="fa-solid fa-angles-right ms-1"></i>
        </button>
    </div>

    {{-- Select Date Section --}}
    <div class="booking-section">
        <h2 class="booking-section-title">Select Date</h2>
        
        @php
            $dates = [];
            $today = \Carbon\Carbon::today();
            for ($i = 0; $i < 14; $i++) {
                $dates[] = $today->copy()->addDays($i);
            }
        @endphp

        <div class="date-scroll-container" id="dateScroll">
            @foreach($dates as $index => $date)
                <div class="date-card {{ $index === 0 ? 'active' : '' }}" 
                     data-date="{{ $date->format('Y-m-d') }}"
                     onclick="selectDate(this, '{{ $date->format('Y-m-d') }}')">
                    <span class="date-card-month">{{ $date->format('M') }}</span>
                    <span class="date-card-day">{{ $date->format('d') }}</span>
                    <span class="date-card-weekday">{{ $date->format('D') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Start Time Section --}}
    <div class="booking-section">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="booking-section-title mb-0">Start Time</h2>
            <span class="badge rounded-pill availability-badge">
                <span class="dot-green"></span> AVAILABLE
            </span>
        </div>
        <p class="duration-label">ISI DURASI (1 - 23 JAM)</p>
        
        {{-- Durasi Input --}}
        <div class="duration-input-wrapper">
            <i class="fa-regular fa-clock duration-icon"></i>
            <input type="number" 
                   id="durationInput" 
                   class="form-control duration-input" 
                   value="2" 
                   min="1" 
                   max="23"
                   onchange="generateTimeSlots()">
            <span class="duration-unit">Jam</span>
        </div>

        {{-- Slot Waktu --}}
        <div class="time-slots-container" id="timeSlotsContainer">
            {{-- Di-generate oleh JS --}}
        </div>
    </div>

    {{-- Sticky Bottom Bar --}}
    <div class="sticky-bottom-bar">
        <div class="bottom-bar-price">
            <span class="bottom-bar-label">Total Price</span>
            <span class="bottom-bar-amount" id="totalPriceDisplay">
                Rp{{ number_format($venue->price_per_hour * 2, 0, ',', '.') }}
            </span>
        </div>
        <button type="button" class="btn btn-primary bottom-bar-confirm" id="confirmSingleDay" disabled>
            Confirm <i class="fa-solid fa-circle-check ms-1"></i>
        </button>
    </div>
</div>

{{-- ======================== MULTI DAY MODE (Hidden Default) ======================== --}}
<div id="multiDayMode" style="display: none;">
    {{-- Info Banner --}}
    <div class="multiday-banner">
        <div class="d-flex gap-3 align-items-start">
            <div class="multiday-banner-icon">
                <i class="fa-solid fa-calendar"></i>
            </div>
            <div>
                <h3 class="multiday-banner-title">Multi Day</h3>
                <p class="multiday-banner-desc">Reserve for tournaments or events spanning days.</p>
            </div>
        </div>
        <button type="button" class="btn-close" id="switchToSingleDay" aria-label="Close"></button>
    </div>

    {{-- Set Duration Form --}}
    <div class="multiday-form-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="multiday-form-title">
                <i class="fa-regular fa-clock me-2"></i> Set Duration
            </h3>
        </div>

        {{-- Pilih Durasi --}}
        <div class="mb-3">
            <select class="form-select multiday-select" id="multidayDuration">
                <option value="">Pilih Durasi</option>
                @for($i = 1; $i <= 23; $i++)
                    <option value="{{ $i }}">{{ $i }} Jam</option>
                @endfor
            </select>
        </div>

        {{-- MULAI --}}
        <div class="mb-3">
            <label class="multiday-label">MULAI</label>
            <div class="row g-2">
                <div class="col-7">
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-calendar input-icon"></i>
                        <input type="text" 
                               class="form-control multiday-input date-picker-trigger" 
                               id="startDate" 
                               placeholder="Pilih Tanggal" 
                               readonly
                               onclick="openCalendar('start')">
                    </div>
                </div>
                <div class="col-5">
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-clock input-icon"></i>
                        <select class="form-control multiday-input" id="startTime">
                            @for($h = 6; $h <= 22; $h++)
                                <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- BERAKHIR --}}
        <div class="mb-3">
            <label class="multiday-label">BERAKHIR</label>
            <div class="row g-2">
                <div class="col-7">
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-calendar input-icon"></i>
                        <input type="text" 
                               class="form-control multiday-input date-picker-trigger" 
                               id="endDate" 
                               placeholder="Pilih Tanggal" 
                               readonly
                               onclick="openCalendar('end')">
                    </div>
                </div>
                <div class="col-5">
                    <div class="input-icon-wrapper">
                        <i class="fa-regular fa-clock input-icon"></i>
                        <select class="form-control multiday-input" id="endTime">
                            @for($h = 6; $h <= 23; $h++)
                                <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00">
                                    {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pesan untuk Admin --}}
        <div class="mb-0">
            <textarea class="form-control multiday-textarea" 
                      id="customerNote" 
                      rows="3" 
                      placeholder="Tambahkan permintaan khusus..."></textarea>
        </div>
    </div>

    {{-- Sticky Bottom Bar --}}
    <div class="sticky-bottom-bar">
        <div class="bottom-bar-price">
            <span class="bottom-bar-label">Total Price</span>
            <span class="bottom-bar-amount" id="totalPriceMultiDay">Rp0</span>
        </div>
        <button type="button" class="btn btn-primary bottom-bar-confirm" id="confirmMultiDay" disabled>
            Confirm <i class="fa-solid fa-circle-check ms-1"></i>
        </button>
    </div>
</div>

{{-- ======================== CALENDAR MODAL ======================== --}}
<div class="calendar-overlay" id="calendarOverlay" style="display: none;">
    <div class="calendar-modal">
        <div class="calendar-header">
            <button type="button" class="calendar-nav" onclick="navigateMonth(-1)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <span class="calendar-month-title" id="calendarMonthTitle">June 2026</span>
            <button type="button" class="calendar-nav" onclick="navigateMonth(1)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div class="calendar-grid-header">
            <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span>
            <span>Thu</span><span>Fri</span><span>Sat</span>
        </div>
        <div class="calendar-grid" id="calendarGrid">
            {{-- Di-generate JS --}}
        </div>

        <div class="calendar-legend">
            <span><span class="legend-dot bg-success"></span> Tersedia</span>
            <span><span class="legend-dot bg-warning"></span> Terpesan Sebagian</span>
            <span><span class="legend-dot bg-danger"></span> Penuh</span>
        </div>

        <div class="calendar-footer">
            <button type="button" class="btn btn-secondary flex-fill" onclick="closeCalendar()">Cancel</button>
            <button type="button" class="btn btn-primary flex-fill" onclick="applyCalendarDate()">Apply Dates</button>
        </div>
    </div>
</div>

{{-- ======================== ALERT TOAST ======================== --}}
<div class="toast-alert" id="conflictToast" style="display: none;">
    <i class="fa-solid fa-triangle-exclamation me-2"></i>
    <span id="conflictMessage">Maaf, slot waktu ini sudah dipesan.</span>
</div>

{{-- Spacer --}}
<div class="booking-spacer"></div>
@endsection

{{-- ======================== STYLES ======================== --}}
@push('styles')
<style>
    /* --- BACK HEADER --- */
    .back-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }
    .back-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: #212529;
        cursor: pointer;
        width: 32px;
        text-align: left;
        padding: 0;
    }
    .back-header-brand {
        font-weight: 700;
        font-size: 17px;
    }

    /* --- CTA BANNER --- */
    .booking-cta-banner {
        padding: 20px 16px;
        text-align: center;
    }
    .cta-banner-label {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }
    .cta-banner-btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.3px;
    }

    /* --- SECTION --- */
    .booking-section {
        padding: 16px 20px;
    }
    .booking-section-title {
        font-weight: 700;
        font-size: 19px;
        color: #000;
    }

    /* --- DATE CARDS --- */
    .date-scroll-container {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
        -webkit-overflow-scrolling: touch;
    }
    .date-scroll-container::-webkit-scrollbar { display: none; }
    .date-card {
        flex: 0 0 64px;
        height: 72px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.15s;
        background: #fff;
    }
    .date-card.active {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }
    .date-card.active span { color: #fff; }
    .date-card-month { font-size: 10px; text-transform: uppercase; color: #6b7280; font-weight: 600; }
    .date-card-day { font-size: 22px; font-weight: 700; color: #111827; }
    .date-card-weekday { font-size: 11px; color: #6b7280; }

    /* --- AVAILABILITY BADGE --- */
    .availability-badge {
        background: #f3f4f6;
        color: #374151;
        font-size: 11px;
        font-weight: 600;
        padding: 5px 12px;
    }
    .dot-green {
        display: inline-block;
        width: 7px; height: 7px;
        background: #22c55e;
        border-radius: 50%;
        margin-right: 5px;
    }
    .duration-label {
        font-size: 11px;
        color: #6b7280;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    /* --- DURATION INPUT --- */
    .duration-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }
    .duration-icon {
        position: absolute;
        left: 14px;
        color: #0d6efd;
        font-size: 16px;
        z-index: 5;
    }
    .duration-input {
        padding: 10px 14px 10px 40px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 16px;
        font-weight: 600;
        width: 100px;
        text-align: center;
    }
    .duration-unit {
        margin-left: 10px;
        font-weight: 600;
        color: #374151;
    }

    /* --- TIME SLOTS --- */
    .time-slots-container { display: flex; flex-direction: column; gap: 10px; }
    .time-slot-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.15s;
    }
    .time-slot-card.selected {
        border: 2px solid #0d6efd;
        background: #eff6ff;
    }
    .time-slot-time { font-weight: 700; font-size: 16px; color: #111827; }
    .time-slot-radio {
        width: 22px; height: 22px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .time-slot-card.selected .time-slot-radio {
        background: #0d6efd;
        border-color: #0d6efd;
    }
    .time-slot-card.selected .time-slot-radio::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: #fff;
        font-size: 11px;
    }

    /* --- STICKY BOTTOM BAR --- */
    .sticky-bottom-bar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #fff;
        border-top: 1px solid #e5e7eb;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 100;
    }
    .bottom-bar-label { font-size: 12px; color: #6b7280; display: block; }
    .bottom-bar-amount { font-size: 20px; font-weight: 700; color: #000; }
    .bottom-bar-confirm {
        padding: 12px 28px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 15px;
    }

    /* --- MULTI DAY BANNER --- */
    .multiday-banner {
        background: #dbeafe;
        border-left: 4px solid #0d6efd;
        border-radius: 10px;
        padding: 16px;
        margin: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .multiday-banner-icon {
        width: 40px; height: 40px;
        background: #0d6efd;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }
    .multiday-banner-title { font-weight: 700; font-size: 17px; color: #000; margin-bottom: 2px; }
    .multiday-banner-desc { font-size: 13px; color: #6b7280; margin: 0; }

    /* --- MULTI DAY FORM --- */
    .multiday-form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
        margin: 0 20px;
    }
    .multiday-form-title { font-weight: 700; font-size: 17px; color: #000; margin: 0; }
    .multiday-label { font-size: 11px; color: #6b7280; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 4px; }
    .multiday-input, .multiday-select { padding: 10px 14px 10px 38px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px; }
    .multiday-textarea { border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px; resize: none; }
    .input-icon-wrapper { position: relative; }
    .input-icon-wrapper .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 15px; z-index: 5; pointer-events: none; }

    /* --- CALENDAR MODAL --- */
    .calendar-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 200;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .calendar-modal {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        width: 100%;
        max-width: 380px;
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .calendar-nav { background: none; border: none; font-size: 18px; color: #374151; cursor: pointer; }
    .calendar-month-title { font-weight: 700; font-size: 17px; }
    .calendar-grid-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 8px;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }
    .calendar-day {
        aspect-ratio: 1;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.1s;
        color: #374151;
    }
    .calendar-day.available { background: #bbf7d0; }
    .calendar-day.partial { background: #fed7aa; }
    .calendar-day.full { background: #fecaca; color: #991b1b; }
    .calendar-day.today { background: #0d6efd; color: #fff; }
    .calendar-day.past { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; }
    .calendar-day.selected-date { border: 2px solid #0d6efd; font-weight: 700; }
    .calendar-legend {
        display: flex;
        gap: 14px;
        font-size: 11px;
        color: #6b7280;
        margin-top: 12px;
    }
    .legend-dot {
        display: inline-block;
        width: 8px; height: 8px;
        border-radius: 50%;
        margin-right: 4px;
    }
    .calendar-footer {
        display: flex;
        gap: 10px;
        margin-top: 16px;
    }

    /* --- TOAST --- */
    .toast-alert {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #dc2626;
        color: #fff;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        z-index: 300;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    /* --- SPACER --- */
    .booking-spacer { height: 90px; }
</style>
@endpush

{{-- ======================== SCRIPTS ======================== --}}
@push('scripts')
<script>
    // ===== STATE =====
    let selectedDate = '{{ \Carbon\Carbon::today()->format('Y-m-d') }}';
    let selectedSlot = null;
    let calendarTarget = null;
    let calendarDate = new Date();
    let tempStartDate = null;
    let tempEndDate = null;
    const venuePricePerHour = {{ $venue->price_per_hour }};

    // ===== SINGLE DAY: Date Selection =====
    function selectDate(el, date) {
        document.querySelectorAll('.date-card').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        selectedDate = date;
        generateTimeSlots();
    }

    // ===== SINGLE DAY: Generate Time Slots =====
    function generateTimeSlots() {
        const duration = parseInt(document.getElementById('durationInput').value) || 2;
        const container = document.getElementById('timeSlotsContainer');
        const opening = 6;
        const closing = 23;
        let html = '';

        for (let h = opening; h <= closing - duration; h++) {
            const start = String(h).padStart(2, '0') + ':00';
            const end = String(h + duration).padStart(2, '0') + ':00';
            html += `
                <div class="time-slot-card" onclick="selectSlot(this, '${start}', '${end}')" data-start="${start}" data-end="${end}">
                    <span class="time-slot-time">${start} - ${end}</span>
                    <span class="time-slot-radio"></span>
                </div>`;
        }
        container.innerHTML = html;

        // Update total price
        updateSingleDayPrice(duration);
        selectedSlot = null;
        document.getElementById('confirmSingleDay').disabled = true;
    }

    function selectSlot(el, start, end) {
        document.querySelectorAll('.time-slot-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        selectedSlot = { start, end };
        document.getElementById('confirmSingleDay').disabled = false;
    }

    function updateSingleDayPrice(duration) {
        const total = venuePricePerHour * duration;
        document.getElementById('totalPriceDisplay').textContent = 
            'Rp' + total.toLocaleString('id-ID');
    }

    // ===== SINGLE DAY: Confirm =====
    document.getElementById('confirmSingleDay').addEventListener('click', function() {
        if (!selectedSlot || !selectedDate) return;
        alert(`Booking: ${selectedDate}, ${selectedSlot.start} - ${selectedSlot.end}`);
        // Submit form ke booking.store
    });

    // ===== TOGGLE: Single ↔ Multi Day =====
    document.getElementById('switchToMultiDay').addEventListener('click', function() {
        document.getElementById('singleDayMode').style.display = 'none';
        document.getElementById('multiDayMode').style.display = 'block';
    });
    document.getElementById('switchToSingleDay').addEventListener('click', function() {
        document.getElementById('multiDayMode').style.display = 'none';
        document.getElementById('singleDayMode').style.display = 'block';
    });

    // ===== CALENDAR =====
    function openCalendar(target) {
        calendarTarget = target;
        document.getElementById('calendarOverlay').style.display = 'flex';
        renderCalendar();
    }

    function closeCalendar() {
        document.getElementById('calendarOverlay').style.display = 'none';
    }

    function navigateMonth(delta) {
        calendarDate.setMonth(calendarDate.getMonth() + delta);
        renderCalendar();
    }

    function renderCalendar() {
        const year = calendarDate.getFullYear();
        const month = calendarDate.getMonth();
        const today = new Date();
        today.setHours(0,0,0,0);

        document.getElementById('calendarMonthTitle').textContent = 
            new Date(year, month).toLocaleString('id-ID', { month: 'long', year: 'numeric' });

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const grid = document.getElementById('calendarGrid');
        let html = '';

        for (let i = 0; i < firstDay; i++) {
            html += '<div></div>';
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const date = new Date(year, month, d);
            const dateStr = date.toISOString().split('T')[0];
            let cls = 'calendar-day ';
            
            if (date < today) {
                cls += 'past';
            } else if (date.getTime() === today.getTime()) {
                cls += 'today';
            } else {
                // Demo: random availability
                const rand = Math.random();
                cls += rand > 0.7 ? 'full' : (rand > 0.3 ? 'partial' : 'available');
            }

            if ((calendarTarget === 'start' && tempStartDate === dateStr) || 
                (calendarTarget === 'end' && tempEndDate === dateStr)) {
                cls += ' selected-date';
            }

            html += `<div class="${cls}" onclick="pickDate('${dateStr}')">${d}</div>`;
        }

        grid.innerHTML = html;
    }

    function pickDate(dateStr) {
        if (calendarTarget === 'start') {
            tempStartDate = dateStr;
            document.getElementById('startDate').value = formatDate(dateStr);
        } else {
            tempEndDate = dateStr;
            document.getElementById('endDate').value = formatDate(dateStr);
        }
        renderCalendar();
        updateMultiDayPrice();
    }

    function applyCalendarDate() {
        closeCalendar();
        updateMultiDayPrice();
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    // ===== MULTI DAY: Price Update =====
    function updateMultiDayPrice() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;

        if (startDate && endDate && startTime && endTime) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const days = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
            const hoursPerDay = parseInt(endTime) - parseInt(startTime);
            const total = venuePricePerHour * hoursPerDay * days;

            document.getElementById('totalPriceMultiDay').textContent = 
                'Rp' + total.toLocaleString('id-ID');
            document.getElementById('confirmMultiDay').disabled = false;
        }
    }

    document.getElementById('startTime').addEventListener('change', updateMultiDayPrice);
    document.getElementById('endTime').addEventListener('change', updateMultiDayPrice);

    // ===== MULTI DAY: Confirm =====
    document.getElementById('confirmMultiDay').addEventListener('click', function() {
        // Simulasi cek bentrok
        const conflict = Math.random() < 0.3; // 30% chance demo
        if (conflict) {
            showConflictToast('Maaf, slot waktu ini (22 Juni, 19:00 - 21:00) sudah dipesan');
        } else {
            alert('Multi Day Booking confirmed!');
        }
    });

    // ===== TOAST =====
    function showConflictToast(message) {
        const toast = document.getElementById('conflictToast');
        document.getElementById('conflictMessage').textContent = message;
        toast.style.display = 'flex';
        setTimeout(() => { toast.style.display = 'none'; }, 4000);
    }

    // ===== INIT =====
    generateTimeSlots();
</script>
@endpush