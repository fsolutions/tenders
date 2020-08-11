<template>
  <section class="mx-0 mx-md-5 mt-5 mb-1 platform__footer">
    <div class="row">
        <div class="col-sm-12 col-md-4 pt-2">
            &copy; Refollower
        </div>
        <div class="col-sm-12 col-md-4 text-center">
            <a href="/platforms/"><img src="/img/cabinet/logo_down_2.jpg" class="img-fluid" alt="Refollower"></a>
        </div>
        <div class="col-sm-12 col-md-4 text-left text-md-right pt-2">
            <a href="#" class="plaform__footer__supportbtn" @click="newQuestion()">Поддержка</a>
        </div>
    </div>

    <!-- Dialog Send Message -->
    <div class="modal fade" id="supportPopup" tabindex="-1" role="dialog" aria-labelledby="supportPopupTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Обращение в поддержку</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <template v-if="questionSended">
                  <div class="alert alert-success mt-0" role="alert">
                    <h4 class="alert-heading mt-2">Ваше сообщение успешно отправлено!</h4>              
                    <p class="bold">Спасибо за ваше обращение!</p>
                    <p>Наш менеджер свяжется с вами в самое ближайшее время!</p>
                  </div>            
                </template>
                <form class="needs-validation" id="questionSendingForm" novalidate v-if="!questionSended">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="subject">Тема обращения</label>
                            <input type="text" required class="form-control" id="subject" placeholder="У меня есть предложение" v-model="support.subject">
                        </div>
                    </div><!-- .form-row -->
                    <div class="form-row">
                        <div class="form-group col-md-12">  
                            <label for="question">Вопрос или предложение</label>
                            <textarea required class="form-control" id="question" rows="6" v-model="support.question"></textarea>
                        </div>
                    </div><!-- .form-row -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-success" @click="sendQuestion()" v-if="!questionSended">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="sendingQuestion"></span>
                    Отправить нам
                </button>
            </div>
            </div>
        </div>
    </div>
    <!-- End: Dialog Send Message -->              
  </section><!--/platform__box__settings-->
</template>
<script>
  import { API_POST_SEND_QUESTION } from '../../API'

  export default {
    data() {
      return {
        dialogES: '#supportPopup',
        sendingQuestion: false,
        questionSended: false,
        support: {
          subject: '',
          question: '',
        }
      }
    },
    watch: {
    },
    mounted () {
      let self = this;
      $(this.dialogES).on('hidden.bs.modal', function (e) {self.close();});
    },
    methods: {
      close() {
          $(this.dialogES).modal('hide');
          this.support.subject = "";
          this.support.question = "";
          this.sendingQuestion = false;
          this.questionSended = false;
      },      
      newQuestion() {
          $(this.dialogES).modal('show');
      },       
      sendQuestion() {
        this.sendingQuestion = true;
        axios.post(API_POST_SEND_QUESTION, this.support)
            .then(response => {
              this.questionSended = true;
            })
            .catch(err => console.log(err.response.data))
            .finally(() => (this.sendingQuestion = false));        
      }  
    },
  }
</script>