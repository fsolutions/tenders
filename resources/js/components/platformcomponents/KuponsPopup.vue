<template>
  <!-- Popup Modal -->
  <div class="modal fade" id="kuponsPopup" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="kuponsPopupLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="kuponsPopupLabel">
            <div :class="`box-with-image box-image-${editedBox.index}`">
              <div class="header-title">
                Промо-коды 
                <span v-if='editedBox.index==1'> первого уровня</span>
                <span v-if='editedBox.index==2'> второго уровня</span>
                <span v-if='editedBox.index==3'> третьего уровня</span>
                <span v-if='editedBox.index==4'> четвертого уровня</span>
              </div>
              <div class="header-caption">{{editedBox.way_name}} <b>{{editedBox.way}}</b> за <b>{{editedBox.grad}} друзей</b></div>
            </div>
          </h5>
          
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-2" style="font-size: 16px;">Выберите вариант добавления промо-кодов:</div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="variant1" id="variant1" value="variant1" v-model="addKuponVariant">
            <label class="form-check-label" for="variant1" style="margin-top: -1px;">
              Один промо-код для всех участников <span class="text-green font-16px ml-2">Единый</span>
              <div class="text-muted font-12px">Промо-коды такого типа могут быть использованы многократно и подходят для незначительной скидки или предложения.</div>
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="variant2" id="variant2" value="variant2" v-model="addKuponVariant">
            <label class="form-check-label" for="variant2" style="margin-top: -1px;">
              Загрузить собственные промо-коды <span class="text-orange font-16px ml-2">Собственный</span>
              <div class="text-muted font-12px">Сделайте выгрузку промо-кодов из своего интернет-магазина, веб-сайта или CRM-системы и загрузите их к данному предложению.</div>
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="variant3" id="variant3" value="variant3" v-model="addKuponVariant">
            <label class="form-check-label" for="variant3" style="margin-top: -1px;">
              Создать (сгенерировать) промо-коды <span class="text-blue font-16px ml-2">Созданный</span>
              <div class="text-muted font-12px">Если у вас нет собственных промо-кодов, а первый вариант создания вам не подходит, то наша система автоматически создаст необходимое количество промо-кодов для вас.</div>
            </label>
          </div>
          <template v-if="savingKuponsMass">
            <div class="alert alert-success mt-4" role="alert">
              <h4 class="alert-heading mt-2">Добавляем промо-коды
                <span v-if='editedBox.index==1'> первого уровня</span>
                <span v-if='editedBox.index==2'> второго уровня</span>
                <span v-if='editedBox.index==3'> третьего уровня</span>
                <span v-if='editedBox.index==4'> четвертого уровня</span>
              </h4>              
              <div class="alert-fly-block d-none d-md-block">Добавлено {{savedKuponsNumber}} из {{savedKuponsNumberAll}}</div>
              <div class="mb-2 d-block d-md-none">Добавлено <b>{{savedKuponsNumber}}</b> из {{savedKuponsNumberAll}}</div>
              <p class="mb-0" v-if='savedKuponsNumber != savedKuponsNumberAll'><em>Пожалуйста, дождитесь сохранения всех промо-кодов!</em></p>
              <p class="mb-0" v-if='savedKuponsNumber == savedKuponsNumberAll'><em>Спасибо за ожидание! Промо-коды сохранены.</em></p>
            </div>            
          </template>
          <template v-if="addKuponVariant=='variant1'">
            <div class="mb-2 mt-3"><b>Заполните значения по купону:</b></div>
            <div class="row">
                <div class="col-sm-9 col-md-5">
                    <div class="form-group">
                        <input type="text" v-model="tempKupon.name" class="form-control" placeholder="Например, XGHRT56" required>
                        <small class="form-text text-muted">Секретное значение купона, например, XGHRT56</small>
                    </div>
                </div><!--/col-->
                <div class="col-sm-3 col-md-6">
                    <div class="form-group">
                        <input type="number" v-model="tempKupon.count" class="form-control" min="1" style="max-width: 100px;" required>
                        <small class="form-text text-muted">Укажите, сколько раз <br>можно использовать купон.</small>
                    </div>
                </div><!--/col-->
            </div>
          </template>
          <template v-if="addKuponVariant=='variant2'">
            <div class="mb-2 mt-3"><b>Введите купоны списком (каждый купон с новой строки):</b></div>
            <div class="row">
                <div class="col-sm-12 col-md-10">
                    <div class="form-group">
                        <textarea v-model="tempMyKupons" rows="8" class="form-control" required></textarea>
                    </div>
                </div><!--/col-->
                <div class="col-sm-12 col-md-2">
                  <small class="form-text text-muted">Например, <br>XGHRT56<br>XGHRT57<br>XGHRT58<br>и так далее...</small>
                </div><!--/col-->
            </div>
          </template>
          <template v-if="addKuponVariant=='variant3'">
            <div class="mb-3 mt-3"><b>Сгенерированные купоны:</b></div>
            <div class="row">
                <div class="col-sm-12 col-md-10">
                    <div class="form-group">
                        <textarea v-model="tempAutoKupons" rows="8" class="form-control" required></textarea>
                    </div>
                </div><!--/col-->
                <div class="col-sm-12 col-md-2">
                  <small class="form-text text-muted">Например, <br>XGHRT56<br>XGHRT57<br>XGHRT58<br>и так далее...</small>
                </div><!--/col-->
            </div>
          </template>
          <template v-if="editedBox.kupons.length > 0">
            <div class="row mt-4">
              <div class="col-sm-12 col-md-8 mt-1 mb-3 text-center text-md-left pl-4">
                <b>Ранее добавленные купоны</b>
              </div>
              <div class="col-sm-12 col-md-4 text-center text-md-right pr-4">
                <button class="btn btn-sm btn-danger" @click="clearAllKuponsInBox()"><i class="fas fa-spinner fa-spin mr-2" v-if="clearingAllKupons"></i><i class="far fa-trash-alt mr-2" v-if="!clearingAllKupons"></i>Удалить все</button>
              </div>
            </div>

            <div class="card nobrd">
              <div class="card-body table-responsive p-0">
                  <div class="input-group input-group">
                    <input type="text" class="form-control" aria-label="Поиск купонов" aria-describedby="inputGroup-sizing-sm" placeholder="Начните вводить наименование купона..." @keyup="searchInTable(searchLine, 'kupons-search-table-sm')" v-model="searchLine" style="border-radius: 0;">
                    <div class="input-group-append">
                      <span class="input-group-text" id="inputGroup-sizing-
                      ">Поиск</span>
                    </div>
                  </div>        
                  <table id="kupons-search-table-sm" class="table table-striped table-hover table-borderless table-kupon mb-0">
                      <thead>
                          <tr>
                              <th>Купон</th>
                              <th class="text-center">Осталось</th>
                              <th class="text-center">Выдано</th>
                              <th class="text-center">Тип промо-кода</th>
                              <th></th>
                          </tr>
                      </thead>

                      <tbody>
                          <tr v-for="(kupon, index)  in allKupons" :key="kupon.id">

                              <td>{{kupon.name}}</td>
                              <td class="text-center">
                                <div class="open-kupon-edit"
                                  v-if='!kupon.edit' 
                                  @click='openKupon(index)'>
                                    {{kupon.count}}<i class="fas fa-pen ml-1 font-10px"></i>
                                </div>
                                <div class="on-kupon-edit" v-if="kupon.edit">
                                    <div class="form-inline" style="display: inline-block;">
                                        <input type="number" class="form-control form-control-sm mr-1" style="width: 60px;" min="0" v-model="kupon.count">
                                        <button class="btn btn-sm btn-success mr-1" @click='saveKupon(index)'><i class="fas fa-check" v-if="!kupon.saving"></i><i class="fas fa-spinner fa-spin" v-if="kupon.saving"></i></button>
                                        <button class="btn btn-sm btn-secondary" @click='cancelKuponEdit(index)'><i class="fas fa-times"></i></button>
                                    </div>
                                    <div><small id="emailHelp" class="form-text text-muted">Введите число купонов и сохраните.</small></div>
                                </div>                      
                              </td>
                              <td class="text-center">{{kupon.opened}}</td>
                              <td :class="typeTextColor(kupon.type)">{{kupon.type_stringify}}</td>
                              <td class="text-center" style="vertical-align: middle;">  
                                  <button class="btn btn-sm btn-outline-danger" :id='`delKupon-${index}`' @click='deleteKupon(index)'><i class="far fa-trash-alt"></i></button>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
              <!-- /.card-body -->
              <!-- <div class="card-footer">
                  <div class="overflow-auto">
                      <pagination
                          class="mb-0"
                          :data="editedBox.kupons"
                          @pagination-change-page="getResults"
                          :limit="limit"
                          :show-disabled="showDisabled"
                          :size="size"
                          :align="align" />
                  </div>
              </div> -->
            </div>

          </template>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="close()">Закрыть</button>
          <button type="button" class="btn btn-success" @click="saveKupons()" v-if="addKuponVariant != ''"><i class="fas fa-spinner fa-spin mr-2" v-if="savingKupons"></i>Сохранить</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import { API_CRUD_KUPONS, API_MASS_DELETE_KUPONS_IN_BOX } from '../../API'

  const initialBoxItem = () => ({
    id: '',
    platform_id: '',
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
    props: {
      box: {
        type: Object,
        default: null
      }
    },
    data() {
      return {
        editedBox: initialBoxItem(),
        allKupons: [],
        addKuponVariant: "",
        clearingAllKupons: false,
        savingKupons: false,
        savingKuponsMass: false,
        savedKuponsNumber: 0,
        savedKuponsNumberAll: 0,   
        searchLine: '',    
        tempKupon:              // Один купон на всех 
        {
          name: "",
          count: 1
        },
        tempMyKupons: "",       // Купоны от пользователя из textarea
        tempMyKuponsList: [],   // Купоны от пользователя массивом
        tempAutoKupons: "",     // Автоматические купоны из textarea
        tempAutoKuponsList: []  // Автоматические купоны массивом
      }
    },
    watch: {
      box: {
          handler() {
            this.editedBox = initialBoxItem();
            this.allKupons = [];
            this.editedBox = JSON.parse(JSON.stringify(this.box));
            this.checkActivationOfKupons();
          },
          // deep: true,
          immediate: true,
      },        
      addKuponVariant: {
          handler(value) {
            if (this.addKuponVariant == 'variant3') {
              this.generateKupons();
            }
          },
      },        
    },
    mounted () {
    },
    created() {
    },
    methods: {
      close() {
        this.savingKuponsMass = false;
        this.clearingAllKupons = false,
        this.savedKuponsNumber = 0;
        this.savedKuponsNumberAll = 0;
        this.searchLine = "";
        this.searchInTable(this.searchLine, 'kupons-search-table-sm');
        this.$emit('offKuponsPopup');
      },
      unique(arr) {
        let result = [];

        for (let str of arr) {
          if (!result.includes(str)) {
            result.push(str);
          }
        }

        return result;
      },    
      generateKupons() {
        this.tempAutoKuponsList = [];
        let abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        let numb = "1234567890";
        for (let index = 0; index < 100; index++) {
          let code = "";
          while (code.length < 4) {
              code += abc[Math.floor(Math.random() * abc.length)];
          }
          while (code.length < 7) {
              code += numb[Math.floor(Math.random() * numb.length)];
          }
          this.tempAutoKuponsList.push(code);
        }
        this.tempAutoKupons = this.unique(this.tempAutoKuponsList).join('\n');
      },
      checkActivationOfKupons() {
        this.editedBox.kupons.forEach((kupon, index) => {
          kupon.active = false;
          if (kupon.count > 0) {
            kupon.active = true;
          }
          this.addKuponToAllKupons(kupon, index);
        });
      },
      addKuponToAllKupons(kupon, index) {
        this.allKupons.push(kupon);
        this.$set(this.allKupons[index], 'index', index);
        this.$set(this.allKupons[index], 'previusCount', kupon.count);
        this.$set(this.allKupons[index], 'saving', false);
        this.$set(this.allKupons[index], 'edit', false);
        this.$set(this.allKupons[index], 'active', false);
      },
      saveOneKupon(name, count, type, total = 0) {
        this.savingKupons = true;
        axios.post(API_CRUD_KUPONS, {
          'box_id': this.box.id,
          'name': name,
          'count': count,
          'type': type
        })
            .then(response => {
                response.data.active = true;
                response.data.opened = 0;
                this.editedBox.kupons.push(response.data);
                this.addKuponToAllKupons(response.data, this.editedBox.kupons.length - 1);
                this.$emit('updateKuponsInBox', this.editedBox.kupons);
                this.addKuponVariant = "";
                this.savedKuponsNumber++;
                if (this.savedKuponsNumber == total) {
                  setTimeout(() => {
                    this.savingKuponsMass = false;
                    this.savedKuponsNumber = 0;
                    this.savedKuponsNumberAll = 0;
                  }, 1500);
                }
            })
            .catch(err => console.log(err.response.data))
            .finally(() => (this.savingKupons = false));
      },
      saveKupons() {
        let typeOfKupons;

        if (this.addKuponVariant == 'variant1') {
          typeOfKupons = 0;
          this.saveOneKupon(this.tempKupon.name, this.tempKupon.count, typeOfKupons);
        } else {
          let kupons, kuponsList;
          if (this.addKuponVariant == 'variant2') { kupons = this.tempMyKupons; typeOfKupons = 1; }
          if (this.addKuponVariant == 'variant3') { kupons = this.tempAutoKupons; typeOfKupons = 2; }

          kuponsList = kupons.split('\n');
          kuponsList.forEach((kupon, index) => {
            kupon = kupon.trim();
            if (kupon == "" || kupon == " ") {
              kuponsList.splice(index, 1);
            }
          });

          this.savedKuponsNumberAll = kuponsList.length;

          this.savingKuponsMass = true;

          kuponsList.forEach(kupon => {
            this.saveOneKupon(kupon, 1, typeOfKupons, kuponsList.length);
          });
        }
      },
      deleteKuponToggler(index) {
          $('#delKupon-' + index + ' i').toggleClass("far fa-trash-alt");
          $('#delKupon-' + index + ' i').toggleClass("fas fa-spinner fa-spin");
      },
      deleteKupon(index) {
        this.deleteKuponToggler(index);
        axios
            .delete(API_CRUD_KUPONS + '/' + this.editedBox.kupons[index].id)
            .then(res => {
              this.editedBox.kupons.splice(index, 1);
              this.allKupons.splice(index, 1);
              this.$emit('updateKuponsInBox', this.editedBox.kupons);
            })
            .catch(err => console.log(err.response))
      },
      clearAllKuponsInBox() {
        this.clearingAllKupons = true;
        axios
            .post(API_MASS_DELETE_KUPONS_IN_BOX + '/' + this.editedBox.id)
            .then(res => {
              this.editedBox.kupons = [];
              this.allKupons = [];
              this.$emit('updateKuponsInBox', this.editedBox.kupons);
            })
            .catch(err => console.log(err.response))
            .finally(() => (this.clearingAllKupons = false));
      },
      openKupon(index) {
        this.allKupons[index].edit = true;
      },
      saveKupon(index) {
        this.allKupons[index].saving = true;

        axios.put(API_CRUD_KUPONS + '/' + this.allKupons[index].id, {
          'name': this.allKupons[index].name,
          'count': this.allKupons[index].count
        })
            .then(response => {
              this.editedBox.kupons[index].count = this.allKupons[index].count;   
              this.$emit('updateKuponsInBox', this.editedBox.kupons);
              this.allKupons[index].previusCount = this.allKupons[index].count;
              this.allKupons[index].edit = false;
            })
            .catch(err => console.log(err.response.data))
            .finally(() => (this.allKupons[index].saving = false));

      },
      cancelKuponEdit(index) {
        this.allKupons[index].edit = false;
        this.allKupons[index].count = this.allKupons[index].previusCount;
      },
      searchInTable(search, element) {
        var filter, table, tr, td, i, txtValue;
        filter = search.toUpperCase();
        table = document.getElementById(element);
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[0];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
            } else {
              tr[i].style.display = "none";
            }
          }
        }
      },      
      typeTextColor(type, needCenter = true) {
        let color;

        switch (type) {
          case 0:
            color = "text-green"; 
            break;
          case 1:
            color = "text-orange"; 
            break;
          case 2:
            color = "text-blue"; 
            break;
        }

        return (needCenter==true ? "text-center ": "") + color;
      }
    },
  }
</script>