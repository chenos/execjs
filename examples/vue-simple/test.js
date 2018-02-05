var Vue = require('vue')
var Hello = require('./js/hello.js')
var renderToString = require('./js/renderToString.js')

if (Hello.__esModule) Hello = Hello.default

async function output() {
    var html, app
    for (var i = 0; i < 100000; i++) {
        // app = new Hello({propsData: {msg: 'Vue'}})
        app = app = new Vue({
            render: h => h(Hello, { props: {msg: 'Vue'} })
        });
        html = await renderToString(app)
    }
    return html
}

output().then(html => {})
// output().then(typeof print === 'undefined' ? console.log : print)
