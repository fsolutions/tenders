<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <title>Личный кабинет сервиса Gravescare Tenders</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link href="//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="flex-center position-ref full-height">
        <div class="wrapper fadeInDown">
            <div id="formContent" style="margin-top: 30px;">
                <!-- Icon -->
                <div class="fadeIn first text-center py-4">
                    <img src="/img/promo/logo.jpg" style="width: 100%; height: auto; max-width: 200px;" alt="Сервис Gravescare Tenders">
                </div>

                @if (Route::has('login'))
                @auth
                <div class="mb-5">
                    <p class="bold">Вы успешно авторизованы!</p>
                    <a href="{{ url('/home') }}" class="btn btn-lg btn-success mt-2">Перейти к тендерам</a>
                    <a href="{{ url('/logout') }}" class="btn btn-secondary ml-2 mt-2">Выйти</a>
                </div>
                @else

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="container">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6 fadeIn second">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row fadeIn third">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Пароль') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row fadeIn fourth">
                            <div class="col-md-6 offset-md-4 text-left">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Запомнить меня') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Войти в систему') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Remind Passowrd -->
                <div id="formFooter">
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="underlineHover bold">Добавить свою организацию</a>
                    @endif
                    <a href="/orders" class="underlineHover uHred bold">Посмотреть текущие тендеры (ДЕМО-Доступ)</a>
                    <p class="text-center mt-3"><a href="https://t.me/joinchat/VgkBnPIzKvrTm7H1" class="btn btn-info" style="color: #FFF;" target="_blank">Отслеживать заказы в Telegram <i class="fab fa-telegram-plane"></i></a></p>
                    @if (Route::has('password.request'))
                    <a class="underlineHover" href="{{ route('password.request') }}">
                        {{ __('Забыли свой пароль?') }}
                    </a>
                    @endif
                </div>

                @endauth
                @endif
            </div>
        </div>
    </div>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(66744505, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true
        });
    </script> <noscript>
        <div><img src="https://mc.yandex.ru/watch/66744505" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript> <!-- /Yandex.Metrika counter -->
</body>

</html>