<template>
  <v-snackbar :value="snackbar" top right :timeout="timeout" :color="color" @input="close">
    <v-icon v-if="color === 'success' || color === 'primary'" class="mr-2">fas fa-check</v-icon>
    <v-icon v-else class="mr-2">fas fa-exclamation-triangle</v-icon>
    {{ message }}
    <template #action="{ attrs }">
      <v-btn color="blue" text v-bind="attrs" @click="close">Fechar</v-btn>
    </template>
  </v-snackbar>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'Snackbar',
  props: {
    text: {
      type: String,
      default: ''
    },
    timeout: {
      type: Number,
      default: 5000
    }
  },
  computed: {
    ...mapState({
      snackbar: state => state.alerts.snackbar,
      color: state => state.alerts.status,
      message: state => state.alerts.message
    })
  },
  methods: {
    close(){
      this.$store.commit('alerts/SET_SNACKBAR', false)
    }
  }
}
</script>
