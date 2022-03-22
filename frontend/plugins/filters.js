import Vue from 'vue'

const formatDate = (currentDate) => {
  if(!currentDate){
    return null
  }
  const dates = currentDate.split('/')
  return dates[2] + '-' + dates[1] + '-' + dates[0]
}

export const formatDateBr = (currentDate) => {
  if(!currentDate){
    return null
  }
  const dates = currentDate.split('-')
  return dates[2] + '/' + dates[1] + '/' + dates[0]
}

const formatPrice = (price) => {
    if(typeof price === 'number'){
        return price.toLocaleString('pt-br', {maximumFractionDigits: 2, minimumFractionDigits: 2})
    }else{
        return parseFloat(price).toLocaleString('pt-br', {maximumFractionDigits: 2, minimumFractionDigits: 2})
    }
}

const timeConvert = (time) => {
  time /= 1000
  const hours = Math.floor(time / 3600)
  const minutes = Math.floor(time % 3600 / 60)
  return hours.toLocaleString('pt-BR', {minimumIntegerDigits: 2}) + ":" + minutes.toLocaleString('pt-BR', {minimumIntegerDigits: 2})
}

Vue.filter('timeConvert', timeConvert)
Vue.filter('formatDate', formatDate)
Vue.filter('formatDateBr', formatDateBr)
Vue.filter('formatPrice', formatPrice)
