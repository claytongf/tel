<template>
  <v-dialog :value="open" :max-width="width" @click:outside="close">
    <v-card>
      <v-card-title class="text-h5 green white--text"><v-icon class="white--text mr-2">fas fa-user-edit</v-icon>{{ title }}</v-card-title>
      <v-card-subtitle class="my-5 text-h6">{{ subtitle }}</v-card-subtitle>
      <v-card-text>
        <v-autocomplete :value="editable" :items="attributes" chips deletable-chips :label="label" multiple :item-text="'name'" :item-value="'id'" v-on="$listeners"></v-autocomplete>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
        <v-btn color="blue darken-1" text @click="saveProfile">Salvar</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: 'ProfileDialog',
  props: {
    attributes: {
      required: true,
      type: Array
    },
    editable: {
      required: true,
      type: Array
    },
    label: {
      type: String,
      default: ''
    },
    title: {
      required: true,
      type: String
    },
    subtitle: {
      default: '',
      type: String
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
  data(){
    return {
      editAttr: []
    }
  },
  methods: {
    saveProfile(){
      this.$emit('saveProfile')
    },
    close(){
      this.editAttr = []
      this.$emit('close')
    }
  }
}
</script>
