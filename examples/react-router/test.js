var React = require('react')
var ReactDOMServer = require('react-dom/server')
var App = require('./build/server.compiled.js').default
var metaTagsInstance = require('./build/server.compiled.js').metaTagsInstance

for (var i = 0; i < 10000; i++) {
    var element = React.createElement(App, { location: '/react-router/about' })
    var html = ReactDOMServer.renderToString(element)
}

if (typeof print === 'undefined') {
    global.print = console.log
}
const meta = metaTagsInstance.renderToString();
print(meta)
print(html)
