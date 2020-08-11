<template>
  <div>
    <div class="row my-3">
        <div class="col-sm-12 col-md-6">
            <div class="headUp text-muted">Список промо-кодов рекламной кампании</div>
        </div>
        <div class="col-sm-12 col-md-6"></div>
    </div>

    <div class="card nobrd">
      <div class="card-body table-responsive p-0">
        <div class="input-group input-group">
          <input type="text" class="form-control" aria-label="Поиск купонов" aria-describedby="inputGroup-sizing-sm" placeholder="Начните вводить наименование купона..." @keyup="searchInTable(searchLine, 'kupons-search-table-1')" v-model="searchLine" style="border-radius: 0;">
          <div class="input-group-append">
            <span class="input-group-text" id="inputGroup-sizing-
            ">Поиск</span>
          </div>
        </div>        
        <table id="kupons-search-table-1" class="table table-striped table-hover table-borderless table-kupon mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="allKuponsChecked" v-model="allKuponsChecked">
                        <label class="form-check-label" for="allKuponsChecked"></label>
                      </div>
                    </th>
                    <th>Купон</th>
                    <th class="text-center">Акция</th>
                    <th class="text-center">Осталось</th>
                    <th class="text-center">Выдано</th>
                    <th class="text-center">Тип промо-кода</th>
                    <th class="text-center"></th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(kupon, index) in allKupons" :key="kupon.id">
                    <td class="text-center">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" :id="`check_${index}`" v-model="allKupons[index].checked">
                      </div>
                    </td>
                    <td>{{kupon.name}}</td>
                    <td class="text-center">{{kupon.akciya}}</td>
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
      <div class="card-footer">
        <div class="text-right">
          <button class="btn btn-warning btn-sm" @click="deleteAllChecked()">Удалить выбранные</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import { API_CRUD_KUPONS, API_MASSDELETE_KUPONS } from '../../API'

  export default {
    props: {
      platform: {
        type: Object,
        default: null
      }
    },
    data() {
      return {
        editedPlatform: "",
        searchLine: "",
        allKupons: [],
        allKuponsChecked: false,
        savingKupons: false,
        tempKupon:              // Один купон на всех 
        {
          name: "",
          count: 1
        },
      }
    },
    watch: {
      platform: {
          handler() {
            this.editedPlatform = Object.assign({});
            this.editedPlatform = JSON.parse(JSON.stringify(this.platform));
            this.formAndcheckActivationOfKupons();
          },
          // deep: true,
          immediate: true,
      },        
      allKuponsChecked: {
          handler() {
            this.allKupons.forEach(kupon => {
              kupon.checked = false;
              if (this.allKuponsChecked) {
                kupon.checked = true;
              }
            });
          },
      },        
    },
    mounted () {
    },
    created() {
    },
    methods: {
      unique(arr) {
        let result = [];

        for (let str of arr) {
          if (!result.includes(str)) {
            result.push(str);
          }
        }

        return result;
      },    
      formAndcheckActivationOfKupons() {
        let specIndex = 0;
        this.editedPlatform.boxes.forEach((box, boxIndex) => {
          box.kupons.forEach((kupon, index) => {
            this.allKupons.push(kupon);
            this.$set(this.allKupons[specIndex], 'index', index);
            this.$set(this.allKupons[specIndex], 'previusCount', kupon.count);
            this.$set(this.allKupons[specIndex], 'saving', false);
            this.$set(this.allKupons[specIndex], 'edit', false);
            this.$set(this.allKupons[specIndex], 'active', false);
            this.$set(this.allKupons[specIndex], 'boxIndex', boxIndex);
            this.$set(this.allKupons[specIndex], 'checked', false);
            this.$set(this.allKupons[specIndex], 'akciya', box.way + ' за ' + box.grad + ' друзей');
            if (kupon.count > 0) {
              this.allKupons[specIndex].active = true;
            }
            specIndex++;
          });
        });
      },
      deleteAllChecked() {
        let idsForDelete = [];
        let indexesForDelete = [];
        this.allKupons.forEach((kupon, index) => {
          if (kupon.checked) {
            indexesForDelete.push(index);
            idsForDelete.push(kupon.id);
            this.deleteKuponToggler(index);
          }
        });

        indexesForDelete.sort(function(a, b) {
          return b - a;
        });        

        axios
            .post(API_MASSDELETE_KUPONS, {"idsForDelete" : idsForDelete})
            .then(res => {
              indexesForDelete.forEach(dindex => {
                this.editedPlatform.boxes[this.allKupons[dindex].boxIndex].kupons.splice(this.allKupons[dindex].index, 1);
                this.allKupons.splice(dindex, 1);
              });
              this.$emit('updatePlatformEmit', this.editedPlatform);
            })
            .catch(err => console.log(err.response))

      },
      deleteKuponToggler(index) {
          $('#delKupon-' + index + ' i').toggleClass("far fa-trash-alt");
          $('#delKupon-' + index + ' i').toggleClass("fas fa-spinner fa-spin");
      },
      deleteKupon(index) {
        this.deleteKuponToggler(index);
        axios
            .delete(API_CRUD_KUPONS + '/' + this.allKupons[index].id)
            .then(res => {
              this.editedPlatform.boxes[this.allKupons[index].boxIndex].kupons.splice(this.allKupons[index].index, 1);
              this.allKupons.splice(index, 1);
              this.$emit('updatePlatformEmit', this.editedPlatform);
            })
            .catch(err => console.log(err.response))
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
              this.editedPlatform.boxes[this.allKupons[index].boxIndex].kupons[this.allKupons[index].index] = this.allKupons[index].count;   
              // this.$emit('update:platform', this.editedPlatform);  
              this.$emit('updatePlatformEmit', this.editedPlatform);
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
          td = tr[i].getElementsByTagName("td")[1];
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
      typeTextColor(type) {
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

        return "text-center " + color;
      }
    },
  }
</script>