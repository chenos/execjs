const app = require('./build/server.compiled.js')
let html = ReactDomServer.renderToString(app)

let template = `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    ${meta}
</head>
<body>
    <div id="app">${html}</div>
    <script src="build/client.compiled.js"></script>
</body>
</html>
`
