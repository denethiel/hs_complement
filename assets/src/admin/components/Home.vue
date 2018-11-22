<template>
	<div class="home">
		<div class="postbox">

		<h2><span>Twitter Media Sharing</span></h2>

			<div class="inside">
				<el-card class="box-card">
				  <div slot="header" class="clearfix">
				    <span>Card name</span>
				    <el-button style="float: right; padding: 3px 0" type="text">Operation button</el-button>
				  </div>
				  <div v-for="o in 4" :key="o" class="text item">
				    {{'List item ' + o }}
				  </div>
				</el-card>
				<el-card class="box-card">
				  <div slot="header" class="clearfix">
				    <span>Card name</span>
				    <el-button style="float: right; padding: 3px 0" type="text">Operation button</el-button>
				  </div>
				  <div v-for="o in 4" :key="o" class="text item">
				    {{'List item ' + o }}
				  </div>
				</el-card>
			</div>
						<!-- .inside -->

	</div>
		
	</div>
</template>
<script>
	export default {
		name: 'Home',
		data(){
			return{
				msg: 'Soy el bot dos veces we'
			}
		}
	}
</script>


<style scoped>
	.el-card{
		margin-bottom: 1em;
	}
</style>