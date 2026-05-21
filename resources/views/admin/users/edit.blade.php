@extends('admin.layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Редактирование пользователя: {{ $user->name }}</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Роль</label>
                    <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror"
                        {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                        @foreach($roles ?? \App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(auth()->id() == $user->id)
                        <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                        <small class="form-text text-muted">Вы не можете изменить свою роль.</small>
                    @endif
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_banned" id="is_banned" class="form-check-input" value="1"
                        {{ $user->is_banned ? 'checked' : '' }}
                        {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                    <label class="form-check-label" for="is_banned">Заблокирован</label>
                    @if(auth()->id() == $user->id)
                        <input type="hidden" name="is_banned" value="{{ $user->is_banned }}">
                        <small class="form-text text-muted d-block">Вы не можете заблокировать себя.</small>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Отмена</a>
            </form>
        </div>
    </div>
@endsection
