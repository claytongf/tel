const USERS = 'users'
const UPDATE_PASSWORD = 'user/update-password'
const UPDATE_PROFILE = 'user/update-profile'

export const state = () => ({
  users: [],
  total: 0,
})

export const mutations = {
  SET_USERS(state, users){
    state.users = users
  },
  ADD_USER(state, user){
    state.users.push(user)
  },
  UPDATE_USER(state, user){
    const index = state.users.findIndex(obj => obj.id === user.id)
    state.users.splice(index, 1, user)
  },
  SET_TOTAL(state, total){
    state.total = total
  },
  INCREMENT_TOTAL(state){
    state.total++
  },
  DECREMENT_TOTAL(state){
    state.total--
  },
  DELETE_USER(state, id){
    state.users = state.users.filter((obj) => {
      return obj.id !== id
    })
  },
}

export const actions = {
  getUsers({ commit }, options){
    const { search, sortBy, sortDesc, page, itemsPerPage } = options
    const dir = sortDesc[0] === true ? '&dir=desc' : sortDesc[0] === false ? '&dir=asc' : ''
    const sort = sortBy.length >= 1 ? '&sort='+sortBy : ''
    const perPage = '&perPage='+itemsPerPage
    const filter = search.length > 0 ?  '&search='+search : ''
    return this.$axios.$get(`${process.env.apiVersion}/${USERS}?page=${page}${sort}${dir}${perPage}${filter}`)
    .then(response => {
      commit('SET_USERS', response.data)
      commit('alerts/RESET_ALERTS', null, { root: true })
      if(response.meta){
        commit('SET_TOTAL', response.meta.total)
      }else{
        commit('SET_TOTAL', response.data.length)
      }
    })
  },
  store({ commit }, formData){
    return this.$axios.$post(`${process.env.apiVersion}/${USERS}`, formData, {
      headers: {
        'Accept': 'Application/json'
      }
    })
    .then(response => {
      commit('ADD_USER', response.data)
      commit('INCREMENT_TOTAL')
      commit('alerts/RESET_ALERTS', null, { root: true })
      commit('alerts/SET_STATUS', 'success', { root: true })
      commit('alerts/SET_MESSAGE', 'Usuário Criado com Sucesso!', { root: true })
      commit('alerts/SET_SNACKBAR', true, { root: true })
    }).catch(error => {
      if('response' in error && error.response.status === 422){
        commit('alerts/SET_ERRORS', error.response.data.errors, { root: true })
        commit('alerts/SET_STATUS', 'error', { root: true })
      }
    })
  },
  update({ commit }, formData){
    return this.$axios.$put(`${process.env.apiVersion}/${USERS}/${formData.id}`, formData, {
      headers: {
        'Accept': 'Application/json'
      }
    }).then(response => {
      commit('UPDATE_USER', response.data)
      commit('INCREMENT_TOTAL')
      commit('alerts/RESET_ALERTS', null, { root: true })
      commit('alerts/SET_STATUS', 'success', { root: true })
      commit('alerts/SET_MESSAGE', 'Usuário Atualizado com Sucesso!', { root: true })
      commit('alerts/SET_SNACKBAR', true, { root: true })
    }).catch(error => {
      if('response' in error && error.response.status === 422){
        commit('alerts/SET_ERRORS', error.response.data.errors, { root: true })
        commit('alerts/SET_STATUS', 'error', { root: true })
      }
    })
  },
  updateProfile({ commit }, params) {
    return this.$axios.$put(`${process.env.apiVersion}/${UPDATE_PROFILE}`, params, {
      headers: {
        'Accept': 'Application/json'
      }
    }).then(response => {
      commit('alerts/SET_STATUS', 'primary', { root: true })
      commit('alerts/SET_SNACKBAR', true, { root: true })
      commit('alerts/SET_MESSAGE', 'Seus dados foram atualizados com sucesso', { root: true })
      this.$auth.setUser(response)
      commit('alerts/RESET_FORM_ERRORS', null, { root: true })
    }).catch(error => {
      commit('alerts/SET_STATUS', 'error', { root: true })
      if(error.response.status === 422){
        commit('alerts/SET_SNACKBAR', true, { root: true })
        commit('alerts/SET_MESSAGE', 'Validação de Formulário. Por favor, verifique os campos preenchidos e tente novamente', { root: true })
        commit('alerts/SET_ERRORS', error.response.data.errors, { root: true })
      }
    })
  },
  delete({ commit }, id){
    return this.$axios.$delete(`${process.env.apiVersion}/${USERS}/${id}`,{
      headers: {
        'Accept': 'Application/json'
      }
    }).then(() => {
      commit('DELETE_USER', id)
      commit('DECREMENT_TOTAL')
      commit('alerts/RESET_ALERTS', null, { root: true })
      commit('alerts/SET_STATUS', 'success', { root: true })
      commit('alerts/SET_MESSAGE', 'Usuário Deletado com Sucesso!', { root: true })
      commit('alerts/SET_SNACKBAR', true, { root: true })
    }).catch(error => {
      commit('alerts/SET_STATUS', 'error', { root: true })
      if(error.response.status === 401){
        commit('alerts/SET_SNACKBAR', true, { root: true })
        commit('alerts/SET_MESSAGE', 'Você não pode remover seu próprio usuário', { root: true })
      }
    })
  },
  updatePassword({ commit }, params) {
    return this.$axios.$patch(`${process.env.apiVersion}/${UPDATE_PASSWORD}`, params, {
      headers: {
        'Accept': 'Application/json'
      }
    }).then(() => {
      commit('alerts/SET_STATUS', 'primary', { root: true })
      commit('alerts/SET_SNACKBAR', true, { root: true })
      commit('alerts/SET_MESSAGE', 'Senha atualizada com sucesso', { root: true })
      commit('alerts/RESET_FORM_ERRORS', null, { root: true })
    }).catch(error => {
      commit('alerts/SET_STATUS', 'error', { root: true })
      if(error.response.status === 422){
        commit('alerts/SET_SNACKBAR', true, { root: true })
        commit('alerts/SET_MESSAGE', 'Validação de Formulário. Por favor, verifique os campos preenchidos e tente novamente', { root: true })
        commit('alerts/SET_ERRORS', error.response.data.errors, { root: true })
      }
    })
  }
}
