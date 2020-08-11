<template>
  <div>
    <div class="row my-3">
        <div class="col-sm-12 col-md-6">
            <div class="headUp text-muted">Статистика рекламной кампании</div>
        </div>
        <div class="col-sm-12 col-md-6"></div>
    </div>
    {{editedPlatform}}
  </div>
</template>
<script>
  import {  } from '../../API'

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
      }
    },
    watch: {
      platform: {
          handler() {
            this.editedPlatform = Object.assign({});
            this.editedPlatform = JSON.parse(JSON.stringify(this.platform));
          },
          deep: true,
          immediate: true,
      },        
    },
    mounted () {
    },
    created() {
      console.log('editedPlatform', this.editedPlatform)
    },
    methods: {
    },
  }
</script>