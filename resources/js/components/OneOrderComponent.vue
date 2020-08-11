<template>
<div class="container">
	<div class="order__header">
		<div class="order__header__bg"></div>

        <div class="openNavButton">
            <button class="btn btn-danger" onclick="openNav()"><i class="fas fa-bars bar"></i></button>
        </div>
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="/orders">Мои площадки</a>
            <a :class="activeMenu=='main' ? 'active':''" @click="changeMenu('main')" href="#">Рекламная кампания</a>
            <a :class="activeMenu=='promo-codes' ? 'active':''" @click="changeMenu('promo-codes')" href="#">Промо-коды</a>
            <a :class="activeMenu=='statistics' ? 'active':''" @click="changeMenu('statistics')" href="#">Статистика</a>
            <a :class="activeMenu=='payment' ? 'active':''" @click="changeMenu('payment')" href="#">Оплата <span class="text-danger ml-1" v-if="!order.is_paied" title="Необходимо оплатить"><i class="fas fa-exclamation-triangle"></i></span></a>
            <a :class="activeMenu=='widget' ? 'active':''" @click="changeMenu('widget')" href="#">Код виджета</a>
            <a href="#">Поддержка</a>
            <div class="productVersion">Версия 1.15</div>
        </div>

        <div class="container px-5">
            <div class="row">
                <div class="col-sm-12 col-md-3 text-center text-md-left pt-3 mt-0 pt-md-4 mt-md-2">
                    <a href="/orders/"><img src="/img/cabinet/logo.png" class="img-fluid" alt="Gravescare"></a>
                </div>
                <div class="d-none d-md-block col-sm-12 col-md-6 order__header__settings__mainblock">
                    <div class="order__header__settings">
                        <div class="order__header__openSettings" @click="openSettings()" v-if="!checkOrderFullness">
                            Настройте вашу кампанию 
                            <button class="btn btn-sm btn-danger btn-fly-top-right"><i class="fas fa-exclamation"></i></button>
                        </div>
                        <div class="order__header__openSettings good" @click="openSettings()" v-if="checkOrderFullness">
                            Данные кампании заполнены
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 text-center text-md-right pt-2 mt-0 pt-md-3 mt-md-2">
                    <div class="order__header__info">ID площадки: {{order.id}}</div>
                    <div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="customSwitch1" 
                                    :checked='order.status' 
                                    :disabled="!order.is_paied" 
                                    :title="order.status==1 ? 'Оплачено до':'Не оплачено'"
                                    @click='changeStatusNow()'>
                            <label class="custom-control-label" for="customSwitch1" v-if="order.status==1">до {{order.paid_till_formated}}</label>
                            <label class="custom-control-label" for="customSwitch1" v-if="order.status==0">
                                <span v-if="order.status==0 && !order.is_paied">{{order.status_stringify}}</span>
                                <span v-if="order.status==0 && order.is_paied">до {{order.paid_till_formated}}</span>
                            </label>
                            <span class="head-payment" v-if="!order.is_paied"><a @click="changeMenu('payment')" class="btn btn-light ml-1" href="#" title="Оплатить"><i class="fas fa-dollar-sign"></i></a></span>
                        </div>                    
                    </div>
                </div>
            </div>
        </div><!--/container-->
        <div class="order__settings__formarea">
            <div class="order__settings__form">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <input type="text" v-model="order.name" class="form-control" placeholder="Успешная кампания #1">
                                <small class="form-text text-muted">Название кампании, его видите только вы.</small>
                            </div>
                        </div><!--/col-->
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <input type="text" v-model="order.url" class="form-control" placeholder="https://сайт.ru">
                                <small class="form-text text-muted">Адрес сайта, для которого создается кампания</small>
                            </div>
                        </div><!--/col-->
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" v-model="order.name_visible" class="form-control" placeholder="Скидка до 50% на новый велосипед!">
                                <small class="form-text text-muted">Название кампании, отоброжающееся для ваших посетителей</small>
                            </div>
                        </div><!--/col-->
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <textarea v-model="order.description" rows="6" class="form-control" placeholder=""></textarea>
                                <small class="form-text text-muted">Описание сути и плюсов вашей реферальной кампании для посетителей сайта</small>
                            </div>
                        </div><!--/col-->
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <div class="mb-2">
                                    <b class="text-white mr-2">Ограничить показ набором страниц</b> <button class="btn btn-sm btn-secondary" @click='addAdditionalURL()'>Добавить урл</button>
                                </div>
                                <div class="row">
                                    <div v-for="(url, index) in order.additional_urls" class="col-sm-12 col-md-6">
                                        <div class="form-inline row mb-2">
                                            <input type="text" class="form-control form-control-sm col-sm-9 ml-3 mr-1" placeholder="/catalog.html или /catalog" v-model="order.additional_urls[index]">
                                            <button class="btn btn-sm btn-secondary col-sm-2" @click='deleteUrl(index)'><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Если необходимо ограничить показ реферальной кампании определенными URL адресами - добавьте их здесь. Если URL не заданы - кампания будет работать на всех страницах сайта. Указывайте URL без наименования сайта.</small>
                            </div>
                        </div><!--/col-->
                    </div><!--/row-->
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-success" @click='updateOrder()'><i class="fas fa-spinner fa-spin mr-2" v-if="savingOrder"></i>Сохранить настройки</button>
                        </div>
                    </div><!--/row-->
                </div>
            </div><!--/order__settings__form-->
        </div>
        <!--/order__settings__formarea-->    

    </div>
    <!--/order__header-->

    <section class="mb-3 order__statistic" v-if="activeMenu=='main' || activeMenu=='statistics'">
        <div class="row">
            <div class="col-sm-12 text-left text-md-center">
                <span class="d-inline-block my-2 mx-2"><span class="badge badge-pill badge-warning stat-pill mr-2"><i class="fas fa-spinner fa-spin" v-if="linksFollowedLoading"></i><span v-if="!linksFollowedLoading">{{statistics.linksFollowed}}</span></span> Переходов по ссылкам</span>
                <span class="d-inline-block my-2 mx-2"><span class="badge badge-pill badge-success stat-pill mr-2"><i class="fas fa-spinner fa-spin" v-if="openedKuponsLoading"></i><span v-if="!openedKuponsLoading">{{statistics.openedKupons}}</span></span> Отдано промо-кодов</span>
                <span class="d-inline-block my-2 mx-2"><span class="badge badge-pill badge-info stat-pill mr-2">{{statistics.leftKupons}}</span> Промо-кодов осталось</span>
            </div>
        </div>
    </section><!--/order__statistic-->

    <section class="main" v-if="activeMenu=='main'">
        <section class="mx-0 mx-md-5 mb-3 order__box__settings">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="headUp text-muted">Настройка вознаграждений и условий</div>
                    <small class="text-muted">Кликните на любой текст или ссылку для редактирования.</small>
                </div>
                <div class="col-sm-12 col-md-6"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-center my-5" v-if="order.boxes.length == 0">
                        <button class="btn btn-success" @click='createDefaultBoxes(order.id)'>Создать бонусные боксы</button>
                    </div>

                    <kupons-popup
                        :box='order.boxes[selectedBoxId]'
                        @updateKuponsInBox='updateKuponsInBox'
                        @offKuponsPopup='offKuponsPopup' />                

                    <template v-if="order.boxes.length > 0">
                        <div class="ref-row" style="margin: 15px 0 auto; width: 100%;">
                            <div v-for="(box, index) in order.boxes" :key="box.id"
                                    :class="`ref-row-item ref-row-item-${box.index} ref-c3`" style="opacity:1;">
                                <div class="ref-row-box" style="padding-top: 20px;">
                                    <div class="ref-box-image"></div>
                                    <div class="ref-box-way" 
                                        v-if="!editBoxWay[index]" 
                                        @click='openBox(index, "editBoxWay")'>
                                        {{box.way}}
                                    </div>
                                    <div class="mb-3 mt-3" v-if="editBoxWay[index]">
                                        <div class="form-inline">
                                            <input type="text" class="form-control form-control-sm mr-1 ml-4 input-boxes" v-model="box.way">
                                            <button class="btn btn-sm btn-success mr-1" @click='saveBox(index, "editBoxWay")'><i class="fas fa-check saverBtn"></i></button>
                                            <button class="btn btn-sm btn-secondary" @click='cancelBoxEdit(index, "editBoxWay")'><i class="fas fa-times"></i></button>
                                        </div>
                                        <div><small id="emailHelp" class="form-text text-muted">Например, 5% или 500 рублей.</small></div>
                                    </div>
                                    <div class="ref-box-way-name" 
                                        v-if="!editBoxWayName[index]" 
                                        @click='openBox(index, "editBoxWayName")'>{{box.way_name}}</div>
                                    <div class="mb-3 mt-1" v-if="editBoxWayName[index]">
                                        <div class="form-inline">
                                            <input type="text" class="form-control form-control-sm mr-1 ml-4 input-boxes" v-model="box.way_name">
                                            <button class="btn btn-sm btn-success mr-1" @click='saveBox(index, "editBoxWayName")'><i class="fas fa-check saverBtn"></i></button>
                                            <button class="btn btn-sm btn-secondary" @click='cancelBoxEdit(index, "editBoxWayName")'><i class="fas fa-times"></i></button>
                                        </div>
                                        <div><small id="emailHelp" class="form-text text-muted">С большой буквы, например: Скидка, Бонусы и т.д.</small></div>
                                    </div>
                                    <div class="ref-box-description" 
                                        v-if="!editBoxWayDescription[index]" 
                                        @click='openBox(index, "editBoxWayDescription")'>{{box.way_description}}</div>
                                    <div class="mb-3 mt-2" v-if="editBoxWayDescription[index]">
                                        <div class="form-inline">
                                            <input type="text" class="form-control form-control-sm mr-1 ml-4 input-boxes" v-model="box.way_description">
                                            <button class="btn btn-sm btn-success mr-1" @click='saveBox(index, "editBoxWayDescription")'><i class="fas fa-check saverBtn"></i></button>
                                            <button class="btn btn-sm btn-secondary" @click='cancelBoxEdit(index, "editBoxWayDescription")'><i class="fas fa-times"></i></button>
                                        </div>
                                        <div><small id="emailHelp" class="form-text text-muted">Опишите ваше предложение.</small></div>
                                    </div>

                                    <div class="ref-box-itog" 
                                        v-if="!editBoxGrad[index]" 
                                        @click='openBox(index, "editBoxGrad")'>{{box.way_name}} <span class="ref-itog-way">{{box.way}}</span><br>за <span class="ref-blue">{{box.grad}} друзей</span></div>
                                    <div class="mb-3 mt-2" v-if="editBoxGrad[index]">
                                        <div class="form-inline">
                                            <input type="number" class="form-control form-control-sm mr-1 ml-4 input-boxes" v-model="box.grad">
                                            <button class="btn btn-sm btn-success mr-1" @click='saveBox(index, "editBoxGrad")'><i class="fas fa-check saverBtn"></i></button>
                                            <button class="btn btn-sm btn-secondary" @click='cancelBoxEdit(index, "editBoxGrad")'><i class="fas fa-times"></i></button>
                                        </div>
                                        <div class="pb-2"><small id="emailHelp" class="form-text text-muted">Введите количество друзей для достижения цели.</small></div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <template v-if="box.kupons.length > 0 && !box.nokupons">
                                        <button class="btn btn-success btn-special" @click="openKuponsEdit(index)">Промо-коды заданы</button>
                                    </template>
                                    <template v-if="box.kupons.length > 0 && box.nokupons">
                                        <button class="btn btn-warning btn-special" @click="openKuponsEdit(index)">Промо-коды закончились</button>
                                    </template>
                                    <template v-if="box.kupons.length == 0">
                                        <button class="btn btn-danger btn-special" @click="openKuponsEdit(index)">Задайте промо-коды</button>
                                    </template>
                                </div>
                            </div><!--/ref-row-item-->
                        </div><!--/ref-row-->
                    </template>
                </div>
            </div>
        </section><!--/order__box__settings-->
    </section><!--/main-->

    <section class="promo-codes mx-5" v-if="activeMenu=='promo-codes'">
        <kupons-list
            :order='order'
            @updateOrderEmit='updateOrderEmit'
        ></kupons-list>
    </section><!--/promo-codes-->

    <section class="statistics mx-5" v-if="activeMenu=='statistics'">
        <statistics-order
            :order='order'
        ></statistics-order>
    </section><!--/statistics-->

    <section class="payment mx-5" v-if="activeMenu=='payment'">
        <payment-order 
            :id='order.id'
        ></payment-order>
    </section><!--/payment-order-->

    <section class="widget mx-5" v-if="activeMenu=='widget'">
        <widget-code 
            :id='order.id'
        ></widget-code>
    </section><!--/widget-->

    <footer-block></footer-block>

    <!-- <div class="card"> -->
        <!-- <div class="card-header">
            <div class="row">
                <div class="col-auto"><a href="/orders" class="btn btn-sm btn-secondary">&laquo; Назад</a></div>
                <div class="col-auto pt-1"><h5>Площадка #{{order.id}}</h5></div>
                <div class="col text-right">
                    <button class="btn btn-sm btn-secondary" @click="reloadOrder(order.id)">
                        <i class="fas fa-sync-alt" v-if="!reloading"></i>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="reloading"></span>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- <div class="card-footer">
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
        </div> -->
    <!-- </div> -->
