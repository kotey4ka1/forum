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
    <div class="card my-4 border-0 bg-light" data-material-id="{{ $chosen->id }}">
        <div class="card-body p-2 text-center">
            <a href="{{ route('ad.click', $chosen->id) }}" target="_blank">
                <img src="{{ asset('storage/app/public/' . $chosen->content) }}" class="img-fluid rounded" style="max-width: 100%; max-height: 150px;">
            </a>
        </div>
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
