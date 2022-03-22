<template>
  <v-dialog :value="open" :max-width="width" @click:outside="close">
    <v-card>
      <v-card-title class="text-h5 blue white--text"><v-icon class="white--text mr-2">fas fa-edit</v-icon>{{ formTitle }}</v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <template v-for="item in fields">
              <v-col v-if="item.type === 'text' || item.type === 'password' || item.type === 'email'" :key="item.field" :cols="item.cols">
                <v-text-field
                  v-model="formItem[item.field]"
                  :type="item.type"
                  :error="item.field in errors"
                  :error-messages="errors[item.field]"
                  :label="item.label">
                </v-text-field>
              </v-col>
              <v-col v-else-if="item.type === 'select'" :key="item.field" :cols="item.cols">
                <v-autocomplete
                  v-model="formItem[item.field]"
                  clearable
                  :error="item.field in errors"
                  :error-messages="errors[item.field]"
                  :label="item.label"
                  :items="item.options"
                  :item-text="'name'"
                  :item-value="'id'"
                  :multiple="multiple">
                </v-autocomplete>
              </v-col>
              <v-col v-else-if="item.type === 'checkbox'" :key="item.field" :cols="item.cols">
                <v-checkbox v-model="formItem[item.field]" :label="item.label"></v-checkbox>
              </v-col>
              <v-col v-else-if="item.type === 'radio'" :key="item.field" :cols="item.cols">
                <v-radio-group v-model="formItem[item.field]" :error="item.field in errors" :error-messages="errors[item.field]" row>
                  <template #label>
                    <div>{{ item.label }}</div>
                  </template>
                  <v-radio v-for="o in item.options" :key="o.value" :label="o.name" :value="o.value"></v-radio>
                </v-radio-group>
              </v-col>
            </template>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
        <v-btn color="blue darken-1" text :loading="loading" @click="save">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { mapState, mapMutations } from 'vuex';
export default {
  name: 'FormDialog',
  props: {
    fields: {
      required: true,
      type: Array
    },
    formTitle: {
      required: true,
      type: String
    },
    width: {
      required: false,
      type: String,
      default: '500px'
    },
    open: {
      default: false,
      type: Boolean
    },
    value: {
      type: Object,
      required:true
    },
    multiple: {
      type: Boolean,
      default: false
    },
    model: {
      type: String,
      required: true
    }
  },
  computed: {
    ...mapState ({
      errors: state => state.alerts.errors,
      loading: state => state.alerts.loading,
      alertStatus: state => state.alerts.status,
    }),
    formItem:{
      get(){
        return this.value
      },
      set(val){
        this.$emit('input', val)
      }
    }
  },
  watch: {
    open(val){
      val || this.close()
    }
  },
  methods: {
    ...mapMutations({
      resetAlerts: 'alerts/RESET_ALERTS',
      setLoading: 'alerts/SET_LOADING',
      setSnackbar: 'alerts/SET_SNACKBAR'
    }),
    close(){
      this.$emit('close')
    },
    async save () {
      this.setLoading(true)
      if (this.formItem.id !== "" && this.formItem.id !== undefined) { // edit
        await this.$store.dispatch(this.model + '/update', this.formItem)
      } else { // new
        delete this.formItem.id
        await this.$store.dispatch(this.model + '/store', this.formItem)
      }
      if(this.alertStatus === 'success'){
        this.close()
      }
      this.setLoading(false)
    },
  }
}
</script>
