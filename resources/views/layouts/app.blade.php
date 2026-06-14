<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Форум бренда') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('public/assets/css/style.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<div id="app" class="d-flex flex-column flex-grow-1">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Форум бренда</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Поисковая строка -->
                <form class="d-flex ms-auto me-3" action="{{ route('search.results') }}" method="GET">
                    <div class="position-relative">
                        <input class="form-control search-input" type="search" name="q" placeholder="Поиск постов и разделов" autocomplete="off" style="width: 260px;">
                        <div class="suggestions-box position-absolute bg-white w-100 rounded shadow-sm mt-1" style="display: none;"></div>
                    </div>
                    <button class="btn btn-outline-primary ms-2" type="submit">Найти</button>
                </form>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Вход</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i>Регистрация</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->avatar && Storage::disk('public')->exists(Auth::user()->avatar))
                                    <img src="{{ asset('storage/app/public/' . Auth::user()->avatar) }}" class="rounded-circle me-2" width="36" height="36">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white me-2" style="width:36px;height:36px;">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                                <a class="dropdown-item" href="{{ route('profile.show', Auth::id()) }}"><i class="bi bi-person-circle me-2"></i>Мой профиль</a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i>Настройки</a>
                                <a class="dropdown-item" href="{{ route('favorites.index') }}"><i class="bi bi-star me-2"></i>Избранное</a>
                                <a class="dropdown-item" href="{{ route('support.index') }}"><i class="bi bi-envelope me-2"></i>Поддержка</a>
                                <a class="dropdown-item" href="{{ route('faq.index') }}"><i class="bi bi-question-circle me-2"></i>FAQ</a>

                                @if(Auth::user()->isModerator() || Auth::user()->isAdmin())
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2"></i>Админ-панель</a>
                                @endif

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right me-2"></i>Выйти</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </div>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
        <div class="container">
            {{-- Flash-сообщения --}}
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
        </div>
    </main>

    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Форум бренда одежды</h5>
                    <p>Общайтесь, делитесь опытом, будьте в курсе новостей.</p>
                </div>
                <div class="col-md-3">
                    <h5>Разделы</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Главная</a></li>
                        <li><a href="{{ route('faq.index') }}" class="text-white-50 text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Контакты</h5>
                    <p>Email: support@saikona.ru</p>
                </div>
            </div>
            <div class="text-center mt-3 border-top pt-3">
                &copy; {{ date('Y') }} Форум бренда. Все права защищены.
            </div>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function() {
        const input = document.querySelector('input[name="q"]');
        const box = document.querySelector('.suggestions-box');
        if (!input || !box) return;

        let timer;
        const historyKey = 'forum_search_history';
        const maxHistory = 10;

        function getHistory() {
            const raw = localStorage.getItem(historyKey);
            return raw ? JSON.parse(raw) : [];
        }

        function addHistory(q) {
            if (!q.trim()) return;
            let h = getHistory();
            h = h.filter(item => item !== q);
            h.unshift(q);
            if (h.length > maxHistory) h.pop();
            localStorage.setItem(historyKey, JSON.stringify(h));
        }

        function showSuggestions(items, isHistory = false) {
            if (!items.length) {
                box.style.display = 'none';
                return;
            }
            box.innerHTML = '';
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'suggestion-item p-2 border-bottom';
                div.style.cursor = 'pointer';
                if (isHistory) {
                    div.innerHTML = `<i class="bi bi-clock-history me-2"></i> ${escapeHtml(item)}`;
                    div.addEventListener('click', () => {
                        input.value = item;
                        addHistory(item);
                        input.closest('form').submit();
                    });
                } else {
                    const icon = item.type === 'section' ? 'bi-folder' : 'bi-chat-text';
                    div.innerHTML = `
                        <div class="d-flex align-items-start">
                            <i class="bi ${icon} me-2 mt-1"></i>
                            <div>
                                <div class="fw-semibold">${escapeHtml(item.title)}</div>
                                ${item.snippet ? `<small class="text-muted">${escapeHtml(item.snippet)}</small>` : ''}
                            </div>
                        </div>
                    `;
                    div.addEventListener('click', () => {
                        window.location.href = item.link;
                    });
                }
                box.appendChild(div);
            });
            box.style.display = 'block';
        }

        async function fetchSuggestions(q) {
            if (!q.trim()) {
                const history = getHistory().slice(0, 5);
                if (history.length) showSuggestions(history, true);
                else box.style.display = 'none';
                return;
            }
            try {
                const resp = await fetch(`/search/suggestions?q=${encodeURIComponent(q)}`);
                if (!resp.ok) throw new Error();
                const data = await resp.json();
                if (data.suggestions && data.suggestions.length) {
                    showSuggestions(data.suggestions, false);
                } else {
                    box.innerHTML = '<div class="p-2 text-muted">Ничего не найдено</div>';
                    box.style.display = 'block';
                }
            } catch(e) {
                box.style.display = 'none';
            }
        }

        input.addEventListener('input', function() {
            clearTimeout(timer);
            const val = this.value;
            if (val.length >= 2) {
                timer = setTimeout(() => fetchSuggestions(val), 300);
            } else {
                timer = setTimeout(() => fetchSuggestions(''), 100);
            }
        });

        input.addEventListener('focus', () => fetchSuggestions(input.value));

        document.addEventListener('click', (e) => {
            if (!box.contains(e.target) && e.target !== input) {
                box.style.display = 'none';
            }
        });

        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', () => addHistory(input.value));
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
    })();
</script>
</body>
</html>