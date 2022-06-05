<div class="row comments_body" id="add_box">
    <div class="d-flex col-md-12 add_new_comment">
        <div class="flex-shrink-0 comment_header">
            <img src="{{image}}" width="48" alt="...">
        </div>
        <div class="flex-grow-1 ms-3 comments_body">

            <div class="add-frm-wrapper" id="add-frm-wrapper" data-comment-id="{{id}}">
                {{form_begin}}
                {{content}}
                {{submit}}
                {{form_end}}
            </div>
        </div>
        <div class="comments_footer">

        </div>
    </div>
</div>