{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
<p class="text-center"><a href="#" id="btn_reading_load">Load More...</a></p>

<div style="height: 520px !important; overflow: auto !important;" id="reading_container"></div>

<br>
<div class="panel-footer" id="footer_message">
    <div class="input-group">
        <input id="btn_chat_text_input" type="text" class="form-control input-sm" placeholder="Type your message here..."/>
        <input type="file" style="display: none;" id="file_upload_message">

        <span class="input-group-btn">
            <button class="btn btn-sm" id="btn_chat_upload"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
            <button class="btn btn-sm" id="btn_chat_text">Send</button>
        </span>
    </div>
</div>