<template>
<div class="container">
    <section>
        <div class="row mt-5 mb-4">
            <div class="col-sm-12 col-md-6">
                <div class="headUp">Каталог тендеров</div>
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

        <div class="card mb-3" v-for="(order, index)  in orders.data" :key="order.id">
            <div class="card-body">
                <div class="card-title">
                    <div :class="`tendercart-type__status-public ${order.status_cssclass}`" :id="`${order.id}`">{{order.status_stringify}}</div>
                    <div class="orderid__item-settings">
					    <span class="reference _public">Заказ #{{order.id}}</span>
					</div>                    
                    <div class="b-tender__block--title">
                        <div class="b-tender__title _wide">
                            <!-- <a :href="`/orders/page/${order.id}`">{{order.tarif_stringify}}</a> -->
                            <span>{{order.tarif_stringify}}</span>
                        </div>
                    </div>

                    <div class="b-tender__info mt-4" v-if="order.user_web_users_name || order.user_web_users_phone || order.user_web_users_email" style="border: 1px solid #ff0000; padding: 20px;">
                        <div class="b-tender__info-item" v-if="order.user_web_users_name">
                            <div class="b-tender__info-item-title">Имя заказчика:</div>
                            <div class="b-tender__info-item-text">{{order.user_web_users_name}}</div>
                        </div>
                        <div class="b-tender__info-item" v-if="order.user_web_users_phone">
                            <div class="b-tender__info-item-title">Телефон заказчика:</div>
                            <div class="b-tender__info-item-text">{{order.user_web_users_phone}}</div>
                        </div>
                        <div class="b-tender__info-item" v-if="order.user_web_users_email">
                            <div class="b-tender__info-item-title">Почта заказчика:</div>
                            <div class="b-tender__info-item-text">{{order.user_web_users_email}}</div>
                        </div>
				    </div>                    

                    <div class="row mt-4">
                        <div class="col-sm-12 col-md-6">
                            <div class="b-tender__info-item-subtitle">Состав заказа</div>
                            <div v-html="order.ordertxt_stringify"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div v-if="order.tarif == 'extended' || order.tarif == 'easy'">
                                <div class="b-tender__info-item-subtitle">Количество могил<br>в заказе</div>
                                <div class="b-tender__info-item-text">{{order.number_of_graves}}</div>
                            </div>
                            <div v-if="order.order_text_details">
                                <div class="b-tender__info-item-subtitle">Комментарий клиента</div>
                                <p>{{order.order_text_details}}</p>
                            </div>
                        </div>                        
                    </div>

                    <div class="b-tender__info mt-4">
                        <div class="b-tender__info-item" v-if="order.city.pagetitle">
                            <div class="b-tender__info-item-title">Город:</div>
                            <div class="b-tender__info-item-text">{{order.city.pagetitle}}</div>
                        </div>
                        <div class="b-tender__info-item" v-if="order.virtual.graveyard_name">
                            <div class="b-tender__info-item-title">Кладбище:</div>
                            <div class="b-tender__info-item-text">{{order.virtual.graveyard_name}}</div>
                            <!-- <small class="text-muted" v-if="order.virtual.map" @click="openOrderOnMap(index)"><i class="fas fa-map-marked-alt"></i> открыть на карте</small> -->
                        </div>
					    <div class="b-tender__info-item">
							<div class="b-tender__info-item-title">Опубликован</div>
							<div class="b-tender__info-item-text">{{order.updatetime}}</div>
                            <small class="text-muted">время по Москве</small>
						</div>
                        <div class="b-tender__info-item">
                            <div class="b-tender__info-item-title">Бюджет:</div>
                            <div class="b-tender__info-item-text">от {{order.itogsum}}&nbsp;<span class="rub _bold"></span></div>
                            <small class="text-muted">по согласованию</small>
                        </div>
				    </div>                    
                </div>
                <div class="card-text mb-3">
                    <small class="text-muted" v-if="!order.can_access">Контакт для связи с заказчиком доступен только зарегистрированным пользователям.</small>
                </div>
                <div class="text-center text-md-left mb-3" v-if="order.can_delete">
                    Смена статусов: 
                    <button @click="changeStatus(index, 3)" class="btn btn-sm btn-secondary mb-2" v-if="order.status != 3"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Вернуть прием заявок</button>
                    <button @click="changeStatus(index, 4)" class="btn btn-sm btn-info mb-2" v-if="order.status != 4"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Исполнитель выбран!</button>
                    <button @click="changeStatus(index, 9)" class="btn btn-sm btn-success mb-2" v-if="order.status != 9"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Заказ успешно завершен!</button>
                    <button @click="changeStatus(index, 'canceled')" class="btn btn-sm btn-danger mb-2" v-if="order.status != 'canceled'"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Заказ отменен</button>
                </div>
                <div class="text-center text-md-left" v-if="order.reacted && order.can_access">
                    <button disabled class="btn btn-sm btn-success">Вы уже откликнулись!</button>
                </div>
                <div class="text-center text-md-left" v-if="!order.reacted && order.can_access && order.status == 3">
                    <button @click="reactNow(index)" class="btn btn-success"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Откликнуться на заказ</button>
                </div>
                <div class="text-center text-md-left" v-if="!order.can_access && order.status == 3">
                    <a href="/register" class="btn btn-success mb-2 mx-2">Зарегистрироваться и откликнуться!</a> или <a href="/" class="btn btn-primary mb-2 mx-2">Войти и откликнуться!</a>
                </div>
            </div>
        </div>                
                        <!-- <tr v-for="(order, index)  in orders.data" :key="order.id">

                            <td class="text-center">{{order.id}}</td>
                            <td><b><a :href="`/orders/page/${order.id}`">{{order.name}}</a></b></td>
                            <td><a :href="order.url" target="_blank">{{order.url}} <i class="fas fa-external-link-alt"></i></a></td>
                            <td :class='statusTextColor(order.status)'>{{order.status_stringify}}</td>
                            <td class="text-center">{{order.paid_till_formated}} <span class="text-danger ml-1" v-if="!order.is_paied" title="Необходимо оплатить"><i class="fas fa-exclamation-triangle"></i></span></td>
                            <td class="text-center">{{order.created_at}}</td>

                            <td style="text-align:center; vertical-align: middle;">
                                <a class="btn btn-info" :href="`/orders/page/${order.id}`">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <button class="btn btn-danger ml-1" @click="deleteRecord(index)" v-if='order.can_delete'>
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr> -->

        <div class="card nobrd">
            <div class="card-footer">
                <div class="overflow-auto">
                    <pagination
                        class="mb-0"
                        :data="orders"
                        @pagination-change-page="getResults"
                        :limit="limit"
                        :show-disabled="showDisabled"
                        :size="size"
                        :align="align" />
                </div>
            </div>
        </div>

    </section>

    <footer-block></footer-block>

    <!-- Dialog Edit Order -->
    <div class="modal fade" id="editOrder" tabindex="-1" role="dialog" aria-labelledby="editOrderTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Заказ №{{editOrder.id}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div style="width: 100%; height: 400px;">
                            <div id="yandex-map-order" style="width: 100%; height: 400px;" class="yandex-map ltr" data-lang="ru_RU"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <!-- <button type="button" class="btn btn-success" @click="createOrder()">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="addingNew"></span>
                    Сохранить
                </button> -->
            </div>
            </div>
        </div>
    </div>
    <!-- End: Dialog Edit Order -->        
