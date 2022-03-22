<template>
  <div>
    <v-app-bar color="white" fixed>
      <v-spacer></v-spacer>
      <v-img :src="require('@/assets/imgs/logo.png')" contain max-height="50" max-width="100"></v-img>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-menu open-on-hover offset-y>
        <template #activator="{ on, attrs }">
          <v-btn v-bind="attrs" tile v-on="on">Olá, {{ user.name }}</v-btn>
        </template>
        <v-list>
          <v-list-item v-for="(item, index) in usermenu" :key="index" @click="goTo(item.link)">
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
    <v-app-bar dark color="#41B9C7" fixed class="fixed-2">
      <v-app-bar-nav-icon color="white" @click="drawNavigator"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <nuxt-link v-for="m in menubar" :key="m.title" :to="m.to" class="mx-3 unstyle-menu white--text"><v-icon small>{{ m.icon }}</v-icon> {{ m.title }}</nuxt-link>
      <v-spacer></v-spacer>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" color="#41B9C7" dark absolute temporary>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title>
            <v-img :src="require('@/assets/imgs/logo.png')" contain max-height="50" max-width="100"></v-img>
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider></v-divider>
      <v-list dense>
        <template v-for="item in items">
          <v-list-item v-if="!item.hasSubmenus" :key="item.title" :to="item.link" link>
            <v-list-item-icon>
              <v-icon small>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-group v-else :key="item.title" :value="true">
            <template #prependIcon>
              <v-icon small>{{ item.icon }}</v-icon>
            </template>
            <template #activator>
              <v-list-item-content>
                <v-list-item-title>{{ item.title }}</v-list-item-title>
              </v-list-item-content>
            </template>
            <template v-for="menu in item.links">
              <v-list-item v-if="!menu.hasSubmenus" :key="menu.title" :to="menu.link" link>
                <v-list-item-icon>
                  <v-icon small>{{ menu.icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-title>{{ menu.title }}</v-list-item-title>
              </v-list-item>
              <v-list-group v-else :key="menu.title" no-action sub-group>
                <template #activator>
                  <v-list-item-content>
                    <v-list-item-title>{{ menu.title }}</v-list-item-title>
                  </v-list-item-content>
                </template>
                <v-list-item v-for="(submenu, i) in menu.links" :key="i" :to="submenu.link" link>
                  <v-list-item-title>{{ submenu.title }}</v-list-item-title>
                  <v-list-item-icon>
                    <v-icon small>{{ submenu.icon }}</v-icon>
                  </v-list-item-icon>
                </v-list-item>
              </v-list-group>
            </template>
          </v-list-group>
        </template>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>
<script>
import { mapState } from 'vuex'
export default{
  data(){
    return {
      drawer: false,
      usermenu: [
        { title: 'Meu Perfil', link: '/panel/profile' },
        { title: 'Logout', link: 'logout' }
      ],
      menubar : [
        { title: 'Home', icon: 'fas fa-home', to: '/panel' },
        { title: 'Meu Perfil', icon: 'fas fa-user', to: '/panel/profile' },
        { title: 'Listar Usuários', icon: 'fas fa-home', to: '/admin/users' },

      ],
      items: [
        { title: 'Home', icon: 'fas fa-home', hasSubmenus: false, link: '/panel' },

        {title: 'Meu Perfil', icon: 'fas fa-user', link: '/panel/profile'},
        {title: 'Listar Usuários', icon: 'fas fa-users', link: '/admin/users'},
      ],
    }
  },
  computed: {
    ...mapState({
      user: state => state.auth.user.data
    })
  },
  methods:{
    drawNavigator(){
      this.drawer = true
    },
    goTo(link){
      if(link !== 'logout'){
        this.$router.push(link)
      }else{
        this.logout()
      }
    },
    async logout(){
      await this.$auth.logout()
      this.$router.push('/')
    },
  }
}
</script>
<style>
  .unstyle-menu{
    text-decoration: none;
  }
  .v-list-item--active{
    color: white !important;
  }
  .v-application--is-ltr .v-list-group--no-action.v-list-group--sub-group > .v-list-group__items > .v-list-item {
    padding-left: 30px !important;
  }
  .fixed-2{
    margin-top: 64px !important;
  }
  @media (max-width: 959px) {
    .fixed-2{
      margin-top: 56px !important;
    }
  }
</style>
