{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}

<!-- Nav tabs -->
<ul class="nav nav-pills" role="tablist">
    <li role="presentation" class="active"><a href="#inbox_tab" aria-controls="inbox_tab" role="tab"
                                              data-toggle="tab"><i
                    class="fa fa-inbox" aria-hidden="true"></i> Messages</a></li>

    <li role="presentation"><a href="#inbox_new_message_tab" aria-controls="inbox_new_message_tab" role="tab" data-toggle="tab"><i
                    class="fa fa-plus" aria-hidden="true"></i> New Message</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="inbox_tab">
        <div style="height: 520px !important; padding-top: 8px; overflow: auto !important;" id="inbox_container">
            <h4 class="text-center">Loading inbox...</h4>
        </div>

        <p class="text-center"><a href="#" id="btn_inbox_load">Load More Messages...</a></p>
    </div>

    <div role="tabpanel" class="tab-pane" id="inbox_new_message_tab">
        <div style="height: 520px !important; padding-top: 8px !important; overflow: auto !important;" class="form-group">
            <input type="search" class="form-control" id="inbox_search_user" placeholder="Search for users...">

            <div id="inbox_new_message_container" style="padding-top: 8px !important;">
                <h4 class="text-center">Loading users...</h4>
            </div>
        </div>

        <p class="text-center"><a href="#" id="btn_user_load">Load More Users...</a></p>
    </div>
</div>