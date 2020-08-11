<template>
<div class="container">
	<div class="order__header">
		<div class="order__header__bg"></div>

        <div class="container px-5">
            <div class="row">
                <div class="col-sm-12 col-md-3 text-center text-md-left pt-3 mt-0 pt-md-4 mt-md-2">
                    <a href="/orders/"><img src="/img/cabinet/logo.png" class="img-fluid" alt="Gravescare"></a>
                </div>
                <div class="d-none d-md-block col-sm-12 col-md-6 order__header__settings__mainblock">
                </div>
                <div class="col-sm-12 col-md-3 text-center text-md-right pt-4 mt-1">
                    <button class="btn btn-success" @click="newOrder()">
                        Новая площадка <i class="far fa-plus-square ml-1"></i>
                    </button>
                </div>
            </div>
        </div><!--/container-->

    </div>
    <!--/order__header-->  

    <section class="mx-5">
        <div class="row my-3">
            <div class="col-sm-12 col-md-6">
                <div class="headUp text-muted">Мои площадки</div>
            </div>
            <div class="col-sm-12 col-md-6"></div>
        </div>

        <div class="card nobrd">
            <div class="card-body table-responsive p-0">
                <div class="progress" v-if="progress != -1">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" :style="`width: ${progress}%`"></div>
                </div>

                <table class="table table-striped table-hover table-borderless table-kupon mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Наименование</th>
                            <th>URL</th>
                            <th class="text-center">Статус</th>
                            <th class="text-center">Активна до</th>
                            <th class="text-center">Создана</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(order, index)  in orders.data" :key="order.id">

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
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer" v-if='orders.length >= limit'>
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
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Создание новой площадки</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="settingForm" novalidate>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="name">Наименование рекламной площадки</label>
                            <input type="text" required class="form-control" id="name" placeholder="Моя первая рекламная кампания" v-model="editOrder.name">
                        </div>
                    </div><!-- .form-row -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="url">Адрес сайта (без слеша на конце и протоколом вначале)</label>
                            <input type="text" required class="form-control" id="url" placeholder="https://domain.com" v-model="editOrder.url">
                        </div>
                    </div><!-- .form-row -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-success" @click="createOrder()">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="addingNew"></span>
                    Сохранить
                </button>
            </div>
            </div>
        </div>
    </div>
    <!-- End: Dialog Edit Order -->        
</div>        
</template>

<script>
  import {API_GET_ORDERS, API_CRUD_ORDER} from '../API'

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
        dialogES: '#editOrder',
        editIndex: -1,
        defaultEditOrder: {},
        editOrder: {
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
            can_delete: ''
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
        $(this.dialogES).on('hidden.bs.modal', function (e) {self.close();});

        // $('#allorders').DataTable();
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
                    this.orders.data.forEach(order => {
                        order.choice_edit = 0;
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
                    this.orders.data.forEach(order => {
                        order.choice_edit = 0;
                    });    
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.progress = -1));
        },
        close() {
            this.progress = -1;
            $(this.dialogES).modal('hide');

            this.editOrder = Object.assign({}, this.defaultEditOrder);

            this.editIndex = -1;
        },      
        newOrder() {
            $(this.dialogES).modal('show');
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
        statusTextColor(status) {
            let color;
            switch (status) {
                case 1:
                    color = "text-green"; 
                    break;
                default:
                    color = "text-danger"; 
                    break;
            }

            return "text-center " + color;
        }        
    },
  }
</script>
