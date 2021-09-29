<template>
<div class="container">
    <section>
        <div class="row my-4" data-html2canvas-ignore="true">
            <div class="col-sm-12 col-md-6"></div>
            <div class="col-sm-12 col-md-3">
                <b>Отправить:</b> <div class="ya-share2" data-curtain data-services="vkontakte,odnoklassniki,telegram,viber,whatsapp,skype"></div>
            </div>
            <div class="col-sm-12 col-md-3">
                <!-- <b>Сохранить:</b> <div><button class="btn btn-sm btn-secondary" @click="printDiv('Заказ-наряд #' + order.id)">Сохранить в PDF</button></div> -->
                <b>Сохранить:</b> <div><button class="btn btn-sm btn-secondary" @click="downloadPdf()">Сохранить в PDF</button></div>
            </div>
        </div>
        <div class="card mt-3 mb-3">
            <div class="card-body" id="contentPrint" ref="content">
                <div class="row mb-4">
                    <div class="col-sm-12 col-md-6">
                        <template v-if="param == ''">
                            <div class="headUp text-center mt-5 pt-4"><span>ЗАКАЗ-НАРЯД #{{order.id}}</span></div>
                        </template>
                        <template v-if="param == 'act'">
                            <div class="headUp text-center mt-5 pt-4"><span>АКТ О ВЫПОЛНЕННЫХ РАБОТАХ <br>ПО ЗАКАЗУ #{{order.id}}</span></div>
                        </template>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <p>
                            <b>Исполнитель:</b> ИП «ФОМИЧЕВ МИХАИЛ СЕРГЕЕВИЧ»,<br>
                            ИНН 761026106748, ОГРНИП 316502400065421<br>
                            Телефон: <a href="tel:+79955775222">+7 (995) 577-52-22</a><br>
                            E-mail: <a href="mailto:info@gravescare.com">info@gravescare.com</a>
                        </p>
                        <p>
                            <b>Дата и время заказа:</b> {{ order.updatetime }}<br>
                            <b>Намеченная дата начала работ:</b> {{ (order.start_date_of_work || '-') | formattedDate }}<br>
                            <b>Дней на исполнение заказа:</b> {{ order.oriental_days_for_work || '-' }}<br>

                            <!-- <template v-if="param == 'act'">
                                <b>Дата и время окончания работ:</b> {{ order.datetime_of_client_signed_the_end | formattedDate }}
                            </template> -->

                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <tr>
                                <td style="width:50%;">
                                    <p class="my-0">
                                        <b>Заказчик</b><br>
                                        <span style="text-transform: uppercase;">{{order.user_web_users_name || '-'}}</span>
                                    </p>
                                </td>
                                <td style="width:50%;">
                                    <p class="my-0">
                                        <b>Контакты заказчика</b><br>
                                        Телефон: {{order.user_web_users_phone || '-'}}<br>
                                        Почта: {{order.user_web_users_email || '-'}}
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <table class="table hover caption-top responsive" v-if="order.order_services && order.order_services.data">
                            <thead v-if="order.order_services && order.order_services.data.length > 0">
                                <tr>
                                    <template v-if="param == ''">
                                        <td colspan="5">Перечень утвержденных услуг*:</td>
                                    </template>
                                    <template v-if="param == 'act'">
                                        <td colspan="5">Перечень выполненных услуг*:</td>
                                    </template>
                                </tr>
                                <tr>
                                    <th class="text-center" style="width: 5%;">№</th>
                                    <th>Услуга</th>
                                    <th class="text-center" style="width: 7%;">Кол-во</th>
                                    <th class="text-center" style="width: 15%;">Цена</th>
                                    <th class="text-center" style="width: 15%;">Стоимость</th>
                                </tr>
                            </thead>
                            <tbody v-if="order.order_services && order.order_services.data.length > 0">
                                <tr v-for="(service, index) in order.order_services.data" :key="index">
                                    <td class="text-center align-middle">{{service.id}}</td>
                                    <td class="align-middle position-relative">
                                        {{ service.name }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ service.number }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ service.sum }} руб.
                                    </td>
                                    <td class="text-center align-middle">{{(Number(service.number ? service.number : 1) * service.sum)}} руб.</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right"><b>Стоимость: </b> {{ (Number(order.itogsum_for_client)).toFixed(0) }} руб.</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right"><b>Скидка: </b> {{ (Number(order.skidka_for_client)).toFixed(0) }} руб.</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right"><b>Стоимость со скидкой: </b> {{ (order.itogsum_for_client - order.skidka_for_client).toFixed(0) }} руб.</th>
                                </tr>
                            </tfoot>
                        </table>
                        <p v-if="order.order_services"><em>* Перечисляемые услуги могут включать в себя приобретение дополнительных материалов, 
                            например, лако-красочные изделия, изделия из металла, камня, чистящие средства и так далее.</em></p>
                    </div>
                </div>

                <div class="html2pdf__page-break" style="margin-bottom: 50px;"></div>

                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <h4 class="mb-3">Местоположение предоставления услуг:</h4>
                        <p>
                            <b>Город:</b> {{order.city.pagetitle}}
                        </p>
                        <p>
                            <template v-if="order.type_of_order_object == 'Могила'">
                                <b>Кладбище:</b> {{order.virtual.graveyard_name}}
                                <template v-if="order.order_gravenumber">
                                    <br><b>Номер захоронения:</b> {{order.order_gravenumber}}
                                </template>
                                <template v-if="order.order_man_name">
                                    <br><b>Имя на могиле/кресте:</b> {{order.order_man_name}}
                                </template>
                                <template v-if="order.order_date_of_birth">
                                    <br><b>Дата рождения:</b> {{order.order_date_of_birth != '1900-01-01' ? order.order_date_of_birth : 'не известно'}}
                                </template>
                                <template v-if="order.order_date_of_death">
                                    <br><b>Дата смерти:</b> {{ (order.order_date_of_death != '1900-01-01' ? order.order_date_of_death : 'не известно') | formattedDate}}
                                </template>
                                <template v-if="order.number_of_graves">
                                    <br><b>Количество могил в заказе:</b> {{order.number_of_graves}}
                                </template>
                                <template v-if="order.address_of_order_object">
                                    <br><b>Особенности местонахождения захоронения:</b> {{order.address_of_order_object}}
                                </template>
                            </template>
                            <template v-if="order.type_of_order_object == 'Квартира'">
                                <b>Квартира по адресу:</b> {{order.address_of_order_object || '-'}}
                            </template>
                            <template v-if="order.type_of_order_object == 'Помещение'">
                                <b>Адрес:</b> {{order.address_of_order_object || '-'}}
                            </template>
                            <template v-if="order.type_of_order_object == 'Другое'">
                                {{order.address_of_order_object || '-'}}
                            </template>
                        </p>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div v-if="order.final_comment">
                            <h4 class="mb-3">Особенности:</h4>
                            <p>
                                {{order.final_comment}}
                            </p>
                        </div>

                        <div v-if="order.manager_id">
                            <h4 class="mb-3">Контакты менеджера:</h4>
                            <p>
                                <b>Имя:</b> {{order.manager.name}}
                            </p>
                            <p>
                                Телефон: <a :href="`tel:${order.manager.phone}`">{{order.manager.phone}}</a><br>
                                E-mail: <a :href="`mailto:${order.manager.email}`">{{order.manager.email}}</a>
                            </p>
                        </div>
                    </div>
                </div>

                <hr>

                <template v-if="param=='act'">
                    <ol>
                        <li>Качество оказанных Услуг проверено Заказчиком и соответствует требованиям Заказчика и/или условиям Оферты, а также Заказ-Наряду к Заказу №{{order.id}}. </li>
                        <li>Сроки устранения выявленных недостатков: отсутствуют.</li>
                        <li>Настоящий Акт составлен и подписан путем нажатия кнопки «Подписать» в подписях согласия о выполненных работах, и хранится у каждой из Сторон.</li>
                    </ol>

                    <hr>
                </template>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="mb-3 text-center">Подписи сторон</h4>
                        <table class="table table-bordered">
                            <tr>
                                <td style="width:50%;">
                                    <p class="my-0">
                                        <b>Заказчик</b><br>
                                        {{order.user_web_users_name || '-'}}<br>
                                        {{order.user_web_passport || ''}}<br>
                                        Телефон: {{order.user_web_users_phone}}
                                        <template v-if="order.user_web_users_email">
                                            <br>E-mail: {{order.user_web_users_email}}
                                        </template>
                                    </p>
                                </td>
                                <td style="width:50%;">
                                    <p class="my-0">
                                        <b>Исполнитель</b><br>
                                        ИП «ФОМИЧЕВ МИХАИЛ СЕРГЕЕВИЧ»,<br>
                                        ИНН 761026106748, ОГРНИП 316502400065421<br>
                                        Телефон: +7 (995) 577-52-22<br>
                                        E-mail: info@gravescare.com
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table class="table" v-if="param == ''">
                            <tr>
                                <td style="width:50%;">
                                    <div v-if="!order.datetime_of_client_signed_the_start">
                                        <button  class="btn btn-success mb-2" @click="signedWithClientStartClick()" :disabled="order.datetime_of_client_signed_the_start" id="datetime_of_client_signed_the_start"><i class="fas fa-spinner fa-spin mr-2" v-if="signedWithClientStart==-1"></i>Подписать</button>
                                        <label class="form-check-label" for="datetime_of_client_signed_the_start">
                                            Нажимая на кнопку «Подписать», выражаю свое согласие с перечнем утвержденных работ, а также с <a href="/docs/oferta-client.docx" target="_blank">Офертой</a> и <a href="https://gravescare.com/politika-konfidencialnosti/" target="_blank">Политикой конфиденциальности</a>. После нажатия, документ считается подписанным, изменению не подлежит.
                                        </label>
                                    </div>                                    
                                    <div v-if="order.datetime_of_client_signed_the_start">
                                        Заказчик подтвердил перечень работ {{ order.datetime_of_client_signed_the_start | formattedDate}}, а также согласился с <a href="/docs/oferta-client.docx" target="_blank">Офертой</a> и <a href="https://gravescare.com/politika-konfidencialnosti/" target="_blank">Политикой конфиденциальности</a>, что идентично подписи Заказчика.
                                    </div>                                    
                                </td>
                                <td style="width:50%;">
                                    <p class="my-0">
                                        <img src="/img/cabinet/sign.png" style="width: 100px; height: auto;"> / Фомичев Михаил Сергеевич <span v-if="order.datetime_of_client_signed_the_start">/ {{ order.datetime_of_client_signed_the_start | formattedDate}}</span>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table class="table" v-if="param == 'act'">
                            <tr>
                                <td style="width:50%;">
                                    <div v-if="!order.datetime_of_client_signed_the_end">
                                        <button class="btn btn-success mb-2" @click="signedWithClientEndClick()" :disabled="order.datetime_of_client_signed_the_end" id="datetime_of_client_signed_the_end"><i class="fas fa-spinner fa-spin mr-2" v-if="signedWithClientEnd==-1"></i>Подписать</button>
                                        <label class="form-check-label" for="datetime_of_client_signed_the_end">
                                            Нажимая на кнопку «Подписать», выражаю свое согласие, что работы выполнены успешно, претензий не имею. После нажатия, документ считается подписанным, изменению не подлежит.
                                        </label>
                                    </div>                                    
                                    <div v-if="order.datetime_of_client_signed_the_end">
                                        Заказчик подписал акт о выполненных работах {{ order.datetime_of_client_signed_the_end | formattedDate}} путем нажатия галочки напротив условий, что идентично подписи Заказчика.
                                    </div>                                    
                                </td>
                                <td style="width:50%;">
                                    <p class="my-0">
                                        <img src="/img/cabinet/sign.png" style="width: 100px; height: auto;"> / Фомичев Михаил Сергеевич <span v-if="order.datetime_of_client_signed_the_end">/ {{ order.datetime_of_client_signed_the_end | formattedDate}}</span>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>    
    </section><!--/main-->

    <footer-block data-html2canvas-ignore="true"></footer-block>
