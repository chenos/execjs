const path = require('path')

const rules = [
  {
    test: /\.js$/,
    exclude: /(node_modules|bower_components)/,
    use: {
      loader: 'babel-loader',
      options: {
        presets: ['react', ['env', { modules: false }]]
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
    filename: 'server.compiled.js'
  },
  externals: {
    'react': 'react',
    'react-router-dom': 'react-router-dom',
  },
  module: { rules },
}, {
  target: 'web',
  entry: {
    client: './js/client.js',
  },
  output: {
    path: path.join(__dirname, 'build'),
    filename: 'client.compiled.js'
  },
  resolve: {
    alias: {
      'react-router-dom': 'react-router-dom/umd/react-router-dom.js'
    }
  },
  externals: {},
  module: { rules },
}]
