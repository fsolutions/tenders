<template>
  <div>
    <div class="row my-3">
        <div class="col-sm-12 col-md-6">
            <div class="headUp text-muted">Установка виджета на сайт</div>
        </div>
        <div class="col-sm-12 col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6 pt-2">
            <p>Для того, чтобы интегрировать Refollower к себе на сайт, необходимо скопировать виджет и установить к себе на сайт, в основной шаблон.</p>
            <p>Код виджета необходимо разместить как можно ниже в разметке вашего сайта.</p>
            <p><em><strong>Важно!</strong> Код должен быть доступен на всех страницах сайта!</em></p>
        </div>
        <div class="col-sm-12 col-md-6">
            <textarea class="form-control" rows="10"><script type="text/javascript">var refollowerPlatformId = {{id}}; var refollowerScript = document.createElement('script'); refollowerScript.src = "https://lk.refollower.ru/deliver/ref-workspace.js"; document.body.appendChild(refollowerScript);</script></textarea>
        </div>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      id: {
        required: true
      }
    },
    data() {
      return {
      }
    },
    watch: {
    },
    mounted () {
    },
    created() {
    },
    methods: {

    },
  }
</script>