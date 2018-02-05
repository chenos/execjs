module.exports = {
  target: 'node',
  entry: {
    app: './index.js',
  },
  output: {
    libraryTarget: 'commonjs2', // !different
    path: __dirname,
    filename: 'liquid.js'
  },
  externals: {
    'fs': 'fs',
    'path': 'path',
    'resolve-url': 'resolve-url',
  },
  module: {
    loaders: [
      {
        test: /\.js$/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [['env', { modules: false }]]
          },
        },
      },
    ]
  },
}
