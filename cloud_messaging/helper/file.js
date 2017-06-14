var fs = require('fs');
var _logFile = __dirname + '/../storage/files/log.txt';

function _log(message) {
    fs.readFile(_logFile, 'utf8', function (err, data) {
        if (err) {
            if (err) {
                console.log('readFile: ' + err);
            }
        }

        // message
        message = (typeof message === "object" && !Array.isArray(message) && message !== null) ? JSON.stringify(message) : message;

        fs.writeFile(_logFile, ((data) ? data + '\n' : '') + new Date() + ' ' + message, function (err) {
            if (err) {
                console.log('writeFile: ' + err);
            }
        });
    });
}

function _createFileLog(message) {
    fs.open(_logFile, "wx", function (err, fd) {
        // handle error
        if (err) {
            console.log('openFile: ' + err);
        }

        fs.close(fd, function (err) {
            // handle error
            if (err) {
                console.log('closeFile: ' + err);
            }

            _log(message);
        });
    });
}

module.exports = {
    log: function (message) {
        if (fs.existsSync(_logFile)) {
            _log(message);
        } else {
            _createFileLog(message);
        }
    }
};