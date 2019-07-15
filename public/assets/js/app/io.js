/**
 * @author              Archie Disono (webmonsph@gmail.com)
 * @link                https://github.com/disono/Laravel-Template
 * @lincense            https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright           Webmons Development Studio
 */

let WBSocketIO = {
    io: null,
    settingResults: {},
    appName: null,
    connected: null,

    construct(callback) {
        this.settings().then(callback);
        return this;
    },

    builder() {
        return this.settings();
    },

    subscribe(name, callback) {
        this.io.on(this.subscribeTo(name), callback);
    },

    subscriptions(subscribers) {
        let self = this;
        subscribers.forEach((client) => {
            self.subscribe(client.name, client.callback);
        });
    },

    addSubscriber(name, callback) {
        let self = this;
        let isConnected = setInterval(() => {
            if (isConnected && self.connected) {
                clearInterval(isConnected);

                self.subscribe(name, callback);
            }
        }, 5000);
    },

    subscribeTo(name) {
        return name === null ? this.appName + '_' + this.settingResults.token.token : this.appName + '_' + this.settingResults.token.token + '_' + name;
    },

    publish(to, data) {
        this.io.emit(this.appName + '_onServerSubscribe', {to: to, data: data});
    },

    initialize() {
        let self = this;

        return new Promise((resolve, reject) => {
            self.appName = self.settingResults.settings.socketIOAppName.value;
            self.io = io(self.settingResults.settings.socketIOServer.value, {
                transportOptions: {
                    polling: {
                        extraHeaders: {
                            'authorization': 'Bearer ' + self.settingResults.token.jwt,
                            'source': 'client',
                            'app-name': self.settingResults.settings.socketIOAppName.value,
                            'tkey': self.settingResults.token.key,
                            'uid': self.settingResults.me.profile.id
                        }
                    }
                }
            });

            self.io.on('connect', () => {
                WBHelper.console.log('OnConnect: ' + self.io.id);
                self.connected = self.io.id;
                resolve(self);
            });

            self.io.on('disconnect', () => {
                WBHelper.console.error('onDisconnect');
            });
        });
    },

    settings() {
        let self = this;

        return WBServices.raw.get('/application/settings').then(response => {
            if (response.data.socketIO.value !== 'enabled') {
                throw "SocketIO is not enabled.";
            }

            self.settingResults.settings = response.data;
            return WBServices.raw.get('/u/me');
        }).then(response => {
            self.settingResults.me = response.data;
            return WBServices.raw.get('/u/security/token');
        }).then(response => {
            self.settingResults.token = response.data;
            return self.initialize();
        });
    },
};