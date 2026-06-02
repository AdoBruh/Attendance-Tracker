<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }

        .app-navbar {
            background: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }

        .navbar-brand {
            color: #0d6efd;
        }

        .navbar-brand:hover {
            color: #0b5ed7;
        }

        .nav-link {
            color: #333333;
        }

        .nav-link:hover {
            color: #0d6efd;
        }

        .page-title {
            font-weight: 700;
            color: #212529;
        }

        .card {
            border-radius: 8px;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: #0d6efd;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .profile-btn {
            border: 1px solid #dee2e6;
            border-radius: 999px;
            background: #fff;
            padding: 4px 10px 4px 4px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light app-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/dashboard">Attendance Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/users">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="/attendance">Attendance</a></li>
                        <li class="nav-item ms-lg-3">
                            <div
                                id="profileMenu"
                                data-name="{{ auth()->user()->name }}"
                                data-photo="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : '' }}"
                            ></div>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                Please check the form and try again.
            </div>
        @endif

        @yield('content')
    </main>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="mainToast" class="toast" role="alert">
            <div class="toast-header">
                <strong class="me-auto">Attendance Tracker</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') ?? session('error') }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @auth
        <script src="https://cdn.jsdelivr.net/npm/react@18/umd/react.production.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/react-dom@18/umd/react-dom.production.min.js"></script>
        <script>
            let profileTarget = document.getElementById('profileMenu');

            if (profileTarget) {
                let userName = profileTarget.dataset.name;
                let photo = profileTarget.dataset.photo;
                let letter = userName ? userName.charAt(0).toUpperCase() : 'U';

                function ProfileMenu() {
                    let avatar = photo
                        ? React.createElement('img', { src: photo, className: 'profile-avatar', alt: userName })
                        : React.createElement('span', { className: 'profile-avatar' }, letter);

                    return React.createElement('div', { className: 'dropdown' },
                        React.createElement('button', {
                            className: 'profile-btn dropdown-toggle d-flex align-items-center gap-2',
                            type: 'button',
                            'data-bs-toggle': 'dropdown'
                        }, avatar, React.createElement('span', { className: 'd-none d-sm-inline' }, userName)),
                        React.createElement('ul', { className: 'dropdown-menu dropdown-menu-end shadow-sm' },
                            React.createElement('li', null,
                                React.createElement('a', { className: 'dropdown-item', href: '/profile' }, 'My Profile')
                            ),
                            React.createElement('li', null,
                                React.createElement('form', { method: 'POST', action: '/logout' },
                                    React.createElement('input', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }),
                                    React.createElement('button', { className: 'dropdown-item text-danger', type: 'submit' }, 'Logout')
                                )
                            )
                        )
                    );
                }

                ReactDOM.createRoot(profileTarget).render(React.createElement(ProfileMenu));
            }
        </script>
    @endauth
    @yield('scripts')
    @if (session('success') || session('error'))
        <script>
            new bootstrap.Toast(document.getElementById('mainToast')).show();
        </script>
    @endif
</body>
</html>
