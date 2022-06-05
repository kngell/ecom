import { Call_controller } from "corejs/form_crud";
import input from "corejs/inputErrManager";
class Comments {
  constructor(element) {
    this.element = element;
  }
  _init = () => {
    this._setupVariables();
    this._setupEvents();
  };
  _setupVariables = () => {
    this.wrapper = this.element.find("#comments-section-wrapper");
    this.commentsWrapper = this.element.find("#users_comments");
    this.addBox = this.element.find("#add_box");
    this.addFrm = this.element.find("#add-comment-frm");
    this.repyfrm = this.element.find("#reply-comment-frm");
    this.replyBox = this.element.find("#reply-box");
  };
  _setupEvents = () => {
    var phpCmt = this;
    /**
     * remove invalid input on focus
     */
    input.removeInvalidInput(phpCmt.addFrm);
    input.removeInvalidInput(phpCmt.repyfrm);

    /**
     * Click to Reply link
     */
    phpCmt.wrapper.on("click", ".reply-link", function (e) {
      e.preventDefault();
      phpCmt.replyBox.insertAfter($(this).parents(".reply").after());
      phpCmt.replyBox.show();
    });

    phpCmt.wrapper.on("click", "#replyCancelBtn", function (e) {
      e.preventDefault();
      phpCmt.replyBox.find("textarea").val("");
      phpCmt.replyBox.hide();
    });
    /**
     * Submit new Comment
     */
    phpCmt.wrapper.on(
      "submit",
      "#reply-comment-frm, #add-comment-frm",
      function (e) {
        e.preventDefault();
        let parentID = 0;
        $(this).find("button").html("Please wait...");
        if (phpCmt.repyfrm.parents(".comment").length > 0) {
          parentID = phpCmt.replyBox.closest(".comment").attr("data-id");
        }
        const data = {
          url: "comments",
          frm: $(this),
          table: "comments",
          frm_name: $(this).attr("id"),
          parent_id: parentID,
          content_id: $(this).find("textarea").attr("id"),
          max: phpCmt.wrapper.find("#totalComments").html(),
          params: $(this),
        };
        Call_controller(data, (response, elt) => {
          elt.attr("id") == "add-comment-frm"
            ? elt.find("button").html("COMMNET")
            : elt.find("button").html("REPLY");
          if (response.result == "success") {
            if (elt.attr("id") == "add-comment-frm") {
              phpCmt.commentsWrapper.first().prepend(response.msg.comment);
              phpCmt.wrapper.find("#totalComments").text(response.msg.nbCmt);
              elt.find("textarea").val("");
            } else {
              elt.parents(".reply").last().append(response.msg.comment);
              phpCmt.wrapper.find("#totalComments").text(response.msg.nbCmt);
              phpCmt.replyBox.find("textarea").val("");
              elt.parents("#reply-box").hide();
            }
          } else {
            if (response.result == "error-field") {
              input.error(elt, response.msg);
            } else {
              elt.find("#alertErr").html(response.msg);
            }
          }
          elt.find("textarea").html("");
        });
      }
    );
  };
}
export default new Comments($("#comments_section"))._init();
