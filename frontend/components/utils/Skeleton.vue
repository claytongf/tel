<template>
  <v-row>
    <v-col cols="12" md="3">
      <v-skeleton-loader :value="loading" elevation="2" type="card-heading, list-item-three-line@5, actions"></v-skeleton-loader>
    </v-col>
    <v-col cols="12" md="9">
      <h2 v-if="title">{{ title }}</h2>
      <v-skeleton-loader :value="loading" elevation="2" type="card-heading, list-item-three-line@3" class="mb-4"></v-skeleton-loader>
    </v-col>
  </v-row>
</template>
<script>
export default {
  name: 'Skeleton',
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: null
    }
  }
}
</script>
