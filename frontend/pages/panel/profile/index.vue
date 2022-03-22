<template>
  <v-container>
    <v-row>
      <card-profile :selected-item="selectedItem" @changeItem="changeItem"></card-profile>
      <component :is="form[selectedItem]"></component>
    </v-row>
    <snackbar></snackbar>
  </v-container>
</template>
<script>
import FormProfile from '@/components/forms/FormProfile'
import CardProfile from '@/components/cards/Profile'
import FormPassword from '@/components/forms/FormPassword'
import Snackbar from '@/components/utils/Snackbar'
export default {
  name: 'Profile',
  components: {
    FormProfile,
    CardProfile,
    FormPassword,
    Snackbar
  },
  layout: 'panel',
  data(){
    return {
      selectedItem: 0,
      form: ['form-profile', 'form-password']
    }
  },
  methods: {
    changeItem(val){
      this.selectedItem = val
    }
  }
}
</script>
