import Vue from 'vue'

Vue.directive('can', function(el, bindings, vnode) {
  const permission = bindings.arg ? bindings.arg + ' ' + bindings.value : bindings.value
  const userPermissions = vnode.context.$store.state.auth.user.data.permissions
  if(!userPermissions.includes(permission)){
    commentNode(el, vnode)
  }
})

Vue.prototype.$can = function(permission){
  const userPermissions = this.$store.state.auth.user.data.permissions
  if(!userPermissions.includes(permission)){
    throw this.$nuxt.error({ statusCode: 401, message: 'Não autorizado' })
  }
}

function commentNode(el, vnode) {
  const comment = document.createComment(' ')

  Object.defineProperty(comment, 'setAttribute', {
    value: () => undefined
  })

  vnode.text = ' '
  vnode.elm = comment
  vnode.isComment = true
  vnode.context = undefined
  vnode.tag = undefined
  vnode.data.directives = undefined

  if (vnode.componentInstance) {
    vnode.componentInstance.$el = comment
  }

  if (el.parentNode) {
    el.parentNode.replaceChild(comment, el)
  }
}
