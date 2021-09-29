import Vue from 'vue'

// Замена запятых на точки на лету
export const changeCommasOnDots = {
    methods: {
        changeCommasOnDots: function(event) {
            event.target.value = event.target.value.replace(",", ".")
            event.target.value = event.target.value.replace(/[^0-9\.]/g,"")
        }
    }
}

// Преобразование сумм
export const toCurrency = {
    methods: {
        toCurrency(value) {
            if (typeof value !== "number") {
                return value
            }
            let formatter = new Intl.NumberFormat('ru-RU', {
                minimumFractionDigits: 0
            })
            return formatter.format(value)
        }
    }
}

// Преобразование даты
const moment = require('moment')
require('moment/locale/ru')
Vue.use(require('vue-moment'), { moment })
Vue.prototype.moment = moment

export const formattedDate = {
    methods: {
        formattedDate(date) {
            let formatDate = moment(date).format('DD.MM.YYYY')
            if (formatDate == 'Invalid date') return "Не указана"
            return formatDate
        }
    }
}

// Преобразование даты со временем
export const formattedDateTime = {
    methods: {
        formattedDateTime(date) {
            let formatDate = moment(date).format('DD.MM.YYYY HH:mm')
            if (formatDate == 'Invalid date') return "Не указана"
            return formatDate
        }
    }
}

// Форматирование номера телефона
export const phoneMask = {
    methods: {
        phoneMask(phone) {
            if (!!phone) {
                phone = phone.replace(/\D/g, '')
                const match = phone.match(/^(\d{1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})$/)
                if (match) {
                    phone = `+${match[1] === '8' ? '7' : match[1]} (${match[2]}) ${match[3]}-${match[4]}-${match[5]} `
                }
                return phone
            }
        }
    }
}

// Обрезка значений в инпутах на лету
export const maxCharsLimit = {
    methods: {
        maxCharsLimit(maxLength, valueId, objectId = 'editedItem') {
            if (this[objectId][valueId].length >= maxLength) {
                this[objectId][valueId] = this[objectId][valueId].substring(0, maxLength)
            }
        }
    }
}



