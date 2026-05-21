@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 text-center">
                        <h3 class="fw-bold mb-0">Регистрация</h3>
                        <p class="text-muted mt-2">Создайте аккаунт, чтобы участвовать в обсуждениях</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Имя --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Имя пользователя</label>
                                <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person"></i>
                                </span>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror border-start-0"
                                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Ваш никнейм">
                                </div>
                                @error('name')
                                <div class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror border-start-0"
                                           name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="example@mail.ru">
                                </div>
                                @error('email')
                                <div class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>

                            {{-- Пароль --}}
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Пароль</label>
                                <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock"></i>
                                </span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror border-start-0"
                                           name="password" required autocomplete="new-password" placeholder="Не менее 8 символов">
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>

                            {{-- Подтверждение пароля --}}
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label fw-semibold">Подтверждение пароля</label>
                                <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                    <input id="password-confirm" type="password" class="form-control border-start-0"
                                           name="password_confirmation" required autocomplete="new-password" placeholder="Введите пароль ещё раз">
                                </div>
                            </div>

                            {{-- Кнопка регистрации --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold rounded-pill">
                                    <i class="bi bi-person-plus me-2"></i>Зарегистрироваться
                                </button>
                            </div>

                            {{-- Ссылка на вход --}}
                            <div class="text-center mt-4">
                                <span class="text-muted">Уже есть аккаунт?</span>
                                <a href="{{ route('login') }}" class="text-decoration-none ms-1 fw-semibold">Войти</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
