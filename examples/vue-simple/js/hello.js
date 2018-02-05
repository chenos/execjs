const Vue = require('vue')

module.exports = Vue.extend({
  template: `<div class="hello">
    <h1 class="hello__title">Hello {{ msg }}!</h1>
  </div>`,
  props: ['msg'],
})
