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
		}
	}
</script>
<style scoped>
	.title{
		font-weight: bold;
	}
</style>