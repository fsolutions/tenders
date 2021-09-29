<template>
<div class="container">
    <section>
        <div class="row mt-5 mb-4">
            <div class="col-sm-12 col-md-6">
                <div class="headUp">{{ windowTitle }}</div>
            </div>
            <div class="col-sm-12 col-md-6"></div>
        </div>

        <div class="card nobrd">
            <div class="card-body table-responsive p-0">
                <div class="progress" v-if="progress != -1">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" :style="`width: ${progress}%`"></div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>

        <div class="card mb-3">
            <form class="needs-validation mb-4" novalidate id="orderCreateForm">            
                <div class="card-body">
                    <div class="card-title">
                        <div :class="`tendercart-type__status-public`">Индивидуальный заказ от пользователя</div>
                        <div class="b-tender__info-item-subtitle mt-3">Выберите тип объекта</div>
                        <div class="row mt-3 position-relative">
                            <div class="belena" v-if="order.datetime_of_client_signed_the_start"></div>
                            <div class="col-sm-12 col-md-4">
                                <select class="form-control" id="typeOfOrderObject" v-model="order.type_of_order_object">
                                    <option v-for="(item, index) in typeOfOrderObject" :value="item.value">{{item.text}}</option>
                                </select>                                
                            </div>
                            <div class="col-sm-12 col-md-8" style="display:none;">
                                <template v-if="order.type_of_order_object == 'Могила'">
                                    <div class="b-tender__info-item-subtitle">Состав заказа</div>
                                    <div v-for="(service, index) in serviceOptions">
                                        <div class="mb-0">
                                            <input 
                                                type="checkbox" 
                                                :id="`servicecheck${index}`"
                                                v-bind:value="service" 
                                                v-model="order.order_txt"
                                                :disabled="loading"
                                            >
                                            <label class="mb-0" :for="`servicecheck${index}`">{{service.name}}</label>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="b-tender__info-item-subtitle">Заказчик</div>
                            <div class="row position-relative">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Имя</label>
                                    <input type="text" class="form-control" v-model="order.user_web_users_name" required :disabled="loading">
                                </div>                            
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Телефон</label>
                                    <input type="text" class="form-control" v-model="order.user_web_users_phone" required :disabled="loading">
                                </div>                            
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Email, если есть</label>
                                    <input type="email" class="form-control" v-model="order.user_web_users_email" :disabled="loading">
                                </div> 
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>Данные паспорта, либо реквизиты организации</label>
                                    <textarea class="form-control" rows="3" v-model="order.user_web_passport" :disabled="loading"></textarea>
                                </div>                            
                                <div class="belena" v-if="order.datetime_of_client_signed_the_start"></div>                         
                            </div>
                        </div>

                        <div class="mt-3 position-relative">
                            <div class="b-tender__info-item-subtitle">
                                Перечень услуг
                                <button 
                                    v-if="!order.datetime_of_client_signed_the_start"
                                    type="button" 
                                    class="btn btn-sm btn-primary text-right ml-2 mb-2"
                                    @click="addOneToServicesList()"
                                >
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button
                                    v-if="!order.datetime_of_client_signed_the_start"
                                    type="button"
                                    class="btn btn-sm btn-secondary text-right ml-2 mb-2"
                                    @click="addOneToServicesList({name: 'Выезд агента', sum: 700})"
                                >
                                    Выезд агента
                                </button>
                                <button
                                    v-if="!order.datetime_of_client_signed_the_start"
                                    type="button"
                                    class="btn btn-sm btn-secondary text-right ml-2 mb-2"
                                    @click="addOneToServicesList({name: 'Фото и видео съемка ДО и ПОСЛЕ выполнения работ', sum: 0})"
                                >
                                    Фото и видео
                                </button>
                            </div>
                            <div class="belena" v-if="order.datetime_of_client_signed_the_start"></div>                         
                            <table class="table hover small caption-top responsive">
                                <thead v-if="order.order_services && order.order_services.data.length > 0">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">№</th>
                                        <th>Услуга</th>
                                        <th style="width: 7%;">Кол-во</th>
                                        <th style="width: 15%;">Цена</th>
                                        <th style="width: 15%;">Стоимость</th>
                                        <th class="text-center" style="width: 10%;">Действие</th>
                                    </tr>
                                </thead>
                                <tbody v-if="order.order_services && order.order_services.data.length > 0">
                                    <tr v-for="(service, index) in order.order_services.data" :key="index">
                                        <td class="text-center align-middle">{{service.id}}</td>
                                        <td class="align-middle position-relative">
                                            <input class="form-control" size="sm" type="text" v-model="service.name" placeholder="Введите наименование услуги"></input>
                                        </td>
                                        <td class="align-middle">
                                            <input class="form-control" size="sm" type="number" min="1" max="999999" v-model="service.number"></input>
                                        </td>
                                        <td class="align-middle">
                                            <input class="form-control" size="sm" type="text" @keyup="changeCommasOnDots($event)" v-model="service.sum"></input>
                                        </td>
                                        <td class="align-middle">{{(Number(service.number ? service.number : 1) * service.sum)}}</td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-sm btn-danger text-right" @click="delOneFromServicesList(index)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <div v-else>Добавьте услуги в заказ, нажав на
                                    <button type="button" class="btn btn-sm btn-primary text-right ml-2" @click="addOneToServicesList()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </table>
                        </div>

                        <div class="mt-3 position-relative">
                            <div class="b-tender__info-item-subtitle">Местоположение предоставления услуг</div>
                            <div class="belena" v-if="order.datetime_of_client_signed_the_start"></div>                         
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>Выберите город</label>
                                    
                                    <select v-model="order.order_city_id" class="form-control" required :disabled="loading">
                                        <optgroup 
                                            v-for="(region, rIndex) in citiesOptions"
                                            :key="rIndex"
                                            :label="region.name"
                                        >
                                            <option
                                                v-for="(city, cIndex) in region.cities"
                                                :value="city.id"
                                                :key="cIndex"
                                            >
                                                {{city.name}}
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>              
                                <template v-if="order.type_of_order_object == 'Могила'">              
                                    <div class="form-group col-sm-12 col-md-4" v-if="order.order_city_id">
                                        <label>Кладбище</label>
                                        <select v-model="order.order_object_id" class="form-control" :disabled="loading">
                                            <option
                                                v-for="(graveyard, index) in graveyardsOptions"
                                                :value="graveyard.id"
                                                :key="index"
                                            >
                                                {{graveyard.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-4">
                                        <template v-if="order.order_city_id && !order.order_object_id">
                                            <div class="mb-3">
                                                <label>Наименование кладбища</label>
                                                <input type="text" class="form-control" v-model="order.order_object_name_ext" :disabled="loading">
                                                <small class="form-text text-muted">Поле заполняется, если кладбище отсутствует в списке</small>
                                            </div>
                                        </template>
                                        <label>Особенности местонахождения захоронения</label>
                                        <textarea class="form-control" rows="2" v-model="order.address_of_order_object" :disabled="loading"></textarea>
                                    </div>
                                </template>
                                <template v-if="order.type_of_order_object != 'Могила'">
                                    <div class="form-group col-sm-12 col-md-8">
                                        <label>Адрес</label>
                                        <textarea class="form-control" rows="2" v-model="order.address_of_order_object" :disabled="loading"></textarea>
                                        <small class="form-text text-muted">Введите адрес максимально точно: область, город, улица, дом, квартира (код домофона или наименование кафе, БЦ и т.д.)</small>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <template v-if="order.type_of_order_object == 'Могила'">
                            <div class="mt-3 position-relative">
                                <div class="belena" v-if="order.datetime_of_client_signed_the_start"></div>
                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-4">
                                        <label>Номер захоронения</label>
                                        <input type="text" class="form-control" v-model="order.order_gravenumber" required :disabled="loading">
                                        <small class="form-text text-muted">Если не известно, поставьте прочерк</small>
                                    </div>                            
                                    <div class="form-group col-sm-12 col-md-4">
                                        <label>Имя на могиле/кресте</label>
                                        <input type="text" class="form-control" v-model="order.order_man_name" required :disabled="loading">
                                    </div>                            
                                    <div class="form-group col-sm-6 col-md-2">
                                        <label>Дата рождения</label>
                                        <input type="date" class="form-control" v-model="order.order_date_of_birth" :disabled="loading">
                                    </div>                            
                                    <div class="form-group col-sm-6 col-md-2">
                                        <label>Дата смерти</label>
                                        <input type="date" class="form-control" v-model="order.order_date_of_death" :disabled="loading">
                                    </div>                            
                                </div>
                            </div>
                        </template>
                        <div class="mt-3 position-relative">
                            <div class="belena" v-if="order.datetime_of_client_signed_the_start"></div>
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-4">
                                    <template v-if="order.type_of_order_object == 'Могила'">
                                        <label>Количество могил в заказе</label>
                                        <input type="number" class="form-control" v-model="order.number_of_graves" :disabled="loading" style="max-width: 70px">
                                    </template>
                                </div>
                                <div class="form-group col-sm-12 col-md-8">
                                    <label>Опишите детали выполнения услуг</label>
                                    <textarea class="form-control" rows="5" v-model="order.order_text_details" :disabled="loading"></textarea>
                                </div>                            
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="b-tender__info-item-subtitle">Взаиморасчеты</div>
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-4">
                                    <div class="custom-control custom-switch mt-3">
                                        <input type="checkbox" class="custom-control-input" id="openedOrderSwitch" v-model="order.opened_order">
                                        <label class="custom-control-label" for="openedOrderSwitch">Открытый тендер <br><small class="form-text text-muted">(контакты доступны всем)</small></label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4">
                                    <label>Стоимость услуг для клиента</label>
                                    <input type="text" class="form-control" v-model="order.itogsum_for_client" @keyup="changeCommasOnDots($event)" required style="max-width: 150px">

                                    <div class="mt-2">
                                        <label>Скидка для клиента в рублях</label>
                                        <input type="text" class="form-control" v-model="order.skidka_for_client" @keyup="changeCommasOnDots($event)" required style="max-width: 150px">
                                    </div>
                                    <div class="mt-2">
                                        Финальная сумма для клиента: 
                                        <b>{{ (order.itogsum_for_client - order.skidka_for_client).toFixed(2) }}</b>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 col-md-4">
                                    <label>Стоимость услуг для агента</label>
                                    <input type="text" class="form-control" v-model="order.itogsum" @keyup="changeCommasOnDots($event)" required style="max-width: 150px">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 position-relative">
                            <div class="b-tender__info-item-subtitle">Порядок работ для Заказ-наряда</div>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <div class="mb-3">
                                        <label>Старт работ намечен на</label>
                                        <input type="date" class="form-control" v-model="order.start_date_of_work" :disabled="loading">
                                    </div>

                                    <label>Дней на исполнение</label>
                                    <input type="number" class="form-control" v-model="order.oriental_days_for_work" :disabled="loading" style="max-width: 70px">
                                </div>                            
                                <div class="form-group col-sm-12 col-md-8">
                                    <label>Особенности (деловой стиль, без ошибок, если есть)</label>
                                    <textarea class="form-control" rows="3" v-model="order.final_comment" :disabled="loading"></textarea>
                                </div>                            
                            </div>
                            <div class="belena" v-if="order.datetime_of_client_signed_the_start"></div>
                        </div>

                        <div class="mt-3">
                            <div class="b-tender__info-item-subtitle">Данные для Акта</div>
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-4">
                                    <label>Дата завершения работ</label>
                                    <input type="date" class="form-control" v-model="order.end_date_of_work" :disabled="loading">
                                </div>                            
                                <div class="form-group col-sm-12 col-md-8">
                                    <label>Приложите фотографии</label>
                                    <p>В разработке...</p>
                                </div>                            
                            </div>
                        </div>

                    </div>

                    <div class="text-center mt-3">
                        <button @click="submitForm($event)" class="btn btn-lg btn-success">
                            <i class="fas fa-spinner fa-spin mr-2" v-if="orderPublication"></i>{{submitButtonText}}
                        </button>
                    </div>
                </div>
            </form>
        </div>    

        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class=" text-center text-md-left mb-3 left-border-line" v-if="order.can_delete">
                    Заказ-наряд: 
                    <a :href="'/orders/client/' + order.id" target="_blank" class="btn btn-sm btn-secondary mb-2">Для клиента</a>
                    <button class="btn btn-sm btn-secondary mb-2" disabled>Для агента</button>
                    <div>
                        <span class="reference _public">Клиент подписал на старт работ: {{order.datetime_of_client_signed_the_start | formattedDateTime}}</span><br>
                        <span class="reference _public">Клиент подписал на завершение работ: {{order.datetime_of_client_signed_the_end | formattedDateTime}}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class=" text-center text-md-left mb-3 left-border-line" v-if="order.can_delete">
                    Акты: 
                    <a :href="'/orders/client/act/' + order.id" target="_blank" class="btn btn-sm btn-secondary mb-2">Для клиента</a>
                    <button class="btn btn-sm btn-secondary mb-2" disabled>Для агента</button>
                    <div>
                        <span class="reference _public">Клиент подписал акт: {{order.datetime_of_client_signed_the_end | formattedDateTime}}</span><br>
                        <span class="reference _public">Агент подписал акт: {{order.datetime_of_ispolnitel_signed_the_end | formattedDateTime}}</span>
                    </div>
                </div>
            </div>
        </div>

    </section><!--/main-->

    <footer-block></footer-block>
</div>
</template>

<script>
    import { API_CRUD_ORDER, API_ORDER_SERVICES_LIST, API_CITIES, API_GRAVEYARDS } from '../API'

    import {
        changeCommasOnDots,
    } from '../mixins'

    const initialEditedItem = () => ({
        id: '',
        user_web_users_id: 1,
        user_web_users_name: '',
        user_web_users_phone: '',
        user_web_users_email: '',
        user_web_passport: '',
        paymentid: '',
        paymenturl: '',
        order_txt: [],
        order_city_id: '', 
        order_object_id: '', 
        order_object_name_ext: '', 
        order_text_details: '', 
        opened_order: 0,
        city: {},
        graveyard: {},
        number_of_graves: 1,
        itogsum: '',
        itogsum_for_client: 0,
        skidka_for_client: 0,
        tarif: 'individual',
        status: 3, 
        virtual: {
            graveyard_name: '',
        },
        manager_id: '',
        manager: {},
        order_services: {
            data: [],
            itog: 0
        },
        start_date_of_work: '',
        end_date_of_work: '',
        oriental_days_for_work: '',
        type_of_order_object: 'Могила',   // ['Могила', 'Квартира', 'Помещение', 'Другое']
        address_of_order_object: '',
        final_comment: '',
        datetime_of_client_signed_the_start: '',
        datetime_of_client_signed_the_end: '',
        datetime_of_ispolnitel_signed_the_start: '',
        datetime_of_ispolnitel_signed_the_end: '',
    })

  export default {
    mixins: [
        changeCommasOnDots,
    ],
    props: {
        importedOrder: {
        type: Object,
        required: false
        }
    },    
    components: {
    },
    data() {
      return {
        mapOrder: '',
        order: {},
        savingOrder: false,
        limit: 10,
        progress: -1,
        loading: false,
        loadingTimer: 1000,
        search: "",
        serviceOptions: [],
        citiesOptions: [],
        graveyardsOptions: [],
        submitButtonText: 'Опубликовать заказ',
        windowTitle: 'Создать новый заказ',
        orderPublication: false,
        editingOrder: false,
        typeOfOrderObject: [
            { text: 'Выберите тип объекта', value: null, disabled: true },
            { text: 'Могила', value: 'Могила' },
            { text: 'Квартира', value: 'Квартира' },
            { text: 'Помещение', value: 'Помещение' },
            { text: 'Другое', value: 'Другое' },
        ],
      }
    },
    watch: {
        order: {
            handler() {
                this.order.opened_order = this.order.opened_order == 0 ? false : true
            },
            deep: true,
            immediate: true,
        },
        "order.order_city_id"(city_id) {
            this.loadGraveYards(city_id)
        },
        "order.opened_order"(value) {
            if (value) {
                this.order.itogsum = this.order.itogsum_for_client
            }
        },
        "order.order_services.data": {
            deep: true,
            handler: 'recheckNumbersInServicesList'
        },
    },
    computed: {
      // logMessages() {
      //     return this.$store.getters.getLogMessages
      // },
    },
    mounted() {
        this.order = initialEditedItem()
        this.loadServices()
        this.loadCities()
        this.prepareForEditOfOrder()
    },
    methods: {
        prepareForEditOfOrder() {
            if (this.importedOrder) {
                this.editingOrder = true;
                this.order = Object.assign(this.order, this.importedOrder);
                this.order.order_txt = this.order.order_txt_preped;

                this.$set(this.order, 'virtual', {});
                this.$set(this.order.virtual, 'reaction', false);
                this.$set(this.order.virtual, 'choice_edit', 0);
                this.$set(this.order.virtual, 'graveyard_name', "");
                this.$set(this.order.virtual, 'map', false);
                this.$set(this.order.virtual, 'grave_lat', '');
                this.$set(this.order.virtual, 'grave_long', '');

                if (!this.order.order_object_name_ext && this.order.graveyard) {
                    this.order.virtual.graveyard_name = this.order.graveyard.pagetitle;
                } else if (this.order.order_object_name_ext) {
                    this.order.virtual.graveyard_name = this.order.order_object_name_ext;
                }

                if (this.order.graveyard && this.order.graveyard.introtext) {
                    let coords = this.order.graveyard.introtext.split(",");
                    this.order.virtual.map = true;
                    this.order.virtual.grave_lat = coords[0];
                    this.order.virtual.grave_long = coords[1];
                }

                this.submitButtonText = 'Обновить заказ'
                this.windowTitle = 'Редактировать заказ #' + this.order.id
                console.log("order", this.order);
            }
        },
        loadServices() {
            // [id] => 171957
            // [name] => Уборка листвы, мусора
            this.loading = true;
            axios.get(API_ORDER_SERVICES_LIST)
                .then(response => {
                    this.serviceOptions = response.data;
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.loading = false));
        },
        loadCities() {
            this.loading = true;
            axios.get(API_CITIES)
                .then(response => {
                    this.citiesOptions = response.data;
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.loading = false));
        },
        loadGraveYards(city_id) {
            if (this.order.order_city_id) {
                this.loading = true;
                axios.get(API_GRAVEYARDS + '/' + city_id)
                    .then(response => {
                        this.graveyardsOptions = response.data;
                        this.graveyardsOptions.push({
                            id: '',
                            name: 'Другое кладбище'
                        })
                    })
                    .catch(err => console.log(err.response.data))
                    .finally(() => (this.loading = false));
            }
        },
        submitForm(event) {
            event.preventDefault()
            let form = document.getElementById("orderCreateForm")
            if (form.checkValidity() === false) {
                event.preventDefault()
                event.stopPropagation()
                form.classList.add('was-validated')
                return false
            }

            if (this.editingOrder) {
                this.updateOrder()
            } else {
                this.createOrder()
            }
        },
        createOrder() {
            this.loading = true;
            this.orderPublication = true

            axios.post(API_CRUD_ORDER, this.order)
                .then((response) => {
                    this.submitButtonText = 'Успешно опубликован!'
                    this.order = initialEditedItem()
                    setTimeout(() => {
                      this.submitButtonText = 'Опубликовать заказ'
                      this.$router.push("/orders");
                    }, 1000);
                })
                .catch((err) => {
                    console.log(err.response.data);
                    this.submitButtonText = 'Опубликовать заказ'
                })
                .finally(() => (this.loading = false,
                                this.orderPublication = false));
            
        },
        updateOrder() {
            this.loading = true;
            this.orderPublication = true

            axios.put(API_CRUD_ORDER + '/' + this.order.id, this.order)
                .then((response) => {
                    this.submitButtonText = 'Успешно отредактирован!'
                    setTimeout(() => {
                      this.submitButtonText = 'Обновить заказ'
                    }, 1000);
                })
                .catch((err) => {
                    console.log(err.response.data);
                    this.submitButtonText = 'Обновить заказ'
                })
                .finally(() => (this.loading = false,
                                this.orderPublication = false));
            
        },
        addOneToServicesList(data = {}) {
            this.order.order_services.data.push({
                id: this.order.order_services.data.length + 1,
                name: (data.name ? data.name : ''),
                number: (data.number ? data.number : 1),
                sum: (data.sum ? data.sum : 0),
            })
        },
        delOneFromServicesList(index) {
            this.order.order_services.data.splice(index, 1)
            this.recheckNumbersInServicesList()
        },
        recheckNumbersInServicesList() {
            this.order.order_services.itog = 0
            this.order.order_services.data.forEach((service, index) => {
                service.id = index + 1
                this.order.order_services.itog += Number(service.sum * (service.number ? service.number : 1))
            })

            this.order.order_services.itog = this.order.order_services.itog.toFixed(2)

            this.order.itogsum_for_client = this.order.order_services.itog
        },    
    },
  }
</script>
<style scoped>
    .b-tender__info-item-subtitle {
        font-size: 1.2rem;
        color: rgb(54, 54, 54);
        opacity: 1;
        margin-bottom: 20px;
        border-bottom: 1px solid #ccccccc7;
        position: relative;
        z-index: 20;
    }
    .belena {
        position: absolute;
        width: 100%;
        height: 100%;
        background: #FFF;
        opacity: .4;
        z-index: 10;
    }
</style>