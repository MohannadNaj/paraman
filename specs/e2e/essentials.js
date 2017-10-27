var conf = require('./../../nightwatch.conf.js')

module.exports = {
  'Open Parameters': function(browser) {
    browser
      .url('http://mohannad-dev.io:8000/parameters')
      .waitForElementVisible('#app')
  },
  'Add Category': browser => {
    browser.assert.containsText('.btn.btn-default.btn-sm', 'Edit') // edit categories button

    browser
      .waitForElementVisible('.btn.btn-default.btn-sm') // edit categories button
      .click('.btn.btn-default.btn-sm') // edit categories button
      .waitForElementVisible('.btn.btn-success.btn-sm') // add new
      .click('.btn.btn-success.btn-sm') //add new
      .waitForElementVisible('#modal form input[type="text"]') // category name
      .setValue('#modal form input[type="text"]', 'A headless category') // category name
      .click('#modal form .btn.btn-primary') //add new
      .click('.modal-header button')
      .pause(1000)
  },
  'Create Textfield Parameter': browser => {
    browser
      .click('.list-group .btn.btn-default.btn-sm') // edit categories button
      .pause(100)
      .click('.list-group .list-group-item:last-child') // last created category
      .waitForElementVisible('.col-sm-offset-1.btn.btn-primary') // submit parameter button
      .setValue(
        `input[placeholder="parameter_name"]`,
        `testing-${new Date().getTime()}`
      ) // parameter name
      .setValue(
        `input[placeholder="parameter_label"]`,
        `label of testing-${new Date().getTime()}`
      ) // parameter label
      .click('.col-sm-offset-1.btn.btn-primary') //submit parameter button
      .waitForElementVisible('.fa.fa-times-circle')
  },
  'Change Created Parameter Category': browser => {
    browser.click('.panel .panel-footer .col-xs-4:nth(2) button') // change category button
  }
}
