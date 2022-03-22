<template>
  <v-app light>
    <top-menu></top-menu>
    <v-main class="mt-20">
      <v-container fluid>
        <Nuxt />
      </v-container>
    </v-main>
    <site-footer></site-footer>
  </v-app>
</template>
<script>
import TopMenu from '@/components/partials/TopMenu'
import SiteFooter from '@/components/partials/Footer'
export default {
  name: 'Panel',
  components: {
    TopMenu,
    SiteFooter
  },
  data () {
    return {
      fixed: false,
      title: 'Ptel'
    }
  }
}
</script>
<style>
  .mt-20{
    margin-top: 120px;
  }
</style>
