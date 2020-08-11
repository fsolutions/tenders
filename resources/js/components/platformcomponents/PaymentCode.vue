<template>
  <div>
    <div class="row mt-3">
        <div class="col-sm-12 col-md-6">
          <div class="headUp text-muted mb-3">Оплата виджета</div>
          <div class="pt-2">
              <p>Для того, чтобы код Refollower заработал, его необходимо оплатить.</p>
              <p>У нас существует 3 тарифа для оплаты, которые должны покрыть все запросы по проведению рекламных кампаний:</p>
              <ul>
                <li>Неделя: <b>999</b> рублей</li>
                <li>Две недели: <b>1799</b> рублей</li>
                <li>Месяц: <b>2999</b> рублей</li>    
              </ul>
              <p><em><strong>Важно!</strong> После получения оплаты рекламная кампания включится автоматически!</em></p>
          </div>
        </div>
        <div class="col-sm-12 col-md-6">
          <div class="headUp text-muted mb-3">Запустите рекламную кампанию</div>
          <div class="pt-2 text-center">
              <select class="custom-select custom-select-lg">
                <option selected>Выберите тарифный план</option>
                <option value="1">Неделя: 999 рублей</option>
                <option value="2">Две недели: 1799 рублей</option>
                <option value="3">Месяц: 2999 рублей</option>
              </select>
              <div class="mt-2 text-center">
                <img src="/img/cabinet/payments.png?2020" alt="Способы оплаты" class="img-fluid">
              </div>
              <button class="btn btn-success btn-lg mt-2">Оплатить сейчас</button>        
          </div>
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