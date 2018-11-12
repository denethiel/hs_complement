<template>
<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1>HispaGamers</h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<h2><span>{{nameView}}</span></h2>

						<div class="inside">
							<router-view></router-view>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

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
							<p>Aqui iran otras cosas></p>
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
	export default {
		data(){
			return {
				activeIndex:'Home'
			}
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