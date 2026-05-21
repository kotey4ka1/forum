@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 text-center">
                        <h3 class="fw-bold mb-0">Восстановление пароля</h3>
                        <p class="text-muted mt-2">Введите ваш email, и мы отправим ссылку для сброса пароля</p>
                    </div>

                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror border-start-0"
                                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="example@mail.ru">
                                </div>
                                @error('email')
                                <div class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold rounded-pill">
                                    <i class="bi bi-send me-2"></i>Отправить ссылку для сброса
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>Вернуться ко входу
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
