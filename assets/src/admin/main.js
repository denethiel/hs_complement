import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import menuFix from './utils/admin-menu-fix'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/es'
import CodeBird from '../lib/Twitter'

Vue.config.productionTip = false

Vue.use(ElementUI, { locale })
Vue.use(CodeBird)

// eslint-disable-next-line no-new
new Vue({
  el: '#vue-admin-app',
  router,
  store,
  mounted: function () {
    console.log('Mounted')
    this.$cb.setConsumerKey("3wtIwM9t089junsWLjEzEbvEi","wX2rFcIMQFnEIuMiNdnb1Ge8WsDcRDuVCENuWxTPFEOgSh2qph")
  },
  render: h => h(App)
})

menuFix('vue-admin-app')
