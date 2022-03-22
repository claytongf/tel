export const state = () => ({
  errors: {},
  message: '',
  status: '',
  loading: false,
  snackbar: false
})

export const mutations = {
  SET_MESSAGE(state, message){
    state.message = message
  },
  SET_ERRORS(state, errors){
    state.errors = {}
    const keys = Object.keys(errors)
    keys.map(key => {
      state.errors[key] = errors[key][0]
      return null
    })
  },
  SET_STATUS(state, status){
    state.status = status
  },
  SET_SNACKBAR(state, snackbar){
    state.snackbar = snackbar
  },
  SET_LOADING(state, loading){
    state.loading = loading
  },
  RESET_FORM_ERRORS(state) {
    state.errors = {}
  },
  RESET_ALERTS(state){
    state.errors = {}
    state.message = ''
    state.status = ''
    state.snackbar = false
  },
}
