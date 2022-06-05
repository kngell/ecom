<div class="row comments_body reply-box" id="reply-box" style="display:none">
    <div class="d-flex col-md-12 reply_new_comment">
        <div class="flex-shrink-0 comment_header">
            <img src="{{image}}" width="48" alt="...">
        </div>
        <div class="flex-grow-1 ms-3 comments_body">
            <div class="reply-frm-wrapper" id="reply-frm-wrapper" data-comment-id="{{id}}">
                {{form_begin}}
                {{content}}
                <div class="reply-btn-group">
                    {{submit}}
                    {{cancel}}
                </div>
                {{form_end}}
            </div>
        </div>
        <div class="comments_footer">

        </div>
    </div>
</div>