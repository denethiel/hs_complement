import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'


const WP = {
	url : HG_WP.rest.base + HG_WP.rest.hgBase,
	nonce : HG_WP.rest.nonce
}
const wordpress_api = axios.create({
	baseURL:WP.url,
	headers:{'X-WP-Nonce':WP.nonce}
})



Vue.use(Vuex)

export default new Vuex.Store({
	state: {
		twitter_form:{
			consumerKey:'',
			consumerKeySecret:'',
			accessToken:'',
			accessTokenSecret:''
		},
		twitch_form:{
			client_id:''
		}
	},
	getters:{
		getTwitterConfig: state => {
			return state.twitter_form
		},
		getTwitchConfig: state => {
			return state.twitch_form
		}
	},
	mutations: {

	},
	actions: {
		getConfiguration({commit, state}) {
			console.log(WP.url)
			wordpress_api.get('settings').then(response => {
				console.log(response)
			})
		}
	}
})