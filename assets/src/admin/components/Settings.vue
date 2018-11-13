<template>
	<div id="settings">
	<div class="postbox">

		<h2><span>Twitter API Settings</span></h2>

			<div class="inside">
				<el-form ref="form" :model="twitter_form" label-width="200px">
					<el-form-item label="Consumer Key">
						<el-input v-model="twitter_form.consumerKey"></el-input>
					</el-form-item>
					<el-form-item label="Consumer Key Secret">
						<el-input v-model="twitter_form.consumerKeySecret"></el-input>
					</el-form-item>
					<el-form-item label="Access Token">
						<el-input v-model="twitter_form.accessToken"></el-input>
					</el-form-item>
					<el-form-item label="Access Token Secret">
						<el-input v-model="twitter_form.accessTokenSecret"></el-input>
					</el-form-item>
					<el-form-item>
					    <el-button type="primary" @click="onSubmit">Guardar</el-button>
					  </el-form-item>
				</el-form>
			</div>
						<!-- .inside -->

	</div>
	<div class="postbox">

		<h2><span>Twitch API Settings</span></h2>

			<div class="inside">
				<el-form ref="form" :model="twitch_form" label-width="200px">
					<el-form-item label="Consumer Key">
						<el-input v-model="twitch_form.client_id"></el-input>
					</el-form-item>
					<el-form-item>
					    <el-button type="primary" @click="onSubmit">Guardar</el-button>
					  </el-form-item>
				</el-form>
			</div>
						<!-- .inside -->

	</div>				<!-- .postbox -->
	</div>
</template>

<script>
	import Twitch from './Twitch.vue'
	import { mapGetters } from 'vuex';
	export default{
		name:'Settigs',
		data(){
			return{
				//twitter_form:null,
				//twitch_form:null
			};
		},
		computed:{
			...mapGetters(['twitter_form','twitch_form'])
		},
		methods:{
			onSubmit(){
				this.$store.dispatch('setConfiguration', this.twitter_form, this.twitch_form)
			},
			open() {
		       this.$notify({
		          title: 'Success',
		          message: 'This is a success message',
		          type: 'success',
		          offset: 100
		        });
		    },
			login:function(){
			  this.$cb.setConsumerKey("3wtIwM9t089junsWLjEzEbvEi","wX2rFcIMQFnEIuMiNdnb1Ge8WsDcRDuVCENuWxTPFEOgSh2qph")
			  this.$cb.setToken("545681441-fU4221vVytWXICaJOPCOU5akXP93im5lh8GBcVeW", "239yrmdwOyrO8sQnpnQ6Ql0HdEZ3SEujjABCGVyvFLdrx");
			  this.$cb.__call("statuses_update", { status: "Whohoo, I just tweeted!" }, function(
			  reply,
			  rate,
			  err
			) {
			  // ...
			});
				// this.$cb.__call("oauth2_token", {}, function(reply, err) {
				// var bearer_token;
				// if (err) {
				// 	console.log("error response or timeout exceeded" + err.error);
				// }
				// if (reply) {
				// 	console.log(reply);
				// 	bearer_token = reply.access_token;
				// }
				// });
				// var self = this
				// this.$cb.__call(
				// 	"oauth_requestToken",
				// 	{oauth_callback:"http://localhost:3000/hs/wp-admin/admin.php?page=hispagamers#/settings"},
				// 	//{oauth_callback:"http://hispagamers.com/wp-admin/admin.php"},
				// 	function(reply, rate, err){
				// 		if(err){
				// 			console.log("error response " + err.error)
				// 		}
				// 		if(reply){
				// 			console.log(reply)
				// 			this.$cb.__call(
				// 				'oauth_authorize',
				// 				{},
				// 				function(auth_url){
				// 					location.href = auth_url
				// 				}
				// 			)
				// 		}
				// 	}
				// )
			}
		}	
	}
</script>

<style lang="css" scoped>
	.inside{
		width: 90%;
	}
</style>