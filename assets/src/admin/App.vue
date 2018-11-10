<template>
	<div id="hispagamers">
    <el-container>
      <h2>HispaGamers Twitter Bot</h2>
      <el-header>
        <el-menu
          :default-active="activeIndex"
          class="el-menu"
          mode="horizontal"
          @select="handleSelect"
          background-color="#545c64"
          text-color="#fff"
          active-text-color="#ffd04b">
          <el-menu-item index="url" router-link to="/">Bot</el-menu-item>
          <el-menu-item index="settings" router-link to="/settings">Configuracion</el-menu-item>
        </el-menu>
      </el-header>
      <el-main>
        <router-view/>
      </el-main>
    </el-container>
    
</div>
</template>

<script>
	export default {
		data(){
			return {
				activeIndex:'url'
			}
		},
		methods:{
			handleSelect(key, keyPath){
				switch(key){
					case 'url':
						$this.$router.push('/')
						break
					case 'settings':
						$this.$router.push('/settings')
						break
				}
			}
		},
		name: 'App'
	}
</script>

<style>
	
</style>