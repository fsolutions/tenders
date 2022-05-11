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

        <div class="card mb-3" style="flex-direction: row; flex-wrap: wrap;">

          <div 
              v-for="(number, index) in numbers"
              :class="`card text-center m-5 ${number.color}`" 
              style="width: 10rem;"
            >
              <div class="card-body">
                <h5 class="card-title">{{ number.number }}</h5>
                <p class="card-text">{{ number.title }}</p>
              </div>
          </div>

        </div>    

    </section><!--/main-->

    <footer-block></footer-block>
</div>
</template>

<script>
    import { API_TG_NUMBERS } from '../../API'

    import {
        changeCommasOnDots,
    } from '../../mixins'

    const initialEditedItem = () => ({
    })

  export default {
    mixins: [
        changeCommasOnDots,
    ],
    components: {
    },
    data() {
      return {
        numbers: {},
        limit: 10,
        progress: -1,
        loading: false,
        loadingTimer: 1000,
        search: "",
        windowTitle: 'Dashboard системы',
      }
    },
    watch: {
        // numbers: {
        //     handler() {
        //         this.numbers.opened_numbers = this.numbers.opened_numbers == 0 ? false : true
        //     },
        //     deep: true,
        //     immediate: true,
        // },
    },
    computed: {
      // logMessages() {
      //     return this.$store.getters.getLogMessages
      // },
    },
    mounted() {
        this.loadNumbers()
    },
    methods: {
        loadNumbers() {
            this.loading = true;
            this.numbers = initialEditedItem()

            axios.get(API_TG_NUMBERS)
                .then(response => {
                    this.numbers = response.data;
                })
                .catch(err => console.log(err.response.data))
                .finally(() => (this.loading = false));
        },
    },
  }
</script>
<style scoped>
</style>