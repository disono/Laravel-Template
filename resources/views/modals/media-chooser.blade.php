<div class="modal-header">
    <h5 class="modal-title">Select Media File</h5>
    <button type="button" class="close dialogDismiss" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body" id="mediaChooser">
    <div class="row" style="text-align: left !important;">
        <div class="col-8" style="max-height: 550px !important; overflow: auto !important;">
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th>File</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(item, index) in list">
                        <td scope="row">
                            <img v-if="item.file_type === 'image'" :src="item.path" :alt="item.title"
                                 class="img-fluid img-thumbnail">
                            <img v-if="item.file_type !== 'image'" :src="item.icon" :alt="item.title"
                                 class="img-fluid img-thumbnail">
                        </td>
                        <td>@{{ item.title }}</td>
                        <td>@{{ item.file_type }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary selected_media"
                                        :data-id="item.id" :data-src="item.path"
                                        :data-title="item.title">Select
                                </button>
                                <button type="button" class="btn btn-danger"
                                        v-on:click.prevent="deleteItem(item.id, index)"
                                        :data-id="item.id"
                                        :data-title="item.title"
                                        :data-src="item.path">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <p class="text-center" v-if="list.length"><a href="#" v-on:click.prevent="fetchData()">Load more...</a>
                </p>
            </div>
        </div>

        <div class="col-4">
            <form v-on:submit.prevent="onSubmit">
                <div class="form-group">
                    <label for="title">Title(Optional)</label>
                    <input type="text" class="form-control" id="title" placeholder="Title" v-model="formInputs.title">
                </div>

                <div class="form-group">
                    <label for="file">File*</label>
                    <input type="file" class="form-control" id="file" @change="uploadInput($event.target)">
                </div>

                <div class="form-group">
                    <label for="description">Description(Optional)</label>
                    <textarea type="text" class="form-control" id="description"
                              placeholder="Description" v-model="formInputs.description"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn dialogDismiss">Cancel</button>
    <button type="button" class="btn btn-primary dialogConfirm">Select</button>
</div>

<script>
    var _mediaData = {};
    _mediaData.list = [];
    _mediaData.currentPage = 1;
    _mediaData.formInputs = {
        title: null,
        description: null,
        file: null
    };

    var _mediaMethods = {};
    _mediaMethods.uploadInput = function (target) {
        this.formInputs.file = target.files;
    };
    _mediaMethods.onSubmit = function () {
        const formData = new FormData();
        var thisApp = this;

        for (var k in this.formInputs) {
            if (this.formInputs.hasOwnProperty(k)) {
                if (k === 'file') {
                    if (!this.formInputs[k]) {
                        return;
                    }

                    formData.append(k, this.formInputs[k][0]);
                } else {
                    formData.append(k, this.formInputs[k]);
                }
            }
        }

        _appMethods.httpPost('/media-file/store', formData)
            .then(function (response) {
                _debugging(JSON.stringify(response));
                thisApp.list.unshift(response.data);
                thisApp.formInputs = {
                    title: null,
                    description: null,
                    file: null
                };
            })
            .catch(function (error) {
                _debugging(JSON.stringify(error));
            });
    };
    _mediaMethods.fetchData = function () {
        var thisApp = this;
        var _mediaType = '';
        if (_appData.mediaType === 'media') {
            _mediaType = 'video';
        } else if (_appData.mediaType === 'image') {
            _mediaType = 'image';
        }

        _appMethods.httpGet('/media-files', {
            page: thisApp.currentPage,
            file_type: _mediaType
        })
            .then(function (response) {
                response.data.forEach(function (value, key) {
                    thisApp.list.push(response.data[key]);
                });

                if (response.data.length) {
                    thisApp.currentPage++;
                }

                jQ(document).on('click', '.selected_media');
            })
            .catch(function (error) {
                _debugging(JSON.stringify(error));
            });
    };
    _mediaMethods.deleteItem = function (id, index) {
        _debugging(id + ' ' + index);
        var thisApp = this;

        _appMethods.dialogs('delete', null, function (r) {
            _appMethods.httpDelete('/media-file/destroy/' + id)
                .then(function (response) {
                    thisApp.list.splice(index, 1);
                })
                .catch(function (error) {

                });
        }, function (r) {

        });
    };

    new Vue({
        el: '#mediaChooser',
        props: ['data-id', 'data-src', 'data-title'],
        mounted: function () {
            this.fetchData();
        },
        data: _mediaData,
        methods: _mediaMethods
    });
</script>