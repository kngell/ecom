<section class="justify-content-center" id="comments_section">
    <div class="container py-4 w-75" id="comments-section-wrapper">
        <div class="row ">
            <div class="comments_header">
                <div class="title">
                    <h2><span class="totalComments" id="totalComments">{{TotalComments}}</span>&nbsp;Comments</h2>
                </div>
                <div class="sort_by">Sort By</div>
            </div>
        </div>
        {{addCommentForm}}
        {{replyCommentForm}}
        <div class="row">
            <div class="col-md-12">
                <div class="users_comments" id="users_comments">
                    {{commentTemplate}}
                </div>
            </div>

        </div>
    </div>

</section>