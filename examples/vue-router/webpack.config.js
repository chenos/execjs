const path = require('path')

const rules = [
  {
    test: /\.vue$/,
    use: {
      loader: 'vue-loader',
      options: {}
    }
  },
  {
    test: /\.js$/,
    exclude: /(node_modules|bower_components)/,
    use: {
      loader: 'babel-loader',
      options: {
        presets: [
          ['env', { modules: false }]
        ],
        plugins: ['syntax-dynamic-import']
      }
    }
  }
]

module.exports = [{
  target: 'node',
  entry: {
    server: './js/server.js',
  },
  output: {
    libraryTarget: 'commonjs2',
    path: path.join(__dirname, 'build'),
    filename: 'server.compiled.js',
    // publicPath: 'js/',
  },
  externals: {
    'vue': 'vue',
    'vue-router': 'vue-router',
  },
  module: { rules },
}, {
  target: 'web',
  entry: {
    client: './js/client.js',
  },
  output: {
    path: path.join(__dirname, 'build'),
    filename: 'client.compiled.js',
    publicPath: 'build/',
  },
  resolve: {
    alias: {}
  },
  externals: {},
  module: { rules },
}]