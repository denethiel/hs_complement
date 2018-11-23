<template>
	<div class="home">
		<div class="postbox">

		<h2><span>Twitter Media Sharing</span></h2>

			<div class="inside">
				
				<streamer v-for="streamer in streamers"
						v-bind:key="streamer.wp_id"
						v-bind:streamer="streamer"></streamer>
			</div>
						<!-- .inside -->

	</div>
		
	</div>
</template>
<script>
	import { mapState } from 'vuex'
	import Streamer from './Streamer.vue'
	export default {
		name: 'Home',
		components:{
			streamer: Streamer
		},
		data(){
			return{
				msg: 'Soy el bot dos veces we'
			}
		},
		computed: mapState([
			'streamers'
		])
	}
</script>


<style scoped>
	.el-card{
		margin-bottom: 1em;
	}
</style>