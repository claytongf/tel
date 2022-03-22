<template>
  <v-dialog :value="open" :max-width="width" @click:outside="close">
    <v-card>
      <v-card-title class="text-h5 red white--text"><v-icon class="white--text mr-2">fas fa-exclamation-triangle</v-icon> {{ question }}</v-card-title>
      <v-card-text class="text-h6 my-3">{{ content }}</v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red darken-1" dark @click="close">Cancelar</v-btn>
        <v-btn color="blue darken-1" dark @click="deleteItemConfirm">Remover</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: 'DeleteDialog',
  props: {
    question: {
      type: String,
      required: true
    },
    content: {
      type: String,
      default: ''
    },
    open: {
      default: false,
      type: Boolean
    },
    width: {
      required: false,
      type: String,
      default: '500px'
    },
  },
  methods: {
    deleteItemConfirm(){
      this.$emit('deleteItemConfirm')
    },
    close(){
      this.$emit('close')
    }
  }
}
</script>
