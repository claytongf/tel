<template>
  <v-row justify="center" align="center">
    <v-col cols="12">
      <v-card outlined>
        <v-card-title>Olá, Visitante</v-card-title>
        <form class="mt-5" @submit.prevent="login">
          <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" md="12">
                    <label>E-mail, CPF ou PIS</label>
                    <v-text-field v-model="email" type="text" placeholder="E-mail, CPF ou PIS" :error="'email' in errors" :error-messages="errors.email" required prepend-icon="fas fa-user"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="12">
                    <label>Senha</label>
                    <v-text-field v-model="password" type="password" placeholder="Senha" required prepend-icon="fas fa-lock"></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
          </v-card-text>
          <v-card-actions class="mb-4 mr-4">
            <v-spacer></v-spacer>
            <v-btn dark type="submit" elevation="2" color="blue">Login</v-btn>
          </v-card-actions>
        </form>
      </v-card>
    </v-col>
  </v-row>
</template>
<script>
import { mapState, mapMutations } from 'vuex'
export default {
  layout: 'auth',
  middleware: ['guest'],
  data() {
    return {
      email: '',
      password: '',
    }
  },
  computed: {
    ...mapState({
      errors: state => state.alerts.errors,
    })
  },
  methods: {
    ...mapMutations({
      resetAlerts: 'alerts/RESET_ALERTS',
      setLoading: 'alerts/SET_LOADING',
      setSnackbar: 'alerts/SET_SNACKBAR',
      setErrors: 'alerts/SET_ERRORS'
    }),
    async login(){
      await this.$auth.loginWith('laravelSanctum', {
        data: {
          email: this.email,
          password: this.password
        }
      })
      .then(() => {
        this.$router.push('/panel')
      }).catch(error => {
        this.setErrors(error.response.data.errors)
      })
    }
  }
}
</script>