</div>        
</template>

<script>
  import {API_GET_ORDERS, API_CRUD_ORDER, API_SEND_ORDER_REACTION} from '../API'

  export default {
    components: {
    },
    data() {
      return {
        orders: {},
        limit: 10,
        showDisabled: false,
        size: 'default',
        align: 'center',
        progress: -1,
        addingNew: false,
        loadingTimer: 1000,
        search: "",
        dialogEO: '#editOrder',
        editIndex: -1,
        defaultEditOrder: {},
        mapOrder: '',
        placemark: '',
        editOrder: {
            id: '',
            ordertxt_stringify: '',
            order_city_id: '', 
            order_object_id: '', 
            order_object_name_ext: '', 
            order_text_details: '', 
            city: {},
            graveyard: {},
            number_of_graves: '',
            itogsum: '',
            tarif: '',
            tarif_stringify: '',
            status: '', 
            status_stringify: '',
            status_cssclass: '',
            can_access: false,
            reacted: false,
            react: [],
            can_delete: false,
            virtual: {
                graveyard_name: '',
                choice_edit: 0,
                reaction: false,
                map: false,
            },
            opened_order: 0
        },        
      }
    },
    computed: {
      // logMessages() {
      //     return this.$store.getters.getLogMessages
      // },
    },
    mounted() {
      // Прогрузим первичные данные
      this.getResults();

        let self = this;
        $(this.dialogEO).on('hidden.bs.modal', function (e) {self.close();});

    },
    created() {
    },
    methods: {
        getResults(page = 1) {
            this.progress = 15;
            axios.get(API_GET_ORDERS + '?page=' + page)
                .then(response => {
                    // console.log(response);
                    this.progress = 85;
                    setTimeout(() => {
                        this.progress = -1;
                    }, this.loadingTimer);
                    this.orders = response.data;
                    this.orders.data.forEach((order, index) => {
                        this.$set(this.orders.data[index], 'virtual', {});
                        this.$set(this.orders.data[index].virtual, 'reaction', false);
                        this.$set(this.orders.data[index].virtual, 'choice_edit', 0);
                        this.$set(this.orders.data[index].virtual, 'graveyard_name', "");
                        this.$set(this.orders.data[index].virtual, 'map', false);
                        this.$set(this.orders.data[index].virtual, 'grave_lat', '');
                        this.$set(this.orders.data[index].virtual, 'grave_long', '');
                        if (!order.order_object_name_ext && order.graveyard) {
                            order.virtual.graveyard_name = order.graveyard.pagetitle;
                        } else if (order.order_object_name_ext) {
                            order.virtual.graveyard_name = order.order_object_name_ext;
                        }

                        if (order.graveyard && order.graveyard.introtext) {
                            let coords = order.graveyard.introtext.split(",");
                            this.orders.data[index].virtual.map = true;
                            this.orders.data[index].virtual.grave_lat = coords[0];
                            this.orders.data[index].virtual.grave_long = coords[1];
                        }
                    });
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.progress = -1));
        },
        getSearchResults(page = 1) {
            this.progress = 15;
            axios.get('/site/search?query=' + this.search + '&page=' + page)
                .then(response => {
                    // console.log(response);
                    this.progress = 85;
                    setTimeout(() => {
                        this.progress = -1;
                    }, this.loadingTimer);
                    this.orders = response.data;
                    this.orders.data.forEach((order,index) => {
                        this.$set(this.orders.data[index], 'virtual', {});
                        this.$set(this.orders.data[index].virtual, 'reaction', false);
                        this.$set(this.orders.data[index].virtual, 'choice_edit', 0);
                        this.$set(this.orders.data[index].virtual, 'graveyard_name', "");
                        this.$set(this.orders.data[index].virtual, 'map', false);
                        this.$set(this.orders.data[index].virtual, 'grave_lat', '');
                        this.$set(this.orders.data[index].virtual, 'grave_long', '');
                        if (!order.order_object_name_ext && order.graveyard) {
                            order.virtual.graveyard_name = order.graveyard.pagetitle;
                        } else if (order.order_object_name_ext) {
                            order.virtual.graveyard_name = order.order_object_name_ext;
                        }

                        if (order.graveyard && order.graveyard.introtext) {
                            let coords = order.graveyard.introtext.split(",");
                            this.orders.data[index].virtual.map = true;
                            this.orders.data[index].virtual.grave_lat = coords[0];
                            this.orders.data[index].virtual.grave_long = coords[1];
                        }
                    });    
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.progress = -1));
        },
        close() {
            this.progress = -1;
            $(this.dialogEO).modal('hide');

            this.editOrder = Object.assign({}, this.defaultEditOrder);

            this.editIndex = -1;
        },      
        newOrder() {
            $(this.dialogEO).modal('show');
        },         
        createOrder() {
            this.addingNew = true;
 
            axios
                .post(API_CRUD_ORDER,
                this.editOrder)
                .then(response => {
                    console.log("response", response.data);
                    this.orders.data.unshift(response.data);
                    this.orders.total++;
                    this.close();
                })
                .catch(err => console.log(err.response))
                .finally(() => (this.addingNew = false))
        },
        deleteRecord(index) {
            confirm('Вы уверены что хотите удалить эту платформу?') &&
            axios
                .delete(API_CRUD_ORDER + '/' + this.orders.data[index].id)
                .then(res => {
                    this.orders.data.splice(index, 1);
                    this.orders.total--;
                })
                .catch(err => console.log(err.response))
        },
        reactNow(index) {
            this.orders.data[index].virtual.reaction = true;

            axios
                .get(API_SEND_ORDER_REACTION + '/' + this.orders.data[index].id)
                .then(response => {
                    this.orders.data[index].reacted = true;
                    this.orders.data[index].virtual.reaction = false;
                })
                .catch(err => console.log(err.response))
                .finally(() => (this.orders.data[index].reacted = true))

        },
        changeStatus(index, newStatus) {
            this.orders.data[index].virtual.reaction = true;

            axios
                .put(API_CRUD_ORDER + '/' + this.orders.data[index].id, {
                    "status": newStatus
                })
                .then(response => {
                    this.orders.data[index].status = newStatus;
                    this.orders.data[index].virtual.reaction = false;
                    this.orders.data[index].status = response.data.status;
                    this.orders.data[index].status_cssclass = response.data.status_cssclass;
                    this.orders.data[index].status_stringify = response.data.status_stringify;
                })
                .catch(err => console.log(err.response))

        },
        openOrderOnMap(index) {
            this.editOrder = Object.assign({}, this.orders.data[index]);
            $(this.dialogEO).modal('show');

            $(this.dialogEO).on('shown.bs.modal', function() {
                this.mapOrder.container.fitToViewport();
                // this.placemark.geometry.setCoordinates([this.editOrder.virtual.grave_lat, this.editOrder.virtual.grave_long]);
                // this.mapOrder.setCenter(new YMaps.GeoPoint([this.editOrder.virtual.grave_lat, this.editOrder.virtual.grave_long]), 10);
            });            

            // this.orderMapInit(this.editOrder.graveyard.introtext);
            // this.mapOrder.container.fitToViewport();
        },
        orderMapInit() {
            this.mapOrder = new ymaps.Map('yandex-map-order', {
                center: [55.755768, 37.617671],
                zoom: 10
            }, {
                // При сложных перестроениях можно выставить автоматическое
                // обновление карты при изменении размеров контейнера.
                // При простых изменениях размера контейнера рекомендуется обновлять карту программно.
                autoFitToViewport: 'always'
                // searchControlProvider: 'yandex#search'
            });

            // Создаем точку.
            this.placemark = new ymaps.Placemark([this.editOrder.virtual.grave_lat, this.editOrder.virtual.grave_long], {
                iconContent: this.editOrder.virtual.graveyard_name,
            }, {
                preset: 'islands#redStretchyIcon',
                draggable: true,
            });

            this.mapOrder.geoObjects.add(placemark);
        }
    },
  }
</script>
