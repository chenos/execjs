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
      }
    }
  }
]

module.exports = {
  target: 'node',
  entry: {
    hello: './js/Hello.vue',
  },
  output: {
    libraryTarget: 'commonjs2',
    path: path.join(__dirname, 'build'),
    filename: 'hello.js',
  },
  externals: {
    'vue': 'vue',
  },
  module: { rules },
}