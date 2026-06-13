@php
    $placementKey = 'between_posts';
    $materials = App\Models\AdMaterial::where('placement_key', $placementKey)
                ->where('is_active', true)
                ->get();
    $chosen = null;
    if ($materials->count()) {
        $totalWeight = $materials->sum('weight');
        $rand = mt_rand(1, max($totalWeight, 1));
        foreach ($materials as $mat) {
            if ($rand <= $mat->weight) {
                $chosen = $mat;
                break;
            }
            $rand -= $mat->weight;
        }
    }
@endphp
@if($chosen)
    <div class="ad-between-container w-100 overflow-hidden" style="height: 300px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;" data-material-id="{{ $chosen->id }}">
        <a href="{{ route('ad.click', $chosen->id) }}" target="_blank" class="d-block w-100 h-100 d-flex align-items-center justify-content-center">
            @if($chosen->type == 'banner')
                <img src="{{ asset('storage/app/public/' . $chosen->content) }}"
                     style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; display: block;">
            @else
                <div class="video-container w-100 h-100 d-flex align-items-center justify-content-center">
                    {!! $chosen->content !!}
                </div>
            @endif
        </a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("ad.impression", $chosen->id) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).catch(e => console.error('Ad error:', e));
        });
    </script>
@endif
