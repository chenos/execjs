var fs = require('fs')
var md = require('markdown-it')();

var data = fs.readFileSync(__dirname+'/demo.md');

for (var i = 0; i < 10000; i++) {
  md.render(data.toString())
}
