export const OrderItem = {
  components: {
  },
  data() {
      return {
        mapOrder: '',
        order: {
           id: '',
            ordertxt_stringify: '',
            order_city_id: '', 
            order_object_id: '', 
            order_object_name_ext: '', 
            order_text_details: '', 
            city: {},
            graveyard: {},
            number_of_graves: '',
            itogsum: 0,
            itogsum_for_client: 0,
            skidka_for_client: 0,
            tarif: '',
            tarif_stringify: '',
            status: '', 
            status_stringify: '',
            status_cssclass: '',
            can_access: false,
            reacted: false,
            react: [],
            can_delete: false,
            virtual: {
                graveyard_name: '',
                choice_edit: 0,
                reaction: false,
                map: false,
            },
            manager_id: '',
            manager: {},
            order_services: {
            data: [],
            itog: 0
        },
            start_date_of_work: '',
            end_date_of_work: '',
            oriental_days_for_work: '',
            type_of_order_object: 'Могила',   // ['Могила', 'Квартира', 'Помещение', 'Другое']
            address_of_order_object: '',
            final_comment: '',
            datetime_of_client_signed_the_start: '',
            datetime_of_client_signed_the_end: '',
            datetime_of_ispolnitel_signed_the_start: '',
            datetime_of_ispolnitel_signed_the_end: '',
        },
        savingOrder: false,
      }
  },
  mounted() {
  },
  methods: {
  }
}
