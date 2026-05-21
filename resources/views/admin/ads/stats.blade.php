@extends('admin.layouts.admin')

@section('content')
    <h1>Статистика баннера: {{ $ad->name }}</h1>
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4"><div class="card text-center"><div class="card-body"><h3>{{ $impressions }}</h3><p>Показов</p></div></div></div>
        <div class="col-12 col-md-4"><div class="card text-center"><div class="card-body"><h3>{{ $clicks }}</h3><p>Кликов</p></div></div></div>
        <div class="col-12 col-md-4"><div class="card text-center"><div class="card-body"><h3>{{ $ctr }}%</h3><p>CTR</p></div></div></div>
    </div>
    <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">← Назад к списку</a>
@endsection
