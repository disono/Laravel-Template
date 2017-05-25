<div class="modal fade modal-mf-chooser" style="z-index: 100000 !important;" tabindex="-1" role="dialog" aria-labelledby="modalMFLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Image</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                	<div class="col-sm-12 col-md-8">
                        <h5>Images Uploaded</h5>
                        <hr>

                        {{-- list of files --}}
                        <div class="row">
                            <div id="imageListUploaded"></div>
                        </div>

                        <p class="text-center"><a href="#" id="loadMoreImageFiles">Load More...</a></p>
                	</div>

                    <div class="col-sm-12 col-md-4">
                        <h5>Upload</h5>
                        <hr>

                        {{-- form to upload images --}}
                        <form action="#" method="post" role="form" enctype="multipart/form-data" id="frmMFChooser">
                        	<div class="form-group">
                        		<label for="chooserMFFile">Select Image</label>
                        		<input type="file" class="form-control" name="image" id="chooserMFFile">
                        	</div>

                        	<button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-primary" id="selectMFYes"><i class="fa fa-check"></i> Select</button>
            </div>
        </div>
    </div>
</div>