import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import { Notification } from 'element-ui';

const WP = {
  url : HG_WP.rest.base + HG_WP.rest.hgBase,
  nonce: HG_WP.rest.nonce
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
		twitter_form: state => {
			return state.twitter_form
		},
		twitch_form: state => {
			return state.twitch_form
		}
	},
	mutations: {
		updateTwitterConfiguration(state, config) {
			state.twitter_form = config
		},
		updateTwitchConfiguration(state, config){
			state.twitch_form = config
		}
	},
  actions: {
		getConfiguration({commit, state}) {
			wordpress_api.get('settings').then(response => {
				
				var twitter = {
					consumerKey: response.data.data.hg_twitter_consumerKey,
					consumerKeySecret: response.data.data.hg_twitter_consumerKeySecret,
					accessToken: response.data.data.hg_twitter_accessToken,
					accessTokenSecret: response.data.data.hg_twitter_accessTokenSecret
				}
				var twitch =  {
					client_id: response.data.data.hg_twitch_client_id
				}
				commit("updateTwitterConfiguration", twitter)
				commit("updateTwitchConfiguration", twitch)

			})
		},
		setConfiguration({commit, state}){
			wordpress_api.post('settings',{
				hg_twitter_consumerKey: state.twitter_form.consumerKey,
				hg_twitter_consumerKeySecret: state.twitter_form.consumerKeySecret,
				hg_twitter_accessToken: state.twitter_form.accessToken,
				hg_twitter_accessTokenSecret: state.twitter_form.accessTokenSecret,
				hg_twitch_client_id: state.twitch_form.client_id
			}).then(response => {
				console.log(response)
				Notification({
		          title: 'Exito',
		          message: 'Configuracion guardada con exito.',
		          type: 'success',
		          offset: 40
		        });
				commit("updateTwitterConfiguration", state.twitter_form)
				commit("updateTwitchConfiguration", state.twitch_form)
			})
		}
	}
})