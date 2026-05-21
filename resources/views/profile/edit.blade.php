@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Редактирование профиля</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Имя пользователя</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="avatar" class="form-label">Аватар (jpg, png, gif, до 2 МБ)</label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                       id="avatar" name="avatar" accept="image/*">
                                @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @if($user->avatar)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/app/public/' . $user->avatar) }}" width="80" class="img-thumbnail rounded-circle">
                                        <div class="form-text">Текущий аватар</div>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Новый пароль (оставьте пустым, если не хотите менять)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" autocomplete="new-password">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                <a href="{{ route('profile.show', $user->id) }}" class="btn btn-outline-secondary">Отмена</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
