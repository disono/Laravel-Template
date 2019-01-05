{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

{{--
Profile & Group Details
--}}
<div class="row">
    <div class="col-md-6 col-sm-12">
        <h3>User One</h3>
        <p>2hr ago</p>
    </div>
</div>

{{--
Messages List
--}}
<div class="list-group">
    <div class="list-group-item list-group-item-action">
        <div class="media">
            <img src="{{ url('assets/img/placeholders/profile_picture.png') }}" class="rounded align-self-start mr-3" style="width: 12% !important;">
            <div class="media-body">
                <h6 class="mt-0">Top-aligned media - <small>August 22 2019 11:00 AM</small></h6>
                <p>
                    Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.
                </p>
            </div>
        </div>
    </div>
</div>

{{--
Form for sending messages
--}}
<form action="">
    <div class="row mt-3">
        <div class="col">
            <textarea name="message" id="message" rows="2" class="form-control"></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-sm-12 mt-3">
            <div class="btn-group" role="group" aria-label="Toolbar">
                <button class="btn btn-secondary"><i class="fas fa-image"></i></button>
                <button class="btn btn-secondary"><i class="fas fa-paperclip"></i></button>
            </div>
        </div>

        <div class="col-md-2 col-sm-12 mt-3">
            <button class="btn btn-primary btn-block">Send</button>
        </div>
    </div>
</form>