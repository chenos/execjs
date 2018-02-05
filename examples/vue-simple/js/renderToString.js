const renderVueComponentToString = require('vue-server-renderer/basic')

module.exports = function (app) {
  return new Promise(function (resolve, reject) {
    renderVueComponentToString(app, function (err, html) {
      err ? reject(err) : resolve(html)
    })
  })
}
