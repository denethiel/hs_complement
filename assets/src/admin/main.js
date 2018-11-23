import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import menuFix from './utils/admin-menu-fix'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/es'
import CodeBird from '../lib/Twitter'
import Axios from '../lib/Axios'

Vue.config.productionTip = false

Vue.use(ElementUI, { locale })
Vue.use(CodeBird)
Vue.use(Axios)

Vue.mixin({
	computed: {
		apiUrl :function (){
			return HG_WP.rest.base + HG_WP.rest.hgBase
		},
		nonce :function(){
			return HG_WP.rest.nonce
		}
	}
})

// eslint-disable-next-line no-new
new Vue({
  el: '#vue-admin-app',
  router,
  store,
  render: h => h(App),
  created(){
  	this.$store.dispatch('getConfiguration')
  	this.$store.dispatch('getStreamers')
  }
})

menuFix('vue-admin-app')
