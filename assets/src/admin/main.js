import Vue from 'vue'
import App from './App.vue'
import router from './router'
import menuFix from './utils/admin-menu-fix'

Vue.config.productionTip = false

new Vue({
	el:'#vue-admin-app',
	router,
	render: h => h(App)
});

menuFix('vue-app');