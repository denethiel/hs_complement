const merge = require('webpack-merge')
const common = require('./webpack.common.js')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

module.exports = merge(common,{
  mode: 'development',
  devtool: 'eval-source-map',
  plugins: [
    new BrowserSyncPlugin({
      proxy: {
        target: 'http://hispagamers.localhost/wp-admin/admin.php?page=hispagamers'
      },
      files: [
        '**/*.php'
      ],
      cors: true,
      reloadDelay: 0
    })
  ]
})
