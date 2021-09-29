$(document).ready(function () {
    if ($('#roleChoserModal').length) {
        $('#roleChoserModal').modal({
            backdrop: 'static',
            keyboard: false
        })
        $('#roleChoserModal').modal('show')
    }

    if ($("#phone").length) {
        $("#phone").mask("+7 (999) 999-99-99");
        $("#phone1").mask("+7 (999) 999-99-99");
    }

    let userTypeRole;

    $(".userType").on("click", function () {
        userTypeRole = $(this).attr("data-role");
        $('#role').val(userTypeRole);
        $('#type_as_role').val(userTypeRole);
        $('#radius').val(0);

        $('#roleChoserModal').modal('hide');

        switch (userTypeRole) {
            case 'worker':
                $(".translate0").text("Добавьте себя на карту исполнителей");
                $(".translate1").text("Укажите ваше примерное расположение на карте");
                $(".translate2").text("Укажите радиус выполнения Ваших услуг");
                $(".notForWorker").hide();
                $(".translate3").text("2");
                $(".translate4").text("Данные для вашего аккаунта");
                break;

            default:
                $('input#party').prop('required', 'required');
                $('input#phone1').prop('required', 'required');
                break;
        }
    });

    // $("#submitRegister").on("click", function (e) {
    //     if ($('password').val().length < 8) {
    //         e.preventDefault();
    //     }
    // });

    if ($('#yandex-map').length) {
        var slider = new Slider("#companyRadius", {
            ticks: [0, 250, 500],
            ticks_labels: ['0', '250 км.', '500 км.'],
            ticks_snap_bounds: 30,
            formatter: function formatter(val) {
                return val + " км.";
            },
        });

        var circleRadius = 0;
        var circle, placemarkToBack;

        slider.on("change", function (slideEvt) {
            circleRadius = parseInt(slideEvt.newValue) * 1000;
            circle.geometry.setRadius(circleRadius);
            $('#radius').val(slideEvt.newValue);
        });

        function placemarkForBack(coordsArray) {
            // console.log(coordsArray);
            if (coordsArray.length > 0) {
                placemarkToBack = coordsArray[0] + ";" + coordsArray[1];
                $('#coords').val(placemarkToBack);
                // console.log(placemarkToBack);
            }
        }

        ymaps.ready(init);

        function init() {
            // Создаем карту.
            var myMap = new ymaps.Map("yandex-map", {
                center: [55.76, 37.64],
                zoom: 5
            }, {
                searchControlProvider: 'yandex#search'
            });

            // Создаем точку.
            var placemark;

            placemark = new ymaps.Placemark([55.76, 37.64], {
                iconContent: 'Это вы',
            }, {
                preset: 'islands#redStretchyIcon',
                draggable: true,
                //   openEmptyBalloon: true
            });

            placemark.properties.set('iconContent', "Идет загрузка данных...");

            circle = new ymaps.Circle([placemark.geometry.getCoordinates(), 10000], {}, {
                geodesic: true
            });

            circle.geometry.setRadius(circleRadius);
            placemarkForBack(placemark.geometry.getCoordinates());
            getAddress(placemark.geometry.getCoordinates());

            myMap.events.add('click', function (e) {
                var coords = e.get('coords');
                placemark.geometry.setCoordinates(coords);
                placemarkForBack(placemark.geometry.getCoordinates());
                getAddress(placemark.geometry.getCoordinates());
                return circle.geometry.setCoordinates(placemark.geometry.getCoordinates());
            });

            placemark.events.add('drag', function (e) {
                placemark.geometry.setCoordinates(coords);
                placemarkForBack(placemark.geometry.getCoordinates());
                getAddress(placemark.geometry.getCoordinates());
                return circle.geometry.setCoordinates(placemark.geometry.getCoordinates());
            });

            myMap.geoObjects.add(placemark);
            myMap.geoObjects.add(circle);

            // Определяем адрес по координатам (обратное геокодирование).
            function getAddress(coords) {
                ymaps.geocode(coords).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);

                    placemark.properties
                        .set({
                            // Формируем строку с данными об объекте.
                            iconContent: [
                                // Название населенного пункта или вышестоящее административно-территориальное образование.
                                firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                                // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                                //   firstGeoObject.getThoroughfare() || firstGeoObject.getPremise(),
                                res.geoObjects.get(0) ? res.geoObjects.get(0).properties.get('name') : 'Не удалось определить адрес.'
                            ].filter(Boolean).join(', '),
                            // В качестве контента балуна задаем строку с адресом объекта.
                            balloonContent: firstGeoObject.getAddressLine()
                        });
                    $('#address_from_map').val(firstGeoObject.getAddressLine());
                    $('#city_from_map').val(firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas());

                });
            }
        }
    }
});
