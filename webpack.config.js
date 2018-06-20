const webpack = require('webpack')
const path = require('path')
const LodashModuleReplacementPlugin = require('lodash-webpack-plugin')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')
const p = process.env.NODE_ENV === 'production'

module.exports = {
  target: 'web',
  devtool: 'source-map',
  entry: path.join(__dirname, 'scripts/app.js'),
  output: {
    path: path.join(__dirname, 'assets'),
    filename: 'index.js'
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.js?$/,
        loader: 'standard-loader',
        exclude: /node_modules/,
        options: {
          parser: 'babel-eslint'
        }
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        include: path.join(__dirname, 'scripts'),
        loaders: ['babel-loader']
      }
    ]
  },
  resolve: {
    alias: {
      micromanagerRoot: path.join(__dirname, 'scripts'),
      components: path.join(__dirname, 'scripts/', 'components'),
      pages: path.join(__dirname, 'scripts/', 'pages'),
      templates: path.join(__dirname, 'scripts/', 'templates'),
      lib: path.join(__dirname, 'scripts/', 'lib')
    }
  },
  plugins: p ? [
    new webpack.NoEmitOnErrorsPlugin(),
    new LodashModuleReplacementPlugin(),
    new UglifyJsPlugin(),
    new webpack.optimize.OccurrenceOrderPlugin()
  ] : []
}
