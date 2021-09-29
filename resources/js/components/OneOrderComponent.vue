<template>
<div class="container">
    <section>
        <div class="row mt-5 mb-4">
            <div class="col-sm-12 col-md-6">
                <div class="headUp"><span class="mr-4">Тендер на заказ #{{order.id}}</span> <a href="/orders" class="btn btn-sm btn-secondary">Посмотреть все тендеры</a></div>
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
                    <button @click="changeStatus(3)" class="btn btn-sm btn-secondary mb-2" v-if="order.status != 3"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Вернуть прием заявок</button>
                    <button @click="changeStatus(4)" class="btn btn-sm btn-info mb-2" v-if="order.status != 4"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Исполнитель выбран!</button>
                    <button @click="changeStatus(9)" class="btn btn-sm btn-success mb-2" v-if="order.status != 9"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Заказ успешно завершен!</button>
                    <button @click="changeStatus('canceled')" class="btn btn-sm btn-danger mb-2" v-if="order.status != 'canceled'"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Заказ отменен</button>
                </div>
                <div class="text-center text-md-left" v-if="order.reacted && order.can_access">
                    <button disabled class="btn btn-sm btn-success">Вы уже откликнулись!</button>
                </div>
                <div class="text-center text-md-left" v-if="!order.reacted && order.can_access && order.status == 3">
                    <button @click="reactNow()" class="btn btn-success"><i class="fas fa-spinner fa-spin mr-2" v-if="order.virtual.reaction"></i>Откликнуться на заказ</button>
                </div>
                <div class="text-center text-md-left" v-if="!order.can_access && order.status == 3">
                    <a href="/register" class="btn btn-success">Зарегистрироваться и откликнуться!</a>
                </div>
            </div>
        </div>    

        <div class="mt-5">
            <div class="text-center">
                <a href="/orders" class="btn btn-lg btn-secondary">Посмотреть все тендеры</a>
            </div>
        </div>            
    </section><!--/main-->

    <footer-block></footer-block>
</div>
</template>

<script>
  import { API_CRUD_ORDER, API_SEND_ORDER_REACTION } from '../API'

  export default {
    components: {
    },
    props: {
        importedOrder: {
        type: Object,
        required: true
        }
    },    
    data() {
      return {
        mapOrder: '',
        order: {
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
            }
        },
        savingOrder: false,
        limit: 10,
        progress: -1,
        reloading: false,
        loadingTimer: 1000,
        search: "",
      }
    },
    watch: {
        order: {
            handler() {
            },
            deep: true,
            immediate: true,
        },        
    },
    computed: {
      // logMessages() {
      //     return this.$store.getters.getLogMessages
      // },
    },
    created() {
        this.order = Object.assign(this.order, this.importedOrder);
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
        console.log("order", this.order);
    },
    methods: {
        reloadOrder(orderId) {
            this.reloading = true;
            axios.get(API_CRUD_ORDER + '/' + orderId)
                .then(response => {
                    this.order = response.data;
                    this.loadStatistics();
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.reloading = false));
        },
        reactNow() {
            this.order.virtual.reaction = true;

            axios
                .get(API_SEND_ORDER_REACTION + '/' + this.order.id)
                .then(response => {
                    this.order.reacted = true;
                })
                .catch(err => console.log(err.response))

        },
        changeStatus(newStatus) {
            this.order.virtual.reaction = true;

            axios
                .put(API_CRUD_ORDER + '/' + this.order.id, {
                    "status": newStatus
                })
                .then(response => {
                    this.order.status = newStatus;
                    this.order.virtual.reaction = false;
                    this.order.status = response.data.status;
                    this.order.status_cssclass = response.data.status_cssclass;
                    this.order.status_stringify = response.data.status_stringify;
                })
                .catch(err => console.log(err.response))

        },
    },
  }
</script>
