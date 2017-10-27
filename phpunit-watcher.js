let files = ['src/**/*.php',
            'tests/**/*.php',
            'app/**/*.php'];

let cmd = '"./vendor/bin/phpunit"';

let chokidarOptions = {};

let chokidar = require('chokidar');

// debounce from: https://stackoverflow.com/a/24004942/4330182
let debounce = (func, wait, immediate) => {
    var timeout;           

    return function() {
        var context = this, 
            args = arguments;

        var callNow = immediate && !timeout;

        clearTimeout(timeout);   

        timeout = setTimeout(function() {

             timeout = null;

             if (!immediate) {
               func.apply(context, args);
             }
        }, wait);

        if (callNow) func.apply(context, args);  
     }; 
};

let handleOutput = (error, stdout, stderr) => {
      if (error) {
          console.error(`exec error: ${error}`);
      }
/*      console.log(`${stdout}`);
      console.log(`${stderr}`);*/
  };
 
 let eventInfoStructure = (path) => { return [
    ['\x1b[92m','____________________'],
    ['\x1b[39m','------------'],
    ['\x1b[39m','|', '\x1b[34m',
      new Date().toISOString().replace(/T/, ' ').replace(/\..+/, '')
      ],
    ['\x1b[39m' , '| File: ', '\x1b[32m', path],
    ['\x1b[39m' , '------------'],
 ];
};

var countFileChanges = [];
var lastChangedFile = "";

let eventInfo = (path) => {
    eventInfoStructure(path).forEach((line) => {
      console.log.apply(console, line);
    })
};

let getFilters = (_path) => {

  let duplicates = countFileChanges.filter(i => countFileChanges.filter(ii => ii === i).length > 2)

  if(duplicates[0] != undefined)
    return '';

  var uniqueFileChanges = countFileChanges.filter((elem, pos) => {
    return countFileChanges.indexOf(elem) == pos;
  });

  if(uniqueFileChanges.length != 1 || _path.toLowerCase().indexOf('tests') == -1)
    return '';

  //console.log(_path, lastChangedFile);
  if(_path.substr(-8) != "Test.php" && lastChangedFile.substr(-8) == "Test.php")
    _path = lastChangedFile;

  var changedFile = path.parse(_path).name;
  var phpunitFilter = ` --filter ${changedFile}`;

  console.log(phpunitFilter);
  return phpunitFilter;
};

let clearToGo = true;
let queue = [];

let runNext = (e) => {

};

let execute = debounce((path) => {
  console.log('running phpunit..');

  clearToGo = false;

  var execProcess = exec(cmd + getFilters(path) , handleOutput);

  execProcess.on('exit', (x) => {clearToGo = true;});

  execProcess.stdout.pipe(process.stdout);

  countFileChanges = [];

}, 1000);

let handleChange = (_path) => {
    eventInfo(_path);
    countFileChanges.push(_path);
//    console.log(_path.substr(-8));
    if(_path.substr(-8) == "Test.php")
      lastChangedFile = _path;

    execute(_path);
};

const exec = require('child_process').exec;

const watcher = chokidar.watch(files, chokidarOptions);

// Event listeners.
watcher
  .on('change', handleChange);
