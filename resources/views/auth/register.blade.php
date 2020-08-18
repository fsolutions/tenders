@extends('layouts.app')

@section('content')
<div class="position-ref full-height">
    <div class="wrapper fadeInDown">
        <div id="formContent" class="lg" style="margin-top: 30px; text-align: left;">
            <!-- Icon -->
            <div class="fadeIn first text-center pt-4 pb-2">
                <img src="/img/promo/logo.jpg" style="width: 100%; height: auto; max-width: 270px;" alt="Сервис Gravescare Tenders">
            </div>
            <div class="text-center">
                <h3 class="translate0">Добавьте свою организацию:</h3>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <input name="role" id="role" type="hidden" value="" />
                <input name="type_as_role" id="type_as_role" type="hidden" value="" />
                <input name="radius" id="radius" type="hidden" value="" />

                <input name="coords" id="coords" type="hidden" value="" />
                <input name="address_from_map" id="address_from_map" type="hidden" value="" />
                <input name="city_from_map" id="city_from_map" type="hidden" value="" />
                <input name="value" id="value" type="hidden" value="" />
                <input name="inn" id="inn" type="hidden" value="" />
                <input name="kpp" id="kpp" type="hidden" value="" />
                <input name="ogrn" id="ogrn" type="hidden" value="" />
                <input name="ogrn_date" id="ogrn_date" type="hidden" value="" />
                <input name="hid" id="hid" type="hidden" value="" />
                <input name="type" id="type" type="hidden" value="" />
                <input name="name_full_with_opf" id="name_full_with_opf" type="hidden" value="" />
                <input name="name_short_with_opf" id="name_short_with_opf" type="hidden" value="" />
                <input name="name_full" id="name_full" type="hidden" value="" />
                <input name="name_short" id="name_short" type="hidden" value="" />
                <input name="opf_short" id="opf_short" type="hidden" value="" />
                <input name="management_name" id="management_name" type="hidden" value="" />
                <input name="management_post" id="management_post" type="hidden" value="" />
                <input name="address_value" id="address_value" type="hidden" value="" />
                <input name="address_unrestricted_value" id="address_unrestricted_value" type="hidden" value="" />
                <input name="state_actuality_date" id="state_actuality_date" type="hidden" value="" />
                <input name="state_registration_date" id="state_registration_date" type="hidden" value="" />
                <input name="state_liquidation_date" id="state_liquidation_date" type="hidden" value="" />
                <input name="state_status" id="state_status" type="hidden" value="" />

                <div class="container">
                    <div class="row mb-4 mt-5">
                        <div class="col-sm-12">
                            <span class="step-number__count text-center">1</span>
                            <span class="step-number__title ml-2 translate1">Укажите расположение организации на карте</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <div id="yandex-map" class="yandex-map ltr" data-lang="ru_RU"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="mb-2 text-center"><label for="companyRadius"><b><span class="translate2">Укажите радиус выполнения Ваших услуг</span></b></small></label></div>
                                <input name="companyRadius" id="companyRadius" type="text" value="0" />
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 notForWorker">
                        <div class="col-sm-12">
                            <span class="step-number__count text-center">2</span>
                            <span class="step-number__title ml-2">Заполните краткие сведения о организации</span>
                        </div>
                    </div>

                    <div class="form-group row fadeIn third notForWorker">
                        <label class="col-md-2 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('ИНН или название организации *') }}</small></label>

                        <div class="col-md-4">
                            <input id="party" name="party" class="form-control" type="text" />
                        </div>

                        <label for="website" class="col-md-2 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('Website организации') }}</small></label>

                        <div class="col-md-4">
                            <input id="website" type="text" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website') }}" autocomplete="website">

                            @error('website')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group row fadeIn third notForWorker">
                        <label for="phone1" class="col-md-2 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('Телефон организации *') }}</small></label>

                        <div class="col-md-4">
                            <input id="phone1" type="text" class="form-control @error('phone1') is-invalid @enderror" name="phone1" value="{{ old('phone1') }}" autocomplete="phone1">

                            @error('phone1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <label for="email1" class="col-md-2 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('Email организации') }}</small></label>

                        <div class="col-md-4">
                            <input id="email1" type="text" class="form-control @error('email1') is-invalid @enderror" name="email1" value="{{ old('email1') }}" autocomplete="email1">

                            @error('email1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>


                    <div class="row mb-4 mt-4">
                        <div class="col-sm-12">
                            <span class="step-number__count text-center translate3">3</span>
                            <span class="step-number__title ml-2 translate4">Данные для управления аккаунтом организации</span>
                        </div>
                    </div>

                    <div class="form-group row fadeIn second">
                        <label for="name" class="col-md-2 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('Ваше имя *') }}</small></label>

                        <div class="col-md-4">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <label for="email" class="col-md-2 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('E-Mail для связи *') }}</small></label>

                        <div class="col-md-4">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row fadeIn third">
                        <label for="phone" class="col-md-2 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('Телефон для связи *') }}</small></label>

                        <div class="col-md-4">
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <label for="password" class="col-md-4 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('Пароль *') }}<br>минимум 8 символов</small></label>

                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-2">
                                <label for="password-confirm" class="col-md-4 col-form-label min-line-height text-md-right"><small class="text-muted">{{ __('Пароль еще раз *') }}</small></label>

                                <div class="col-md-8">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="termsAgreed" required>
                                <label class="form-check-label" for="termsAgreed">
                                    Согласен с <a href="/privacy-policy" target="_blank">политикой конфиденциальности</a> и подтверждаю <a href="/terms" target="_blank">договор оферты</a>.
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <small>* Поля, отмеченные звездочкой (*) обязательны к заполнению.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success" id="submitRegister">
                                {{ __('Зарегистрироваться') }}
                            </button>
                        </div>
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
<!-- Modal Choosing Role -->
<div class="modal fade" id="roleChoserModal" tabindex="-1" aria-labelledby="roleChoserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="margin: 0 auto; display: block;" id="roleChoserModalLabel">Кого вы представляете</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
                <a href="/" class="btn"><span aria-hidden="true">&times;</span></a>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <button type="button" data-role="company" class="userType btn btn-primary" style="width: 300px;">Ритуальное агентство</button>
                </div>
                <div class="text-center mb-3">
                    <button type="button" data-role="company-granit" class="userType btn btn-primary" style="width: 300px;">Гранитная мастерская</button>
                </div>
                <div class="text-center mb-3">
                    <button type="button" data-role="worker" class="userType btn btn-primary" style="width: 300px;">Частное лицо</button>
                </div>
                <div class="text-center mb-3">
                    <button type="button" data-role="graves-admin" class="userType btn btn-primary" style="width: 300px;">Администрация кладбища</button>
                </div>
                <div class="text-center mb-3">
                    <button type="button" data-role="church-admin" class="userType btn btn-primary" style="width: 300px;">Представитель церкви</button>
                </div>

                <!-- <div class="text-center">
                    <a href="/" data-role="company" class="btn btn-secondary" style="width: 300px;">Отменить регистрацию</a>
                </div> -->
            </div>
            <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div> -->
        </div>
    </div>
</div>
<!-- END: Modal Choosing Role -->

@endsection