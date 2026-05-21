@extends('admin.layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Перемещение поста: {{ $post->title }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.posts.move', $post) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="section_id" class="form-label">Выберите раздел</label>
                    <select class="form-select" id="section_id" name="section_id" required>
                        <option value="" disabled selected>-- Выберите раздел --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ $post->forum_section_id == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Переместить</button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
@endsection
