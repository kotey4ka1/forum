@extends('admin.layouts.admin')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Пользователи</h1>
    </div>
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
        <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Имя или Email" value="{{ request('search') }}"></div>
        <div class="col-md-3"><select name="role_id" class="form-select"><option value="">Все роли</option>@foreach($roles as $role)<option value="{{ $role->id }}" {{ request('role_id')==$role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>@endforeach</select></div>
        <div class="col-md-2"><select name="is_banned" class="form-select"><option value="">Все</option><option value="1" {{ request('is_banned')=='1' ? 'selected' : '' }}>Забаненные</option><option value="0" {{ request('is_banned')=='0' ? 'selected' : '' }}>Активные</option></select></div>
        <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Фильтровать</button></div>
    </form>
    <table class="table table-bordered align-middle">
        <thead class="table-light">
        <tr><th>ID</th><th>Имя</th><th>Email</th><th>Роль</th><th>Забанен</th><th>Действия</th></tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->display_name ?? 'user' }}</td>
                <td>{{ $user->is_banned ? 'Да' : 'Нет' }}</td>
                <td><a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Ред.</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger ms-1">Уд.</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Нет пользователей</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $users->links() }}
@endsection
