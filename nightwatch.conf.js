const PKG = require('./package.json');
const BINPATH = './node_modules/nightwatch/bin/';
const SCREENSHOT_PATH = "./node_modules/nightwatch/screenshots/" + PKG.version + "/";

const config = {
  "src_folders": [
    "specs/e2e"    
  ],
  "output_folder": "./node_modules/nightwatch/reports",
  "selenium": {
    "start_process": true,
    "server_path": BINPATH + "selenium.jar",
    "log_path": "",
    "host": "127.0.0.1",
    "port": 4444,
    "cli_args": {
      "webdriver.chrome.driver" : BINPATH + "chromedriver"
    }
  },
  "test_workers" : {"enabled" : true, "workers" : "auto"},
  "test_settings": {
    "default": {
      "launch_url": "http://localhost",
      "selenium_port": 4444,
      "selenium_host": "127.0.0.1",
      "silent": true,
      "screenshots": {
        "enabled": true,
        "path": SCREENSHOT_PATH
      },
      "globals": {
        "waitForConditionTimeout": 15000
      },
      "desiredCapabilities": {
        "browserName": "chrome",
        "chromeOptions": {
          "args": [
            "--window-size=640,640"
          ]
        },
        "javascriptEnabled": true,
        "acceptSslCerts": true
      }
    },
  },
  "custom_commands_path" : "node_modules/nightwatch-custom-commands-assertions/js/commands",
  "custom_assertions_path" : "node_modules/nightwatch-custom-commands-assertions/js/assertions"
}
module.exports = config;

require('fs').stat(BINPATH + 'selenium.jar', function (err, stat) {
  if (err || !stat || stat.size < 1) {
    require('selenium-download').ensure(BINPATH, function(error) {
      if (error) throw new Error(error);
      console.log('✔ Selenium & Chromedriver downloaded to:', BINPATH);
    });
  }
});

function padLeft (count) {
  return count < 10 ? '0' + count : count.toString();
}

var FILECOUNT = 0;
/**
 * The default is to save screenshots to the root of your project even though
 * there is a screenshots path in the config object above! ... so we need a
 * function that returns the correct path for storing our screenshots.
 * While we're at it, we are adding some meta-data to the filename, specifically
 * the Platform/Browser where the test was run and the test (file) name.
 */
function imgpath (browser) {
  var a = browser.options.desiredCapabilities;
  var meta = [a.platform];
  meta.push(a.browserName ? a.browserName : 'any');
  meta.push(a.version ? a.version : 'any');
  meta.push(a.name);
  var metadata = meta.join('~').toLowerCase().replace(/ /g, '');
  return SCREENSHOT_PATH + metadata + '_' + padLeft(FILECOUNT++) + '_';
}

module.exports.imgpath = imgpath;
module.exports.SCREENSHOT_PATH = SCREENSHOT_PATH;
