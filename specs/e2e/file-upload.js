var conf = require('./../../nightwatch.conf.js')
var parameterName = `testing-${new Date().getTime()}`

module.exports = {
  'Open Parameters': function(browser) {
    browser
      .url('http://mohannad-dev.io:8000/parameters')
      .waitForElementVisible('#app')
  },
  'Chose Category': browser => {
    browser
      .waitForElementVisible('.categories--list') // edit categories button
      .click('.categories--list__item:first-child') // edit categories button
      .waitForElementVisible('.parameters-list--button__add') // add new
      .click('.parameters-list--button__add') //add new
  },
  'Fill Input': browser => {

    browser
      .waitForElementVisible('.addParameter--button__submit') // edit categories button
      .setValue(
        `.addParameter--form-input__name`,
        `${parameterName}`
      ) // parameter name
      .setValue(
        `.addParameter--form-input__label`,
        `label of ${parameterName}`
      ) // parameter label
      .setValue(
        `select.addParameter--form-input__type`,
        `file`
      ) // parameter type
      .submitForm('.addParameter--form')
  },
  'Open Edit Parameter Modal': browser => {
    var editSelector = `.parameter:contains('${parameterName}') .parameter--button_edit`

    browser
      .waitForJqueryElement(editSelector)
      .jqueryClick(editSelector)
      .waitForElementVisible('#dropzone_upload.modal.in')
      .pause(3000)
      .execute("document.querySelectorAll('.dz-hidden-input')[0].style = {};")
      .pause(100)
      .setValue('.dz-hidden-input',require('path').resolve(__dirname + '/../setup/e2e-upload-file.txt'))
      .pause(1000)
      .execute(`document.querySelectorAll(#dropzone_upload.modal.in .btn.btn-primary')[0].click();`)
      .pause(1000)
  },
  'End': browser => {
    browser.end()
  }
}
