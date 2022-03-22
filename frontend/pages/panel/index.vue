<template>
  <v-row justify="center">
    <v-col cols="12" md="8" class="my-8 py-5">
      Olá, {{ user.name }}!
    </v-col>
  </v-row>
</template>
<script>
import { mapState } from 'vuex'
export default {
  layout: 'panel',
  computed: {
    ...mapState({
      user: state => state.auth.user.data
    })
  },
}
</script>
