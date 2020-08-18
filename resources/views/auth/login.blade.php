@extends('layouts.app')

@section('content')
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
                <a href="{{ url('/home') }}" class="btn btn-lg btn-primary mt-2">Войти в систему</a>
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
@endsection