@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Новое обращение</div>
            <div class="card-body">
                <form method="POST" action="{{ route('support.store') }}">
                    @csrf
                    <div class="mb-3"><label>Тема</label><input type="text" name="subject" class="form-control" required></div>
                    <div class="mb-3"><label>Тип</label><select name="type" class="form-control" required><option value="consultation">Консультация</option><option value="complaint">Жалоба</option><option value="other">Другое</option></select></div>
                    <div class="mb-3"><label>Сообщение</label><textarea name="content" rows="5" class="form-control" required></textarea></div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                    <a href="{{ route('support.index') }}" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </div>
@endsection
