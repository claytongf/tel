<template>
  <v-col>
    <v-card outlined>
      <v-card-title>Alterar Senha</v-card-title>
      <v-card-text>
        <form @submit.prevent="save">
          <label>Senha</label>
          <v-text-field v-model="form.password" type="password" placeholder="Senha" :error="'password' in errors" :error-messages="errors.password" required prepend-icon="fas fa-lock"></v-text-field>
          <label>Confirme sua senha</label>
          <v-text-field v-model="form.password_confirmation" type="password" placeholder="Confirme sua Senha" required prepend-icon="fas fa-lock"></v-text-field>
          <v-btn color="success" :loading="loading" @click.prevent="submit">Salvar</v-btn>
        </form>
      </v-card-text>
    </v-card>
  </v-col>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
export default {
  name: 'FormPassword',
  data(){
    return {
      form: {
        password: '',
        password_confirmation: ''
      }
    }
  },
  computed: {
    ...mapState({
      user: state => state.auth.user.data,
      errors: state => state.alerts.errors,
      loading: state => state.alerts.loading,
    })
  },
  mounted(){
    this.$store.commit('alerts/RESET_FORM_ERRORS')
  },
  methods: {
    ...mapMutations({
      setLoading: 'alerts/SET_LOADING',
    }),
    async submit(){
      this.setLoading(true)
      await this.$store.dispatch('user/updatePassword', this.form)
      this.setLoading(false)
    }
  }
}
</script>
