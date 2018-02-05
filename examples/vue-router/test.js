var renderToString = require('./js/renderToString.js')
var createApp = require('./build/server.compiled.js').default

async function output() {
    let app, html
    for (var i = 0; i < 500; i++) {
        app = await createApp({url: '/vue-router/'})
        html = await renderToString(app)
    }
    for (var i = 0; i < 500; i++) {
        app = await createApp({url: '/not-found/'})
        html = await renderToString(app)
    }
    return html
}

output().then(h => {})
