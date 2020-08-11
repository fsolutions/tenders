@extends('layouts.app')

@section('content')
<div class="flex-center position-ref full-height">
    <div class="wrapper fadeInDown">
        <div id="formContent" style="margin-top: 70px;">
            <!-- Icon -->
            <div class="fadeIn first text-center py-4">
                <img src="/img/promo/logo.jpg" style="width: 100%; height: auto; max-width: 200px;" alt="Сервис Gravescare Tenders">
            </div>
            <div class="">
                <p class="bold">Регистрация в системе:</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group row fadeIn second">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Ваше имя') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row fadeIn third">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row fadeIn fourth">
                    <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Телефон') }}</label>

                    <div class="col-md-6">
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">

                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row fadeIn fourth">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Пароль') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row fadeIn fourth">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Подтвердите пароль') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4 text-left">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Зарегистрироваться') }}
                        </button>
                    </div>
                </div>
            </form>
            <div id="formFooter">
                @if (Route::has('login'))
                <a href="/" class="underlineHover bold">Вход в систему</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection