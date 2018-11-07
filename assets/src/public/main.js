
var Vue = require('vue');
var App = require('./App.vue');

Vue.config.productionTip = false;

new Vue({
	el: '#hg-stream-app',
	data:{
		message:'Hello Vue.js'
	}
})