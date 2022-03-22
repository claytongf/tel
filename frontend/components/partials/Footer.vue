<template>
  <v-footer :absolute="!fixed" padless app>
    <v-card width="100%" flat tile class="grey darken-2 text-center">
      <v-card-text class="white--text grey darken-3">
        <span>&copy; {{ new Date().getFullYear() }} - Ptel</span>
      </v-card-text>
    </v-card>
  </v-footer>
</template>

<script>
export default {
  name: 'SiteFooter',
  data () {
    return {
      fixed: false
    }
  }
}
</script>
