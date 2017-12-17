{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}

{{-- Nav tabs --}}
<ul class="nav nav-fill nav-pills" id="tabInbox" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="inbox-tab" data-toggle="tab" href="#inbox" role="tab" aria-controls="inbox"
           aria-selected="true">
            <i class="fa fa-inbox" aria-hidden="true"></i>
            <small>Inbox</small>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" id="new_message-tab" data-toggle="tab" href="#new_message" role="tab"
           aria-controls="new_message"
           aria-selected="false">
            <i class="fa fa-send" aria-hidden="true"></i>
            <small>New Message</small>
        </a>
    </li>
</ul>

{{-- Tab panes --}}
<div class="tab-content" id="tabInboxContent">
    <div class="tab-pane fade show active mt-3" id="inbox" role="tabpanel" aria-labelledby="inbox-tab">
        <div style="height: 520px !important; padding-top: 8px; overflow: auto !important;" id="inbox_container">
            <h4 class="text-center">Loading inbox...</h4>
        </div>

        <p class="text-center"><a href="#" id="btn_inbox_load">Load More Messages...</a></p>
    </div>

    <div class="tab-pane fade mt-3" id="new_message" role="tabpanel" aria-labelledby="new_message-tab">
        <div style="height: 520px !important; overflow: auto !important;">
            <div class="form-group p-1">
                <input type="search" class="form-control" id="inbox_search_user" placeholder="Search for users...">
            </div>

            <div id="inbox_new_message_container" style="padding-top: 8px !important;">
                <h4 class="text-center">Loading users...</h4>
            </div>
        </div>

        <p class="text-center"><a href="#" id="btn_user_load">Load More Users...</a></p>
    </div>
</div>