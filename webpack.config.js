const path = require('path');
const webpack = require('webpack');

var config = {
	entry: {
      main: [
      	'./resources/assets/js/main.js'
      ],
      page_timer: [
      	'./resources/assets/js/page_timer.js', 
      	'./resources/assets/js/vue/app_timer.js',
      	'./resources/assets/js/vue/app_history.js',
      	'./resources/assets/js/vue/app_stopwatch.js',
      ],
      page_timer_edit: [
      	'./resources/assets/js/page_timer_edit.js',  
      ],
      page_report: [
        './resources/assets/js/page_report.js',  
      ],
      page_user: [
        './resources/assets/js/page_user.js',  
      ]
  },

  output: {
      path: path.join(__dirname, "public/dist"),
      filename: "[name].js"
  },

  module: {
    loaders: [
      {
        test: /\.js$/, 
        loader: 'babel-loader',
        exclude: /(node_modules|bower_components)/,
        query: { presets: ['es2015'] }
      },
      { 
      	include: /\.json$/, 
      	loaders: ["json-loader"] 
      },
      {
        test: /\.css$/,
        use: [ 'style-loader', 'css-loader' ]
      }
    ]
  },

  resolve: {
	  alias: {
	    'vue$': 'vue/dist/vue.common.js',
	    jquery: "jquery/src/jquery",
	    jQuery: "jquery/src/jquery",
	    'jquery.smartmenus': 'smartmenus/src/jquery.smartmenus.js',
	  },
	  extensions: ['.json', '.jsx', '.js'],
	},

	plugins: []
};

module.exports = config;