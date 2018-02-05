var fs = require('fs')
var marked = require('./marked.options.js')

var data = fs.readFileSync(__dirname+'/demo.md');

for (var i = 0; i < 10000; i++) {
  marked(data.toString())
}
