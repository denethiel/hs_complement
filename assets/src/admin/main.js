import Vue from 'vue'
import App from './App.vue'
import router from './router'
import menuFix from './utils/admin-menu-fix'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/es'

Vue.config.productionTip = false

Vue.use(ElementUI, { locale })

// eslint-disable-next-line no-new
new Vue({
  el: '#vue-admin-app',
  router,
  render: h => h(App)
})

menuFix('vue-admin-app')
