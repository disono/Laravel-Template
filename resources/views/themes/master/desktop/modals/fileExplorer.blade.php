{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="modal-header">
    <h5 class="modal-title">File Explorer</h5>

    <button type="button" class="close dialogDismiss" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 col-md-8">
            {{--- tab --}}
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link fileExplorerNav" href="#" data-type="all"><i class="fas fa-th-list"></i> All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fileExplorerNav" href="#" data-type="photo"><i class="far fa-images"></i> Photos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fileExplorerNav" href="#" data-type="video"><i class="fas fa-video"></i> Videos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fileExplorerNav" href="#" data-type="audio"><i class="fas fa-volume-down"></i> Audios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fileExplorerNav" href="#" data-type="doc"><i class="fas fa-file-word"></i> Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fileExplorerNav" href="#" data-type="file"><i class="fas fa-archive"></i> Files</a>
                </li>
            </ul>

            {{--- search --}}
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search files..."
                       aria-label="Search Messages" aria-describedby="button-search-message"
                       id="fileExplorerSearchBar">
            </div>

            {{-- list --}}
            <div class="row text-center text-lg-left" id="fileSelectList"></div>
            <hr>

            {{-- pagination button --}}
            <div class="text-center">
                <a href="#" id="fileExplorerLoad" class="btn btn-light">Load more files...</a>

                <div class="spinner-border text-primary" role="status" id="fileExplorerLoading">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>

        {{-- uploader form --}}
        <div class="col-sm-12 col-md-4 border-left">
            <form action="{{ route('module.file.store') }}" id="fileUploaderFrm">
                <div class="form-group">
                    <label for="name">Title</label>
                    <input id="name" type="text"
                           class="form-control"
                           name="title" value="">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="file_selected">Select a file <strong class="text-danger">*</strong></label>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file_selected"
                               id="file_selected">
                        <label class="custom-file-label" for="file_selected">Choose file</label>
                    </div>
                </div>

                <hr>
                <button class="btn btn-primary btn-block" type="submit">Upload</button>
            </form>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light dialogDismiss">Dismiss</button>
    <button type="button" class="btn btn-primary dialogConfirm" style="display: none;">Select</button>
</div>