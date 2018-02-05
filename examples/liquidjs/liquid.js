var Liquid = require('liquidjs')

if (typeof PHP === 'undefined') {
    var print = console.log
} else {
    var __dirname = PHP.__ROOT__
}

var engine = Liquid({
    root: __dirname + '/views',
    extname: '.html',
    cache: true,
})

async function output() {
    var html
    for (var i = 0; i < 1000; i++) {
        html = await engine.renderFile('hello.html', {name: 'Liquid'})
    }
    return html
}

output().then(print).catch(print)
