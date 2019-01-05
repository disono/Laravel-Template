{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

{{--
Compose Message & Group
--}}
<div class="row mb-3">
    <div class="col-5"><h4>Messaging</h4></div>
    <div class="col" style="text-align: right !important;">
        <button class="btn btn-primary" v-on:click="btnChatCreateGroupModal"><i class="fas fa-user-friends"></i></button>
        <button class="btn btn-primary" v-on:click="btnChatWriteMessageModal"><i class="fas fa-edit"></i></button>
    </div>
</div>

{{--
 Search Messages
 --}}
<form>
    <div class="input-group input-group mb-3">
        <input type="text" class="form-control" placeholder="Search Messages"
               aria-label="Search Messages" aria-describedby="button-search-message">

        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="button-search-message">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</form>

{{--
Messages List
--}}
<div class="list-group">
    <a href="#" class="list-group-item list-group-item-action active">
        <div class="media">
            <img src="{{ url('assets/img/placeholders/profile_picture.png') }}" class="align-self-start mr-3" style="width: 22% !important;">
            <div class="media-body">
                <h5 class="mt-0">Top-aligned media</h5>
                <p>
                    Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.
                </p>
            </div>
        </div>
    </a>
</div>