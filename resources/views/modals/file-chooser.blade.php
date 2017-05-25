<div class="modal fade modal-file-chooser" style="z-index: 100000 !important;" tabindex="-1" role="dialog" aria-labelledby="modalFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Files</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-8">
                        <h5>Files Uploaded</h5>

                        {{-- list of files --}}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody id="fileListUploaded"></tbody>
                                </table>
                            </div>
                        </div>

                        <p class="text-center"><a href="#" id="loadMoreFiles">Load More...</a></p>
                    </div>

                    <div class="col-sm-12 col-md-4">
                        <h5>Upload</h5>

                        {{-- form to upload files --}}
                        <form action="#" method="post" role="form" enctype="multipart/form-data" id="frmChooser">
                            <div class="form-group">
                                <label for="chooserFile">Select File</label>
                                <input type="file" class="form-control" name="file" id="chooserFile">
                            </div>

                            <div class="form-group">
                                <label for="titleFile">Title*</label>
                                <input type="text" class="form-control" name="title" id="titleFile">
                            </div>

                            <div class="form-group">
                                <label for="descriptionFile">Description</label>
                                <input type="text" class="form-control" name="description" id="descriptionFile">
                            </div>

                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>