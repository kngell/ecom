import _uploadAdapter from "corejs/editor_uploadAdapter";
import {
  fontColorConfig,
  fontbgColorConfig,
  imageConfig,
  headings,
  linkConfig,
  mediaConfig,
  tableConfig,
} from "./editorConfig";
import Autoformat from "@ckeditor/ckeditor5-autoformat/src/autoformat";
import ClassicEditor from "@ckeditor/ckeditor5-editor-classic/src/classiceditor";
import Essentials from "@ckeditor/ckeditor5-essentials/src/essentials";
import Heading from "@ckeditor/ckeditor5-heading/src/heading";
import HeadingButtonsUI from "@ckeditor/ckeditor5-heading/src/headingbuttonsui";
import Paragraph from "@ckeditor/ckeditor5-paragraph/src/paragraph";
import ParagraphButtonUI from "@ckeditor/ckeditor5-paragraph/src/paragraphbuttonui";
import Bold from "@ckeditor/ckeditor5-basic-styles/src/bold";
import Italic from "@ckeditor/ckeditor5-basic-styles/src/italic";
import Underline from "@ckeditor/ckeditor5-basic-styles/src/underline";
import BlockQuote from "@ckeditor/ckeditor5-block-quote/src/blockquote";
import ListStyle from "@ckeditor/ckeditor5-list/src/liststyle";
import Indent from "@ckeditor/ckeditor5-indent/src/indent";
import IndentBlock from "@ckeditor/ckeditor5-indent/src/indentblock";
import Clipboard from "@ckeditor/ckeditor5-clipboard/src/clipboard";
import Alignment from "@ckeditor/ckeditor5-alignment/src/alignment";
import Font from "@ckeditor/ckeditor5-font/src/font";
import Image from "@ckeditor/ckeditor5-image/src/image";
import FontBackgroundColor from "@ckeditor/ckeditor5-font/src/fontbackgroundcolor";
import ImageToolbar from "@ckeditor/ckeditor5-image/src/imagetoolbar";
import ImageCaption from "@ckeditor/ckeditor5-image/src/imagecaption";
import ImageStyle from "@ckeditor/ckeditor5-image/src/imagestyle";
import ImageResize from "@ckeditor/ckeditor5-image/src/imageresize";
import LinkImage from "@ckeditor/ckeditor5-link/src/linkimage";
import ImageInsert from "@ckeditor/ckeditor5-image/src/imageinsert";
import Link from "@ckeditor/ckeditor5-link/src/link";
import AutoLink from "@ckeditor/ckeditor5-link/src/autolink";
import MediaEmbed from "@ckeditor/ckeditor5-media-embed/src/mediaembed";
import Table from "@ckeditor/ckeditor5-table/src/table";
import TableToolbar from "@ckeditor/ckeditor5-table/src/tabletoolbar";
import TableProperties from "@ckeditor/ckeditor5-table/src/tableproperties";
import TableCellProperties from "@ckeditor/ckeditor5-table/src/tablecellproperties";
import PasteFromOffice from "@ckeditor/ckeditor5-paste-from-office/src/pastefromoffice";
import ImageRemoveEventCallbackPlugin from "ckeditor5-image-remove-event-callback-plugin";
/**
 * Editor
 * ====================================================================
 * @param {*} elementid
 * @returns
 */
export default class myEditor {
  constructor(elementid, token) {
    this.elementid = elementid;
    this.token = token;
  }
  _createEditor = () => {
    const p = this;
    return new Promise((resolve, reject) => {
      const p = this;
      ClassicEditor.create(document.querySelector("#" + p.elementid), {
        plugins: [
          Autoformat,
          Essentials,
          Heading,
          HeadingButtonsUI,
          Paragraph,
          ParagraphButtonUI,
          Bold,
          Italic,
          Underline,
          BlockQuote,
          ListStyle,
          Indent,
          IndentBlock,
          Clipboard,
          Alignment,
          Font,
          Image,
          FontBackgroundColor,
          ImageToolbar,
          ImageCaption,
          ImageStyle,
          ImageResize,
          LinkImage,
          ImageInsert,
          Link,
          AutoLink,
          MediaEmbed,
          Table,
          TableToolbar,
          TableProperties,
          TableCellProperties,
          PasteFromOffice,
          p._mycustomuUpload,
          ImageRemoveEventCallbackPlugin,
        ],
        toolbar: {
          items: [
            "heading",
            "|",
            "bold",
            "italic",
            "underline",
            "bulletedList",
            "numberedList",
            "|",
            "alignment",
            "fontSize",
            "fontFamily",
            "fontColor",
            "fontBackgroundColor",
            "|",
            "outdent",
            "indent",
            "undo",
            "redo",
            "blockQuote",
            "|",
            "imageInsert",
            "mediaEmbed",
            "|",
            "link",
            "insertTable",
            "|",
          ],
        },
        heading: {
          options: headings,
        },
        fontSize: {
          options: [
            "default",
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
          ],
          supportAllValues: true,
        },
        fontColor: {
          colors: fontColorConfig,
        },
        fontBackgroundColor: {
          colors: fontbgColorConfig,
        },
        image: imageConfig,
        link: linkConfig,
        mediaEmbed: mediaConfig,
        table: tableConfig,
        imageRemoveEvent: {
          callback: (imagesSrc, nodeObjects) => {
            // note: imagesSrc is array of src & nodeObjects is array of nodeObject
            // node object api: https://ckeditor.com/docs/ckeditor5/latest/api/module_engine_model_node-Node.html

            console.log("callback called", imagesSrc, nodeObjects);
          },
        },
      })
        .then((editor) => {
          window.elementid = editor;
          editor.model.document.on("change:data", (e, data) => {
            let root = editor.model.document.getRoot();
            let children = root.getChildren();
          });
          //Resolve
          resolve(editor);
        })
        .catch((error) => {
          reject(error);
        });
    });
  };
  _mycustomuUpload = (editor) => {
    const p = this;
    editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
      return new _uploadAdapter(loader, p.token);
    };
  };
}