</div>
</template>

<script>
  import { API_CRUD_ORDER, API_ORDER_SIGN, API_SEND_ORDER_REACTION } from '../API'
  import {OrderItem} from '../mixins'

  import jsPDF from 'jspdf' 
  import html2canvas from "html2canvas"
  import html2pdf from "html2pdf.js"

  export default {
    components: {
    },
    props: {
        importedOrder: {
            type: Object,
            required: true
        },
        param: {
            type: String,
            required: false,
            default: ''
        }
    },    
    data() {
      return {
        limit: 10,
        progress: -1,
        reloading: false,
        loadingTimer: 1000,
        search: '',
        makeMeInVisible: '',
        signedWithClientStart: 0,
        signedWithClientEnd: 0
      }
    },
    mixins: [
        OrderItem
    ],
    watch: {
        order: {
            handler() {
            },
            deep: true,
            immediate: true,
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
        signedWithClientStartClick() {
            this.signedWithClientStart = -1
            this.reactNow()
        },
        signedWithClientEndClick() {
            this.signedWithClientEnd = -1
            this.reactNowEnd('end')
        },
        reactNow() {
            this.loading = true;
            this.$set(this.order, 'signedWithClientStart', 1);

            axios.put(API_ORDER_SIGN + '/' + this.order.id, this.order)
                .then((response) => {
                    this.signedWithClientStart = 1
                    this.order.datetime_of_client_signed_the_start = response.data.datetime_of_client_signed_the_start
                })
                .catch((err) => {
                })
                .finally(() => (this.loading = false));

        },
        reactNowEnd() {
            this.loading = true;
            this.$set(this.order, 'signedWithClientEnd', 1);

            axios.put(API_ORDER_SIGN + '/' + this.order.id, this.order)
                .then((response) => {
                    this.signedWithClientEnd = 1
                    this.order.datetime_of_client_signed_the_end = response.data.datetime_of_client_signed_the_end
                })
                .catch((err) => {
                })
                .finally(() => (this.loading = false));
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
        recheckNumbersInServicesList() {
            this.order.order_services.itog = 0
            this.order.order_services.data.forEach((service, index) => {
                service.id = index + 1
                this.order.order_services.itog += Number(service.sum * (service.number ? service.number : 1))
            })

            this.order.order_services.itog = this.order.order_services.itog.toFixed(2)

            this.order.itogsum_for_client = this.order.order_services.itog
        },    
        printDiv(title) {
            let divId = 'contentPrint'
            let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');

            mywindow.document.write(`<html><head><title>${title}</title>`);
            mywindow.document.write(`<link href="//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
                                    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
                                    <link href="/css/bootstrap-slider.min.css" rel="stylesheet">
                                    <link href="//cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css" rel="stylesheet" />`);
            mywindow.document.write(`<link href="/css/app.css" rel="stylesheet">`);

            mywindow.document.write('</head><body >');
            mywindow.document.write(document.getElementById(divId).innerHTML);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        },     
        downloadPdf: async function() {
            this.makeMeInVisible = 'makeMeInVisible'
            var element = document.getElementById('contentPrint');
            let filename = this.param == 'act' ? 'act-for-order-' : 'zakaz-naryad-'
            await html2pdf(element, {
                                margin: 0,
                                filename: `${filename}${this.order.id}.pdf`,
                                image: { type: 'jpeg', quality: 1 },
                                html2canvas: { scale: 2, logging: true },
                                jsPDF: { unit: 'in', format: 'a4' }
                            }).then(() => {
                                this.makeMeInVisible = ''
                            })

            //     source = this.$refs.content,
            //     specialElementHandlers = {
            //         '#bypassme': function (element, renderer) {
            //             return true
            //         }
            //     },
            //     margins = {
            //         top: 80,
            //         bottom: 60,
            //         left: 40,
            //         width: 522
            //     };
            // pdf.fromHTML(
            // source, // HTML string or DOM elem ref.
            // margins.left, // x coord
            // margins.top, { // y coord
            //     'width': margins.width, // max width of content on PDF
            //     'elementHandlers': specialElementHandlers
            // },

            // function (dispose) {
            //     pdf.save('Test.pdf');
            // }, margins);
           
            // var canvasElement = document.createElement('canvas');
            //     html2canvas(this.$refs.content, { 
            //         canvas: canvasElement 
            //     }).then(function (canvas) {
            //         const img = canvas.toDataURL("image/jpeg", 1);
            //         doc.addImage(img,'JPEG',1,1);
            //         doc.save("sample.pdf");
            //     });
        },        
    },
  }
</script>
<style scoped>
    .makeMeInVisible {
        visibility: hidden;
    }
</style>