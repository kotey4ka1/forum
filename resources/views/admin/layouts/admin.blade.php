<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
<div class="container-fluid flex-grow-1 px-0">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Админ-панель</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-label="Переключить навигацию">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Дашборд</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-1"></i>Пользователи</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.sections.index') }}"><i class="bi bi-grid me-1"></i>Разделы</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.posts.index') }}"><i class="bi bi-file-text me-1"></i>Посты</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.comments.index') }}"><i class="bi bi-chat-dots me-1"></i>Комментарии</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.ads.index') }}"><i class="bi bi-megaphone me-1"></i>Реклама</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.moderation.index') }}"><i class="bi bi-shield-shaded me-1"></i>Модерация</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.faq.index') }}"><i class="bi bi-question-circle me-2"></i>FAQ</a></li>
                </ul>
                <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm">На сайт</a>
            </div>
        </div>
    </nav>

    <main class="container py-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
            </div>
        @endif
        @yield('content')
    </main>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
