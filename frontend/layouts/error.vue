<template>
  <v-app dark class="text-center mt-5">
    <h1 v-if="error.statusCode === 404">
      {{ error.statusCode }} - {{ pageNotFound }}
    </h1>
    <h1 v-else-if="error.statusCode === 401">
      {{ error.statusCode }} - {{ unauthorized }}
    </h1>
    <h1 v-else>
      {{ error.statusCode }} - {{ otherError }}
    </h1>
    <NuxtLink to="/">
      Home page
    </NuxtLink>
  </v-app>
</template>

<script>
export default {
  layout: 'panel',
  props: {
    error: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      pageNotFound: 'Página não encontrada',
      unauthorized: 'Você não está autorizado a acessar essa página',
      otherError: 'Ocorreu um erro. Por favor, entre em contato com o suporte.'
    }
  },
  head () {
    const title =
      this.error.statusCode === 404 ? this.pageNotFound : (this.error.statusCode === 401 ? this.unauthorized : this.otherError)
    return {
      title
    }
  }
}
</script>

<style scoped>
h1 {
  font-size: 20px;
}
</style>
