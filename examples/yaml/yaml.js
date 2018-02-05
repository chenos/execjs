var jsyaml = require('js-yaml')

for (var i = 0; i < 100000; i++) {
    jsyaml.load('greeting: hello\nname: world');
    jsyaml.dump({
        foo: 'bar',
        bar: {
            foo: 'bar',
            bar: 'baz',
        },
    })
}
