<template>
<div class="container">
    <section>
        <div class="row mt-5 mb-4">
            <div class="col-sm-12 col-md-6">
                <div class="headUp">Создать новый заказ</div>
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
                        <div class="mt-3">
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
                        </div>
                        <div class="mt-3">
                            <div class="b-tender__info-item-subtitle">Заказчик</div>
                            <div class="row">
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
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="b-tender__info-item-subtitle">Месторасположение захоронения</div>
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
                                        <label>Наименование кладбища</label>
                                        <input type="text" class="form-control" v-model="order.order_object_name_ext" :disabled="loading">
                                        <small class="form-text text-muted">Поле заполняется, если кладбище отсутствует в списке</small>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
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
                        <div class="mt-3">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-4">
                                    <label>Количество могил в заказе</label>
                                    <input type="number" class="form-control" v-model="order.number_of_graves" :disabled="loading" style="max-width: 70px">
                                    
                                    <label class="mt-2">Стоимость услуг</label>
                                    <input type="number" class="form-control" v-model="order.itogsum" :disabled="loading" required style="max-width: 100px">

                                    <div class="custom-control custom-switch mt-3">
                                        <input type="checkbox" class="custom-control-input" id="openedOrderSwitch" v-model="order.opened_order">
                                        <label class="custom-control-label" for="openedOrderSwitch">Открытый тендер <br><small class="form-text text-muted">(контакты доступны всем)</small></label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-8">
                                    <label>Опишите детали поиска места захоронения и выполнения услуг</label>
                                    <textarea class="form-control" rows="5" v-model="order.order_text_details" :disabled="loading"></textarea>
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

    </section><!--/main-->

    <footer-block></footer-block>
</div>
</template>

<script>
    import { API_CRUD_ORDER, API_ORDER_SERVICES_LIST, API_CITIES, API_GRAVEYARDS } from '../API'

    const initialEditedItem = () => ({
        id: '',
        user_web_users_id: 1,
        user_web_users_name: '',
        user_web_users_phone: '',
        user_web_users_email: '',
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
        tarif: 'individual',
        status: 3, 
        virtual: {
            graveyard_name: '',
        }
    })

  export default {
    components: {
    },
    data() {
      return {
        mapOrder: '',
        order: {
        },
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
        orderPublication: false
      }
    },
    watch: {
        order: {
            handler() {
            },
            deep: true,
            immediate: true,
        },
        "order.order_city_id"(city_id) {
            this.loadGraveYards(city_id)
        }
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
    },
    methods: {
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
            this.createOrder()
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
                    }, 1000);
                })
                .catch((err) => {
                    console.log(err.response.data);
                    this.submitButtonText = 'Опубликовать заказ'
                })
                .finally(() => (this.loading = false,
                                this.orderPublication = false));
            
        }
    },
  }
</script>
<style scoped>
    .b-tender__info-item-subtitle {
        font-size: 1.2rem;
        color: rgb(54, 54, 54);
        opacity: 1;
    }
</style>