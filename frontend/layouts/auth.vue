<template>
  <v-app light >
    <v-main>
      <v-container fill-height fluid class="background-login">
        <v-row justify="center" align="center">
          <v-col cols="12" sm="7" md="4">
            <Nuxt />
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'Auth'
}
</script>
<style>
  .background-login{
    background-color: #41B9C7
  }
</style>
