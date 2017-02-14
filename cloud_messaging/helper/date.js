module.exports = {
    sqlDateTime: function (date) {
        if (date) {
            new Date(date).toISOString().slice(0, 19).replace('T', ' ');
        }

        return new Date().toISOString().slice(0, 19).replace('T', ' ');
    }
};