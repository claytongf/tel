<template>
  <v-col cols="12" md="3">
    <v-card color="#41B9C7" dark>
      <v-card-title>{{ user.name }}</v-card-title>
      <v-card-text>
        <v-list dense color="#41B9C7">
          <v-list-item-group :value="selectedItem" color="primary" @change="changeItem">
            <v-list-item v-for="(item, i) in items" :key="i" >
              <v-list-item-icon>
                <v-icon v-text="item.icon"></v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title v-text="item.text"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card-text>
    </v-card>
  </v-col>
</template>
<script>
import { mapState } from 'vuex'
export default {
  name: 'CardProfile',
  props: {
    selectedItem: {
      type: Number,
      default: 0
    }
  },
  data(){
    return {
      items: [
        { text: 'Minha Conta', icon: 'fas fa-user'},
        { text: 'Alterar Senha', icon: 'fas fa-lock'},
      ]
    }
  },
  computed: {
    ...mapState({
      user: state => state.auth.user.data
    })
  },
  methods: {
    changeItem(val){
      this.$emit('changeItem', val)
    }
  }
}
</script>
