const path = require('path')
const VueLoaderPlugin = require('vue-loader/lib/plugin')

var entryPoint = {
	public:'./assets/src/public/main.js',
	admin:'./assets/src/admin/main.js',
};

var exportPath = path.resolve(__dirname, './assets/js');
module.exports = {
	entry: entryPoint,
	output:{
		path:exportPath,
		filename:'[name].js'
	},
	module:{
		rules:[
			{
				test: /\.vue$/,
				loader: 'vue-loader'
			},
			{
				enforce: 'pre',
				test: /\.(js|vue)$/,
				loader: 'eslint-loader',
				exclude: /node_modules/
			},
			{
				test: /\.js$/,
				exclude: file => (
          			/node_modules/.test(file) &&
          			!/\.vue\.js/.test(file)
				),
				use:{
                	loader:'babel-loader',
                	options:{
                		presets: ['@babel/preset-env']
                	}
                }
			},
			{
				test: /\.less$/,
				use: [
					'vue-style-loader',
					'css-loader',
					'less-loader'
				]
			}
		]
	},
	plugins: [
    	new VueLoaderPlugin()
	]

}