</div>
</template>

<script>
  import { API_CRUD_ORDER } from '../API'

  const initialBoxItem = () => ({
    id: '',
    order_id: '',
    index: '',
    grad: '',
    way: '',
    way_name: '',
    way_description: '',
    secret_code: '',
    number_of_openings: '',
    kupons: [],
  });

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
        order: {
            id: '',
            url: '', 
            name: '', 
            name_visible: '', 
            description: '', 
            additional_urls: '',
            is_paied: '',
            user_id: '', 
            status: '', 
            paid_till: '',
            paid_till_formated: '', 
            status_stringify: '', 
            can_delete: '',
            boxes: [],
            followers: []
        },
        savingOrder: false,
        activeMenu: "main",
        limit: 10,
        showDisabled: false,
        size: 'default',
        align: 'center',
        progress: -1,
        reloading: false,
        loadingTimer: 1000,
        search: "",
        checkOrderFullness: false,
        linksFollowedLoading: true,
        openedKuponsLoading: true,
        editedBox: initialBoxItem(),
        editBoxWay: {
            0: false, 
            1: false, 
            2: false, 
            3: false
        },
        editBoxWayName: {
            0: false, 
            1: false, 
            2: false, 
            3: false
        },
        editBoxWayDescription: {
            0: false, 
            1: false, 
            2: false, 
            3: false
        },
        editBoxGrad: {
            0: false, 
            1: false, 
            2: false, 
            3: false
        },
        dialogKupons: '#kuponsPopup',
        selectedBoxId: 0,
        statistics: {
            linksFollowed: 0,   // Кликов по ссылкам переходов
            openedKupons: 0,    // Открытые купоны
            leftKupons: 0,      // Оставшиеся купоны
        }
      }
    },
    watch: {
        order: {
            handler() {
                this.checkOrderFullness = this.orderFullness();
                this.countLeftKupons();
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

        if (this.orderFullness() === false) {
            this.openSettings();
        }

        console.log("order", this.order);

        this.loadStatistics();
    },
    methods: {
        orderFullness() {
            if (!this.order.url || !this.order.name || !this.order.name_visible || !this.order.description) {
                return false;
            } 

            return true;
        },
        openSettings() {
            $('.order__settings__formarea').toggleClass('hidden');
        },
        getResults(page = 1) {
            // this.progress = 15;
            // axios.get(API_GET_orders + '?page=' + page)
            //   .then(response => {
            //       // console.log(response);
            //       this.progress = 85;
            //       setTimeout(() => {
            //           this.progress = -1;
            //       }, this.loadingTimer);
            //       this.orders = response.data;
            //       this.orders.data.forEach(site => {
            //           site.choice_edit = 0;
            //       });
            //   })
            //   .catch(err => console.log(err.response.data))
            //   .finally(() => (this.progress = -1));
        },
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
        changeStatusNow() {
            this.savingOrder = true;
            let neworderstatus = (this.order.status==1 ? 0:1);

            axios
                .put(API_CRUD_ORDER + '/' + this.order.id,
                {
                    'status': neworderstatus,
                    'url': this.order.url
                })
                .then(response => {
                    this.order.status = response.data.status;
                })
                .catch(err => console.log(err.response))
                .finally(() => (this.savingOrder = false))            
        },

        offKuponsPopup() {
            $(this.dialogKupons).modal('hide');
        },
        openKuponsEdit(boxIndex) {
            this.selectedBoxId = boxIndex; 
            $(this.dialogKupons).modal('show');
        },
        updateKuponsInBox(kupons) {
            this.order.boxes[this.selectedBoxId].kupons = kupons;
            // this.offKuponsPopup();
            this.checkBoxesOnKupons();
        },
        updateOrderEmit(order) {
            this.order = Object.assign(this.order, order);
            this.checkBoxesOnKupons();
        },
        checkBoxesOnKupons() {
            this.order.boxes.forEach(box => {
                let kuponSumOfCount = 0;
                box.nokupons = true;
                box.kupons.forEach(kupon => {
                    kuponSumOfCount += kupon.count;
                });
                if (kuponSumOfCount > 0) {
                    box.nokupons = false;
                }
            });
        },
        saveBtnToggler() {
            $('.saverBtn').toggleClass("fas fa-check");
            $('.saverBtn').toggleClass("fas fa-spinner fa-spin");
        },
        closeAllEditors() {
            for (var prop in this.editBoxWay) {
                this.editBoxWay[prop] = false
            }            
            for (var prop in this.editBoxWayName) {
                this.editBoxWayName[prop] = false
            }            
            for (var prop in this.editBoxWayDescription) {
                this.editBoxWayDescription[prop] = false
            }            
            for (var prop in this.editBoxGrad) {
                this.editBoxGrad[prop] = false
            }            
        },
        openBox(boxIndex, objName) {
            this.closeAllEditors();
            this.editedBox = Object.assign({}, this.order.boxes[boxIndex]);
            this[objName][boxIndex] = true;
        },
        saveBox(boxIndex, objName) {
            this.saveBtnToggler();
            const box = this.order.boxes[boxIndex];
            axios.put(API_BOXES_RESOURCE + '/' + box.id, box)
                .then(response => {
                    this.editedBox = initialBoxItem();
                    this[objName][boxIndex] = false;
                    this.saveBtnToggler()
                    console.log(`Box ${box.id} saved!`);
                })
                .catch(err => console.log(err.response.data))
        },
        cancelBoxEdit(boxIndex, objName) {
            this.order.boxes[boxIndex] = Object.assign(this.order.boxes[boxIndex], this.editedBox);
            this.editedBox = initialBoxItem();
            this[objName][boxIndex] = false;
        },
        createDefaultBoxes(orderId) {
            this.progress = 15;
            axios.get(API_CRUD_ORDER + '/' + orderId)
                .then(response => {
                    this.progress = 85;
                    this.order.boxes = response.data;
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.progress = -1));
        },
        addAdditionalURL(index) {
            if (!this.order.additional_urls) {
                this.order.additional_urls = [];
            }
            this.order.additional_urls.push("");
        },
        deleteUrl(index) {
            this.order.additional_urls.splice(index, 1);
        },
        updateOrder() {
            this.savingOrder = true;

            if (!this.order.additional_urls) {
                this.order.additional_urls = [];
            }

            this.order.additional_urls.forEach((url,index) => {
                if (!url) {
                    this.order.additional_urls.splice(index, 1);
                }
            });

            axios
                .put(API_CRUD_ORDER + '/' + this.order.id,
                this.order)
                .then(response => {
                    if (this.orderFullness() === false) {
                        this.openSettings();
                    }                    
                })
                .catch(err => console.log(err.response))
                .finally(() => (this.savingOrder = false))
        },        
        deleteRecord(index) {
            confirm('Вы уверены что хотите удалить эту строку?') &&
            axios
                .delete('/site/' + this.orders.data[index].id)
                .then(res => {
                this.orders.data.splice(index, 1)
                this.orders.total--
                })
                .catch(err => console.log(err.response))
                .finally(() => (this.loading = false))
        },
        changeMenu(menu) {
            this.activeMenu = menu;
        },
        countLeftKupons() {
            this.statistics.leftKupons = 0;

            this.order.boxes.forEach(box => {
                box.kupons.forEach(kupon => {
                    this.statistics.leftKupons += parseInt(kupon.count);
                });
            });
        },
        loadedStat() {
            this.linksFollowedLoading = false;
            this.openedKuponsLoading = false;
        },
        loadStatistics() {
            this.linksFollowedLoading = true;
            this.openedKuponsLoading = true;

            axios.get(API_GET_STAT_NUMBERS + '/' + this.order.id)
                .then(response => {
                    this.statistics.linksFollowed = parseInt(response.data.linksFollowed);
                    this.statistics.openedKupons = parseInt(response.data.openedKupons);
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.loadedStat()));

        }
    },
  }
</script>
