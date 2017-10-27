TestData = require('./testData.json')
moxios = require('moxios')
sinon = require('sinon')
Promise = require('promise-polyfill')
if (!window.Promise) {
  window.Promise = Promise
}
require('./testUtils.js')
require('./fakeDataHelper.js')

window.Laravel = TestData.clientData

require('../../resources/assets/js/core.js')
require('../../resources/assets/js/bootstrap.js')
Vue.config.productionTip = false
