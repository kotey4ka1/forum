@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Обращение #{{ $support->id }}</h5>
                @if(auth()->id() == $support->user_id || auth()->user()->isAdmin())
                    <form action="{{ route('support.destroy', $support) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Удалить</button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-3"><strong>Тема:</strong> {{ $support->subject }}</div>
                <div class="mb-3"><strong>Тип:</strong> {{ $support->type_name }}</div>
                <div class="mb-3"><strong>Статус:</strong> <span class="badge bg-secondary">{{ $support->status_name }}</span></div>
                <div class="mb-3"><strong>Сообщение:</strong><br>{{ $support->content }}</div>
                @if($support->response)
                    <hr>
                    <div class="mb-3"><strong>Ответ администратора:</strong><br>{{ $support->response }}</div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('support.index') }}" class="btn btn-secondary">← Назад</a>
            </div>
        </div>
    </div>
@endsection
