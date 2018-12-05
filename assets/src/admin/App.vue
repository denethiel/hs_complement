<template>
<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1>{{nameView}}</h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<router-view></router-view>
					

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h2><span></span></h2>

						<div class="inside">
							<bot></bot>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->
</template>

<script>
	import Bot from './components/Bot.vue'
	export default {
		data(){
			return {
				activeIndex:'Home'
			}
		},
		components:{
			bot: Bot
		},
		computed:{
			nameView(){
				return this.$route.name
			}
		},
		methods:{},
		name: 'App'
	}
</script>

<style>
	
</style>