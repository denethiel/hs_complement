<template>
	<div class="bot">
		<el-switch
		  style="display: block"
		  v-model="running"
		  active-color="#13ce66"
		  inactive-color="#ff4949"
		  active-text="Bot Activo"
		  inactive-text="Bot Desactivado">
		</el-switch>

	</div>
</template>
<script>
	import { mapState } from 'vuex'
	export default{
		name:'Twitch',
		data(){
			return{
				
			}
		},
		computed:{
			running:{
				get() {return this.$store.state.running},
				set(value){ this.$store.dispatch('toogleBot', value)}
			}
		},
		
	}
</script>