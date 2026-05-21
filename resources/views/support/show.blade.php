@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Обращение #{{ $supportRequest->id }}</h5>
                @if(auth()->id() == $supportRequest->user_id || auth()->user()->isAdmin())
                    <form action="{{ route('support.destroy', $supportRequest->id) }}" method="POST" onsubmit="return confirm('Удалить обращение?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-3"><strong>Тема:</strong> {{ $supportRequest->subject }}</div>
                <div class="mb-3"><strong>Тип:</strong> {{ $supportRequest->type }}</div>
                <div class="mb-3"><strong>Статус:</strong> <span class="badge bg-secondary">{{ $supportRequest->status }}</span></div>
                <div class="mb-3"><strong>Сообщение:</strong><br>{{ $supportRequest->content }}</div>
                @if($supportRequest->response)
                    <hr><div class="mb-3"><strong>Ответ администратора:</strong><br>{{ $supportRequest->response }}</div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('support.index') }}" class="btn btn-secondary">← Назад</a>
            </div>
        </div>
    </div>
@endsection
