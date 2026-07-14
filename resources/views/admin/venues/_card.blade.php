{{-- Venue Card Partial --}}
<div class="col-lg-4 col-md-6">
    <div class="venue-admin-card">
        {{-- Image --}}
        <div class="venue-admin-img-wrap">
            @php $firstImage = $venue->images->first(); @endphp
            @if($firstImage)
                <img src="{{ asset('storage/' . $firstImage->image_path) }}" 
                     alt="{{ $venue->name }}" class="venue-admin-img">
            @else
                <div class="venue-admin-img-placeholder">
                    <i class="fa-solid fa-image"></i>
                </div>
            @endif
            
            {{-- Status Badge --}}
            <span class="venue-status-badge status-{{ $venue->status }}">
                @if($venue->status === 'available')
                    ● Active
                @elseif($venue->status === 'maintenance')
                    🔍 Maintenance
                @else
                    ● In Use
                @endif
            </span>

            {{-- VIP Badge (conditional) --}}
            @if(isset($venue->is_vip) && $venue->is_vip)
                <span class="vip-badge">VIP</span>
            @endif
        </div>

        {{-- Body --}}
        <div class="venue-admin-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="venue-admin-name">{{ $venue->name }}</h4>
                    <p class="venue-admin-meta">
                        <i class="fa-solid fa-futbol me-1"></i> 
                        {{ ucfirst($venue->type) }}
                    </p>
                </div>
                <div class="venue-admin-price text-end">
                    Rp{{ number_format($venue->price_per_hour, 0, ',', '.') }}<small>/hr</small>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="venue-admin-actions">
            <a href="{{ route('admin.venues.edit', $venue->id) }}" class="venue-edit-btn">
                <i class="fa-solid fa-pen me-1"></i> Edit
            </a>
            <form method="POST" action="{{ route('admin.venues.destroy', $venue->id) }}" 
                  onsubmit="return confirm('Yakin hapus venue ini?')" style="display: inline;">
                @csrf @method('DELETE')
                <button type="submit" class="venue-delete-btn" title="Delete">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>