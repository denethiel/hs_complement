import Vue from 'vue'
import App from './App.vue'

Vue.config.productionTip = false

new Vue({
	el: '#hg-stream-app',
	render: h => h(App)
})