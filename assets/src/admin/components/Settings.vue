<template>
	<div class="app-settings">
		The Settigs Page
		<button @click.prevent="login">Login</button>
	</div>
</template>

<script>
	export default{
		name:'Settigs',
		data(){
			return{
			};
		},
		methods:{
			login:function(){
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
				
				this.$cb.__call(
					"oauth_requestToken",
					{oauth_callback:"http://localhost:3000/hs/wp-admin/admin.php?page=hispagamers#/settings"},
					function(reply, rate, err){
						if(err){
							console.log("error response " + err.error)
						}
						if(reply){
							console.log(reply)
							this.$cb.__call(
								'oauth_authorize',
								{},
								function(auth_url){
									location.href = auth_url
								}
							)
						}
					}
				)
			}
		}	
	}
</script>

<style lang="css" scoped>
	
</style>