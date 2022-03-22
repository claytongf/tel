<template>
  <v-row justify="center" class="background">
    <v-col cols="12" md="10" class="my-8 py-5">
      <v-card>
        <v-card-title>
          <v-col cols="12" md="6">
            <v-text-field
              v-model="search"
              append-icon="fas fa-search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-btn color="blue darken-1" dark @click="clearSearch">Limpar Busca</v-btn>
          </v-col>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="users"
          :items-per-page="10"
          class="elevation-1"
          sort-by="name"
          :options.sync="options"
          :server-items-length="total"
          :loading="loading"
        >
          <template #[`item.is_active`]="{ item }">
            <v-icon v-if="item.is_active == true" color="green">fas fa-check-circle</v-icon>
            <v-icon v-else color="red">fas fa-times-circle</v-icon>
          </template>
          <template #top>
            <v-toolbar flat>
              <v-toolbar-title>Usuários Cadastrados</v-toolbar-title>
              <v-divider class="mx-4" inset vertical></v-divider>
              <v-spacer></v-spacer>
              <v-btn color="primary" dark class="mb-2" @click="openDialog"><v-icon class="mr-2">fas fa-plus</v-icon>Novo Usuário</v-btn>
              <form-dialog v-model="editedItem" model="user" :fields="fields" width="500px" :open="dialog" :form-title="formTitle" @close="close"></form-dialog>
              <delete-dialog :open="dialogDelete" width="550px" question="Você tem certeza em deletar este Usuário?" :content="editedItem.name" @close="closeDelete" @deleteItemConfirm="deleteItemConfirm"></delete-dialog>
            </v-toolbar>
          </template>
          <template #[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on"><v-icon class="mr-2" color="blue" @click="editItem(item)">fas fa-pencil-alt</v-icon></span>
              </template>
              <span>Editar {{ item.name }}</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on"><v-icon color="red" @click="deleteItem(item)">fas fa-trash-alt</v-icon></span>
              </template>
              <span>Deletar {{ item.name }}</span>
            </v-tooltip>
          </template>
        </v-data-table>
      </v-card>
    </v-col>
    <v-snackbar :value="snackbar" absolute top right :multi-line="true" :color="alertStatus" dark text @input="setSnackbar(false)">
      {{ message }}
      <template #action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="setSnackbar(false)">Fechar</v-btn>
      </template>
    </v-snackbar>
  </v-row>
</template>
<script>
import { mapState, mapMutations } from 'vuex'
import FormDialog from '@/components/dialogs/FormDialog'
import DeleteDialog from '@/components/dialogs/DeleteDialog'
import ProfileDialog from '@/components/dialogs/ProfileDialog'

export default {
  components: {
    FormDialog,
    DeleteDialog,
    ProfileDialog
  },
  layout: 'panel',
  data () {
    return {
      dialog: false,
      dialogDelete: false,
      dialogProfile: false,
      search: '',
      userRoles: [],
      options: {},
      headers: [
        { text: 'Nome', align: 'start', value: 'name' },
        { text: 'E-Mail', value: 'email' },
        { text: 'CPF', value: 'cpf' },
        { text: 'PIS', value: 'pis' },
        { text: 'País', value: 'country' },
        { text: 'Estado', value: 'state' },
        { text: 'Cidade', value: 'city' },
        { text: 'Actions', value: 'actions', sortable: false}
      ],
      fields: [],
      editedIndex: -1,
      editedItem: {
        id: '',
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        cpf: '',
        pis: '',
        country: '',
        state: '',
        city: '',
        street_address: '',
        street_number: '',
        street_comp: ''
      },
      defaultItem: {
        id: '',
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        cpf: '',
        pis: '',
        country: '',
        state: '',
        city: '',
        street_address: '',
        street_number: '',
        street_comp: ''
      },
    }
  },
  head(){
    return {
      title: 'Usuários'
    }
  },
  computed: {
    ...mapState({
      users: state => state.user.users,
      total: state => state.user.total,
      message: state => state.alerts.message,
      alertStatus: state => state.alerts.status,
      snackbar: state => state.alerts.snackbar,
      loading: state => state.alerts.loading
    }),
    formTitle() {
      return this.editedIndex === -1 ? 'Novo Usuário' : 'Editar Usuário'
    }
  },
  watch: {
    options: {
      handler(){
        this.getUsers()
      },
      deep: true
    },
    search(){
      this.getUsers()
    }
  },
  mounted(){
    this.setLoading(true)
    this.resetAlerts()
    this.fields = [
      { type: 'text', field: 'name', cols: '12', label: 'Nome'},
      { type: 'text', field: 'email', cols: '12', label: 'E-Mail'},
      { type: 'password', field: 'password', cols: '12', label: 'Senha'},
      { type: 'password', field: 'password_confirmation', cols: '12', label: 'Confirme sua Senha'},
      { type: 'text', field: 'cpf', cols: '6', label: 'CPF'},
      { type: 'text', field: 'pis', cols: '6', label: 'PIS'},
      { type: 'text', field: 'city', cols: '6', label: 'Cidade'},
      { type: 'text', field: 'state', cols: '2', label: 'Estado'},
      { type: 'text', field: 'country', cols: '4', label: 'País'},
      { type: 'text', field: 'street_address', cols: '6', label: 'Endereço'},
      { type: 'text', field: 'street_number', cols: '3', label: 'Número'},
      { type: 'text', field: 'street_comp', cols: '3', label: 'Complemento'},
    ]
  },
  methods: {
    ...mapMutations({
      resetAlerts: 'alerts/RESET_ALERTS',
      setLoading: 'alerts/SET_LOADING',
      setSnackbar: 'alerts/SET_SNACKBAR'
    }),
    openDialog(){
      this.dialog = true
    },
    async getUsers(){
      this.setLoading(true)
      await this.$store.dispatch('user/getUsers', {search: this.search, ...this.options})
      this.setLoading(false)
    },
    editItem (item) {
      this.editedIndex = this.users.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },
    editProfile (item){
      this.editedIndex = item.id
      this.editedItem = Object.assign({}, item)
      this.dialogProfile = true
    },
    deleteItem (item) {
      this.editedIndex = item.id
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    closeProfile(){
      this.dialogProfile = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    closeDelete () {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    async deleteItemConfirm () {
      this.setLoading(true)
      await this.$store.dispatch('user/delete', this.editedIndex)
      this.closeDelete()
      this.setLoading(false)
    },
    clearSearch(){
      if(this.search.length > 0){
        this.search = ''
        this.getUsers()
      }
    }
  }
}
</script>
