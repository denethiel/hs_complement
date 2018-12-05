<template>
	<el-card class="box-card">
		<div slot="header" class="clearfix">
			<span class="title">{{streamer.name}}
			<div v-if="streamer.live" style="float: right; padding: 3px 0"><el-tag type="success">En vivo</el-tag></div>
			<div v-else style="float: right; padding: 3px 0"><el-tag type="info">Desconectado</el-tag></div>
			</span>
			
		</div>
		<el-row :gutter="20">
			<el-col :span="6">
				<img v-bind:src="streamer.avatar" class="image">
			</el-col>
			<el-col :span="6">
				<li>{{streamer.twitch_user}}</li>
				<li>{{streamer.twitch_id}}</li>
				<li>{{streamerTime}}</li>
			</el-col>
		</el-row>
	</el-card>
</template>
<script>
	export default{
		name:'Streamer',
		props:['streamer'],
		data(){
			return{
				
			}
		},
		computed:{
			streamerTime:function(){
				if(this.streamer.last_updated === ''){
					return "Nunca"
				}else{


				let d = new Date(this.streamer.last_updated.date)
				//return d.getTime()<

				var seconds = Math.floor((new Date() - d) / 1000);

			  var interval = Math.floor(seconds / 31536000);

			  if (interval > 1) {
			    return "Hace " + interval + " años";
			  }
			  interval = Math.floor(seconds / 2592000);
			  if (interval > 1) {
			    return "Hace " + interval + " meses";
			  }
			  interval = Math.floor(seconds / 86400);
			  if (interval > 1) {
			    return "Hace " + interval + " dias";
			  }
			  interval = Math.floor(seconds / 3600);
			  if (interval > 1) {
			    return "Hace " + interval + " horas";
			  }
			  interval = Math.floor(seconds / 60);
			  if (interval > 1) {
			    return "Hace " + interval + " minutos";
			  }
			  return "Hace "+ Math.floor(seconds) + " segundos";
			  }
			}
		},
	}
</script>
<style scoped>
	.title{
		font-weight: bold;
	}
</style>