<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <title>Заказ-наряд от сервиса Gravescare</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link href="//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link href="/css/bootstrap-slider.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="https://yastatic.net/share2/share.js"></script>
</head>

<body>
    <div id="app">
        <main class="pb-4">
            @yield('content')
        </main>
    </div>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>

    <script src="//code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="//api-maps.yandex.ru/2.1/?apikey=8ee8ae8d-c504-46b5-958f-51ee95eaf3b9&lang=ru_RU" type="text/javascript"></script>
    <script src="/js/bootstrap-slider.min.js" defer></script>
    <script src="/js/jquery.maskedinput.min.js" defer></script>

    <script src="//cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js"></script>

    <script>
        $("#party").suggestions({
            token: "0b505f4769f7feba086adcb9c73b4833075f3524",
            type: "PARTY",
            /* Вызывается, когда пользователь выбирает одну из подсказок */
            onSelect: function(suggestion) {
                let data = suggestion.data;
                $('#value').val(suggestion.value);
                $('#inn').val(data.inn);
                $('#kpp').val(data.kpp);
                $('#ogrn').val(data.ogrn);
                $('#ogrn_date').val(data.ogrn_date);
                $('#hid').val(data.hid);
                $('#type').val(data.type);
                $('#name_full_with_opf').val(data.name.full_with_opf);
                $('#name_short_with_opf').val(data.name.short_with_opf);
                $('#name_full').val(data.name.full);
                $('#name_short').val(data.name.short);
                $('#opf_short').val(data.opf.short);
                $('#management_name').val(data.management.name);
                $('#management_post').val(data.management.post);
                $('#address_value').val(data.address.value);
                $('#address_unrestricted_value').val(data.address.unrestricted_value);
                $('#state_actuality_date').val(data.state.actuality_date);
                $('#state_registration_date').val(data.state.registration_date);
                $('#state_liquidation_date').val(data.state.liquidation_date);
                $('#state_status').val(data.state.status);


                console.log(suggestion);
            }
        });
    </script>
    <script src="/js/register.js"></script>

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