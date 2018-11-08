const webpack = require('webpack');
const path = require('path');
const package = require('./package.json');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
//const ExtractTextPlugin = require('extract-text-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin')

const devMode = process.env.WEBPACK_ENV !== 'production'

const config = require('./config.json');

var appName = 'app';
var entryPoint = {
	public:'./assets/src/public/main.js',
	admin:'./assets/src/admin/main.js',
	vendor: Object.keys(package.dependencies),
	style:'./assets/less/style.less',
};

var exportPath = path.resolve(__dirname, './assets/js');


var plugins = [];

var env = process.env.WEBPACK_ENV;

function isProduction() {
	return process.env.WEBPACK_ENV === 'production';
}

// const extractCss = new ExtractTextPlugin({
// 	filename:"../css/[name].css",
// });

const extractCss = new MiniCssExtractPlugin({
	filename: devMode ? '[name].css' : '[name].[hash].css',
	chunkFilename: devMode ? '[id].css':'[id].[hash].css',
})

plugins.push(extractCss);

// Extract all 3rd party modules into a separate 'vendor' chunk
// plugins.push(new webpack.optimize.CommonsChunkPlugin({
// 	name:'vendor',
// 	minChunks:({ resource }) => /node_modules/.test(resource),
// }));


plugins.push(new BrowserSyncPlugin({
	proxy:{
		target: config.proxyURL
	},
	files:[
		'**/*.php'
	],
	cors:true,
	reloadDelay:0
} ));


// Generate a 'manifest' chunk to be inlined in the HTML template
// plugins.push(new webpack.optimize.CommonsChunkPlugin('manifest'));

// Compress extracted CSS. We are using this plugin so that possible
// duplicated CSS from different components can be deduped.

plugins.push(new OptimizeCSSPlugin({
    cssProcessorOptions: {
        safe: true,
        map: {
            inline: false
        }
    }
}));

plugins.push(new VueLoaderPlugin())

// Differ settings based on production flag
if ( isProduction() ) {

    plugins.push(new UglifyJsPlugin({
        sourceMap: true,
    }));

    plugins.push(new webpack.DefinePlugin({
        'process.env': env
    }));

    appName = '[name].min.js';
} else {
    appName = '[name].js';
}

module.exports = {
	mode: 'none',
    entry: entryPoint,
    output: {
        path: exportPath,
        filename: appName,
        chunkFilename: 'chunks/[chunkhash].js',
        jsonpFunction: 'pluginWebpack'
    },

    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            '@': path.resolve('./assets/src/'),
            'public': path.resolve('./assets/src/public/'),
            'admin': path.resolve('./assets/src/admin/'),
        },
        modules: [
            path.resolve('./node_modules'),
            path.resolve(path.join(__dirname, 'assets/src/')),
        ]
    },

    optimization:{
    	splitChunks:{
    		cacheGroups:{
    			default: false,
    			vendors: false,
    			vendor:{
    				chunks: 'all',
    				test: /node_modules/
    			}
    		}
    	}
    },

    plugins,

        module: {
        rules: [
            {
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use:{
                	loader:'babel-loader',
                	options:{
                		presets: ['@babel/preset-env']
                	}
                }
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    extractCSS: true
                }
            },
            {
                test: /\.less$/,
                use: [
                	devMode ? 'vue-style-loader' : MiniCssExtractPlugin.loader,
                	'css-loader',
                	'less-loader'
                ],
            },
            {
                test: /\.css$/,
                use:[
                	'vue-style-loader',
                	'css-loader'
                ]
            }
        ]
    },
}