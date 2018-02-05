var React = require('react')
var ReactDOMServer = require('react-dom/server')
var App = require('./build/server.compiled.js').default

for (var i = 0; i < 10000; i++) {
    var element = React.createElement(App, { location: '/react-router/' })
    var html = ReactDOMServer.renderToString(element)
}

// if (typeof print === 'undefined') {
//     global.print = console.log
// }

// print(html)
