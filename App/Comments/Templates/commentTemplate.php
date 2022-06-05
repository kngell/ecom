<div class="comment" data-id="{{id}}">
    <div class="d-flex">
        <div class="flex-shrink-0 comment_header">
            <img src="{{image}}" width="48" alt="...">
        </div>
        <div class="flex-grow-1 ms-3 comment_body">
            <div class="user">{{name}}&nbsp;&minus;&nbsp;<span class="time">{{date}}</span>
            </div>
            <div class="comment_content_text">
                <p class="content"> {{comment_content}}</p>
            </div>

        </div>
        <div class="comment_footer">

        </div>
    </div>
    <div class="replies" id="replies">
        <div class="reply">
            <div class="icon thumbs-up"></div>&nbsp;<div class="icon thumbs-down"></div>&nbsp;<div class=""><a
                    href="javascript:void(0)" class="reply-link">REPLY</a></div>
        </div>
        {{nestedComment}}
    </div>


</div>