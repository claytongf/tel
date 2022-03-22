<template>
  <v-col>
    <v-card outlined>
      <v-card-title>Meu Perfil</v-card-title>
      <v-card-text>
        <form>
          <v-row>
            <v-col cols="12">
              <label>Nome</label>
              <v-text-field v-model="form.name" type="text" placeholder="Nome" :error="'name' in errors" :error-messages="errors.name" required ></v-text-field>
              <label>E-mail</label>
              <v-text-field v-model="form.email" type="email" placeholder="E-mail" :error="'email' in errors" :error-messages="errors.email" required ></v-text-field>
              <label>PIS</label>
              <v-text-field v-model="form.pis" type="text" placeholder="PIS" :error="'pis' in errors" :error-messages="errors.pis" required ></v-text-field>
              <label>CPF</label>
              <v-text-field v-model="form.cpf" type="text" placeholder="CPF" :error="'cpf' in errors" :error-messages="errors.cpf" required ></v-text-field>
              <label>Cidade</label>
              <v-text-field v-model="form.city" type="text" placeholder="Cidade" :error="'city' in errors" :error-messages="errors.city" required ></v-text-field>
              <label>Estado</label>
              <v-text-field v-model="form.state" type="text" placeholder="Estado" :error="'state' in errors" :error-messages="errors.state" required ></v-text-field>
              <label>País</label>
              <v-text-field v-model="form.country" type="text" placeholder="País" :error="'country' in errors" :error-messages="errors.country" required ></v-text-field>
              <label>Endereço</label>
              <v-text-field v-model="form.street_address" type="text" placeholder="Endereço" :error="'street_address' in errors" :error-messages="errors.street_address" ></v-text-field>
              <label>Número</label>
              <v-text-field v-model="form.street_number" type="text" placeholder="Número" :error="'street_number' in errors" :error-messages="errors.street_number" ></v-text-field>
              <label>Complemento</label>
              <v-text-field v-model="form.street_compl" type="text" placeholder="Complemento" :error="'street_compl' in errors" :error-messages="errors.street_compl" ></v-text-field>
              <v-btn type="button" color="success" :loading="loading" @click.prevent="submit">Salvar</v-btn>
            </v-col>
          </v-row>
        </form>
      </v-card-text>
    </v-card>
  </v-col>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
export default {
  name: 'FormProfile',
  data(){
    return {
      form: {
        name: '',
        email: '',
        pis: '',
        cpf: '',
        city: '',
        state: '',
        country: '',
        street_address: '',
        street_number: '',
        street_compl: '',
      }
    }
  },
  computed: {
    ...mapState({
      user: state => state.auth.user.data,
      errors: state => state.alerts.errors,
      loading: state => state.alerts.loading,
      message: state => state.alerts.message
    })
  },
  mounted(){
    this.form = Object.assign({}, this.user)
    this.$store.commit('alerts/RESET_FORM_ERRORS')
  },
  methods: {
    ...mapMutations({
      setLoading: 'alerts/SET_LOADING',
    }),
    async submit(){
      this.setLoading(true)
      await this.$store.dispatch('user/updateProfile', this.form)
      this.setLoading(false)
    }
  }
}
</script>
