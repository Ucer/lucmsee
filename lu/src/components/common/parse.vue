<template>
    <div v-if="rawHtml == null" class="ui icon message info">
        <i class="notched circle loading icon"></i>
        <div class="content">
            <div class="header">稍候</div>
            <p>正在努力加载中。。。</p>
        </div>
    </div>
    <div v-else>
        <div class="ui readme markdown-body content-body" v-html="rawHtml" v-highlight>
        </div>
    </div>
</template>

<script>
import marked from 'marked'

export default {
  props: {
    content: {
      type: String,
      default () {
        return null
      }
    }
  },
  data () {
    return {
      rawHtml: null
    }
  },
  created () {
    this.rawHtml = marked(this.content)
  }
}
</script>

<style lang="css">
    .editor-preview,
    .markdown-body,
    .markdown-reply {
        overflow: visible;
    }

    .editor-preview-active-side,
    .editor-preview,
    .markdown-body,
    .markdown-reply {
        font-size: 15px;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
        line-height: 1.4;
        line-height: 1.6;
        word-wrap: break-word;
    }

    .editor-preview-active-side a,
    .editor-preview a,
    .markdown-body a,
    .markdown-reply a {
        background: transparent;
    }

    .editor-preview-active-side a:active,
    .editor-preview-active-side a:hover,
    .editor-preview a:active,
    .editor-preview a:hover,
    .markdown-body a:active,
    .markdown-body a:hover,
    .markdown-reply a:active,
    .markdown-reply a:hover {
        outline: 0;
    }

    .editor-preview-active-side ol li,
    .editor-preview ol li,
    .markdown-body ol li,
    .markdown-reply ol li {
        margin: 8px 0;
    }

    .editor-preview-active-side pre[class*=language-],
    .editor-preview pre[class*=language-],
    .markdown-body pre[class*=language-],
    .markdown-reply pre[class*=language-] {
        margin: 1.2em 0 !important;
    }

    .editor-preview-active-side strong,
    .editor-preview strong,
    .markdown-body strong,
    .markdown-reply strong {
        font-weight: bold;
    }

    .editor-preview-active-side h1,
    .editor-preview h1,
    .markdown-body h1,
    .markdown-reply h1 {
        font-size: 2em;
        margin: 0.67em 0;
    }

    .editor-preview-active-side img,
    .editor-preview img,
    .markdown-body img,
    .markdown-reply img {
        border: 0;
    }

    .editor-preview-active-side hr,
    .editor-preview hr,
    .markdown-body hr,
    .markdown-reply hr {
        -webkit-box-sizing: content-box;
        box-sizing: content-box;
        height: 0;
    }

    .editor-preview-active-side table,
    .editor-preview table,
    .markdown-body table,
    .markdown-reply table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    .editor-preview-active-side td,
    .editor-preview-active-side th,
    .editor-preview td,
    .editor-preview th,
    .markdown-body td,
    .markdown-body th,
    .markdown-reply td,
    .markdown-reply th {
        padding: 0;
    }

    .editor-preview-active-side *,
    .editor-preview *,
    .markdown-body *,
    .markdown-reply * {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .editor-preview-active-side a,
    .editor-preview a,
    .markdown-body a,
    .markdown-reply a {
        text-decoration: none;
    }

    .editor-preview-active-side a:hover,
    .editor-preview-active-side a:focus,
    .editor-preview-active-side a:active,
    .editor-preview a:hover,
    .editor-preview a:focus,
    .editor-preview a:active,
    .markdown-body a:hover,
    .markdown-body a:focus,
    .markdown-body a:active,
    .markdown-reply a:hover,
    .markdown-reply a:focus,
    .markdown-reply a:active {
        text-decoration: none;
    }

    .editor-preview-active-side hr,
    .editor-preview hr,
    .markdown-body hr,
    .markdown-reply hr {
        height: 0;
        overflow: hidden;
        background: transparent;
        border: 2px dashed #F0F4F6;
        border-bottom: 0px;
        margin: 18px auto;
        width: 50%;
    }

    .editor-preview-active-side hr:before,
    .editor-preview-active-side hr:after,
    .editor-preview hr:before,
    .editor-preview hr:after,
    .markdown-body hr:before,
    .markdown-body hr:after,
    .markdown-reply hr:before,
    .markdown-reply hr:after {
        display: table;
        content: " ";
    }

    .editor-preview-active-side hr:after,
    .editor-preview hr:after,
    .markdown-body hr:after,
    .markdown-reply hr:after {
        clear: both;
    }

    .editor-preview-active-side h1,
    .editor-preview-active-side h2,
    .editor-preview-active-side h3,
    .editor-preview-active-side h4,
    .editor-preview-active-side h5,
    .editor-preview-active-side h6,
    .editor-preview h1,
    .editor-preview h2,
    .editor-preview h3,
    .editor-preview h4,
    .editor-preview h5,
    .editor-preview h6,
    .markdown-body h1,
    .markdown-body h2,
    .markdown-body h3,
    .markdown-body h4,
    .markdown-body h5,
    .markdown-body h6,
    .markdown-reply h1,
    .markdown-reply h2,
    .markdown-reply h3,
    .markdown-reply h4,
    .markdown-reply h5,
    .markdown-reply h6 {
        margin-top: 15px;
        margin-bottom: 15px;
        line-height: 1.1;
        font-weight: normal;
    }

    .editor-preview-active-side h1,
    .editor-preview h1,
    .markdown-body h1,
    .markdown-reply h1 {
        font-size: 30px;
    }

    .editor-preview-active-side h2,
    .editor-preview h2,
    .markdown-body h2,
    .markdown-reply h2 {
        font-size: 21px;
    }

    .editor-preview-active-side h3,
    .editor-preview h3,
    .markdown-body h3,
    .markdown-reply h3 {
        font-size: 16px;
    }

    .editor-preview-active-side h4,
    .editor-preview h4,
    .markdown-body h4,
    .markdown-reply h4 {
        font-size: 14px;
    }

    .editor-preview-active-side h5,
    .editor-preview h5,
    .markdown-body h5,
    .markdown-reply h5 {
        font-size: 12px;
    }

    .editor-preview-active-side h6,
    .editor-preview h6,
    .markdown-body h6,
    .markdown-reply h6 {
        font-size: 11px;
    }

    .editor-preview-active-side blockquote,
    .editor-preview blockquote,
    .markdown-body blockquote,
    .markdown-reply blockquote {
        margin: 0;
    }

    .editor-preview-active-side blockquote p:last-child,
    .editor-preview-active-side blockquote ul:last-child,
    .editor-preview-active-side blockquote ol:last-child,
    .editor-preview blockquote p:last-child,
    .editor-preview blockquote ul:last-child,
    .editor-preview blockquote ol:last-child,
    .markdown-body blockquote p:last-child,
    .markdown-body blockquote ul:last-child,
    .markdown-body blockquote ol:last-child,
    .markdown-reply blockquote p:last-child,
    .markdown-reply blockquote ul:last-child,
    .markdown-reply blockquote ol:last-child {
        margin-bottom: 0;
    }

    .editor-preview-active-side ul,
    .editor-preview-active-side ol,
    .editor-preview ul,
    .editor-preview ol,
    .markdown-body ul,
    .markdown-body ol,
    .markdown-reply ul,
    .markdown-reply ol {
        padding: 0;
        margin-top: 0;
        margin-bottom: 0;
    }

    .editor-preview-active-side ol ol,
    .editor-preview ol ol,
    .markdown-body ol ol,
    .markdown-reply ol ol {
        list-style-type: lower-roman;
    }

    .editor-preview-active-side dd,
    .editor-preview dd,
    .markdown-body dd,
    .markdown-reply dd {
        margin-left: 0;
    }

    .editor-preview-active-side code,
    .editor-preview-active-side pre,
    .editor-preview code,
    .editor-preview pre,
    .markdown-body code,
    .markdown-body pre,
    .markdown-reply code,
    .markdown-reply pre {
        font-family: monaco, Consolas, "Liberation Mono", Menlo, Courier, monospace;
        font-size: 1em;
    }

    .editor-preview-active-side pre,
    .editor-preview pre,
    .markdown-body pre,
    .markdown-reply pre {
        margin-top: 0;
        margin-bottom: 0;
        overflow: auto;
    }

    .editor-preview-active-side .markdown-body > *:first-child,
    .editor-preview .markdown-body > *:first-child,
    .markdown-body .markdown-body > *:first-child,
    .markdown-reply .markdown-body > *:first-child {
        margin-top: 0 !important;
    }

    .editor-preview-active-side .markdown-body > *:last-child,
    .editor-preview .markdown-body > *:last-child,
    .markdown-body .markdown-body > *:last-child,
    .markdown-reply .markdown-body > *:last-child {
        margin-bottom: 0 !important;
    }

    .editor-preview-active-side .anchor,
    .editor-preview .anchor,
    .markdown-body .anchor,
    .markdown-reply .anchor {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        display: block;
        padding-right: 6px;
        padding-left: 30px;
        margin-left: -30px;
    }

    .editor-preview-active-side .anchor:focus,
    .editor-preview .anchor:focus,
    .markdown-body .anchor:focus,
    .markdown-reply .anchor:focus {
        outline: none;
    }

    .editor-preview-active-side h1,
    .editor-preview-active-side h2,
    .editor-preview-active-side h3,
    .editor-preview-active-side h4,
    .editor-preview-active-side h5,
    .editor-preview-active-side h6,
    .editor-preview h1,
    .editor-preview h2,
    .editor-preview h3,
    .editor-preview h4,
    .editor-preview h5,
    .editor-preview h6,
    .markdown-body h1,
    .markdown-body h2,
    .markdown-body h3,
    .markdown-body h4,
    .markdown-body h5,
    .markdown-body h6,
    .markdown-reply h1,
    .markdown-reply h2,
    .markdown-reply h3,
    .markdown-reply h4,
    .markdown-reply h5,
    .markdown-reply h6 {
        position: relative;
        margin-top: 1.0em;
        margin-bottom: 16px;
        line-height: 1.4;
    }

    .editor-preview-active-side h1,
    .editor-preview h1,
    .markdown-body h1,
    .markdown-reply h1 {
        padding-bottom: 0.3em;
        font-size: 2.25em;
        line-height: 1.2;
        border-bottom: 1px solid #eee;
    }

    .editor-preview-active-side h2,
    .editor-preview h2,
    .markdown-body h2,
    .markdown-reply h2 {
        padding-bottom: 0.3em;
        font-size: 1.3em;
        line-height: 1.225;
        border-bottom: 1px solid #eee;
    }

    .editor-preview-active-side h3,
    .editor-preview h3,
    .markdown-body h3,
    .markdown-reply h3 {
        font-size: 1.2em;
        line-height: 1.43;
    }

    .editor-preview-active-side h4,
    .editor-preview h4,
    .markdown-body h4,
    .markdown-reply h4 {
        font-size: 1.1em;
    }

    .editor-preview-active-side h5,
    .editor-preview h5,
    .markdown-body h5,
    .markdown-reply h5 {
        font-size: 1.0em;
    }

    .editor-preview-active-side h6,
    .editor-preview h6,
    .markdown-body h6,
    .markdown-reply h6 {
        font-size: 0.9em;
        color: #777;
    }

    .editor-preview-active-side p,
    .editor-preview-active-side blockquote,
    .editor-preview-active-side ul,
    .editor-preview-active-side ol,
    .editor-preview-active-side dl,
    .editor-preview-active-side table,
    .editor-preview-active-side pre,
    .editor-preview p,
    .editor-preview blockquote,
    .editor-preview ul,
    .editor-preview ol,
    .editor-preview dl,
    .editor-preview table,
    .editor-preview pre,
    .markdown-body p,
    .markdown-body blockquote,
    .markdown-body ul,
    .markdown-body ol,
    .markdown-body dl,
    .markdown-body table,
    .markdown-body pre,
    .markdown-reply p,
    .markdown-reply blockquote,
    .markdown-reply ul,
    .markdown-reply ol,
    .markdown-reply dl,
    .markdown-reply table,
    .markdown-reply pre {
        margin-top: 0;
        margin-bottom: 10px;
        line-height: 30px;
    }

    .editor-preview-active-side ul,
    .editor-preview-active-side ol,
    .editor-preview ul,
    .editor-preview ol,
    .markdown-body ul,
    .markdown-body ol,
    .markdown-reply ul,
    .markdown-reply ol {
        padding-left: 2em;
        padding: 10px 20px 10px 30px;
        color: #7d8688;
    }

    .editor-preview-active-side ol ol,
    .editor-preview-active-side ol ul,
    .editor-preview ol ol,
    .editor-preview ol ul,
    .markdown-body ol ol,
    .markdown-body ol ul,
    .markdown-reply ol ol,
    .markdown-reply ol ul {
        margin-top: 0;
        margin-bottom: 0;
    }

    .editor-preview-active-side li > p,
    .editor-preview li > p,
    .markdown-body li > p,
    .markdown-reply li > p {
        margin-top: 16px;
    }

    .editor-preview-active-side dl,
    .editor-preview dl,
    .markdown-body dl,
    .markdown-reply dl {
        padding: 0;
    }

    .editor-preview-active-side dl dt,
    .editor-preview dl dt,
    .markdown-body dl dt,
    .markdown-reply dl dt {
        padding: 0;
        margin-top: 16px;
        font-size: 1em;
        font-style: italic;
        font-weight: bold;
    }

    .editor-preview-active-side dl dd,
    .editor-preview dl dd,
    .markdown-body dl dd,
    .markdown-reply dl dd {
        padding: 0 16px;
        margin-bottom: 16px;
    }

    .editor-preview-active-side blockquote,
    .editor-preview blockquote,
    .markdown-body blockquote,
    .markdown-reply blockquote {
        font-size: inherit;
        padding: 0 15px;
        color: #777;
        border-left: 4px solid #ddd;
    }

    .editor-preview-active-side blockquote code,
    .editor-preview blockquote code,
    .markdown-body blockquote code,
    .markdown-reply blockquote code {
        color: inherit;
        background-color: transparent;
        border: 1px solid #d1e0f3;
    }

    .editor-preview-active-side blockquote > :first-child,
    .editor-preview blockquote > :first-child,
    .markdown-body blockquote > :first-child,
    .markdown-reply blockquote > :first-child {
        margin-top: 20;
    }

    .editor-preview-active-side blockquote > :last-child,
    .editor-preview blockquote > :last-child,
    .markdown-body blockquote > :last-child,
    .markdown-reply blockquote > :last-child {
        margin-bottom: 20;
    }

    .editor-preview-active-side blockquote,
    .editor-preview blockquote,
    .markdown-body blockquote,
    .markdown-reply blockquote {
        margin: 20px 0 !important;
        padding: 1rem;
        background-color: #f5f8fc;
        color: #8796A8;
        border-left: 4px solid #7AD03A;
    }

    .editor-preview-active-side table,
    .editor-preview table,
    .markdown-body table,
    .markdown-reply table {
        display: block;
        width: 100%;
        overflow: auto;
        margin: 25px 0;
    }

    .editor-preview-active-side table th,
    .editor-preview table th,
    .markdown-body table th,
    .markdown-reply table th {
        font-weight: bold;
    }

    .editor-preview-active-side table th,
    .editor-preview-active-side table td,
    .editor-preview table th,
    .editor-preview table td,
    .markdown-body table th,
    .markdown-body table td,
    .markdown-reply table th,
    .markdown-reply table td {
        padding: 6px 13px;
        border: 1px solid #ddd;
    }

    .editor-preview-active-side table tr,
    .editor-preview table tr,
    .markdown-body table tr,
    .markdown-reply table tr {
        background-color: #fff;
        border-top: 1px solid #ccc;
    }

    .editor-preview-active-side table tr:nth-child(2n),
    .editor-preview table tr:nth-child(2n),
    .markdown-body table tr:nth-child(2n),
    .markdown-reply table tr:nth-child(2n) {
        background-color: #f8f8f8;
    }

    .editor-preview-active-side img,
    .editor-preview img,
    .markdown-body img,
    .markdown-reply img {
        max-width: 100%;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .editor-preview-active-side img:not(.emoji),
    .editor-preview img:not(.emoji),
    .markdown-body img:not(.emoji),
    .markdown-reply img:not(.emoji) {
        margin-bottom: 30px;
        margin-top: 10px;
        border: 1px solid #ddd;
        -webkit-box-shadow: 0 0 30px #ccc;
        box-shadow: 0 0 30px #ccc;
    }

    .editor-preview-active-side img.rm-style,
    .editor-preview img.rm-style,
    .markdown-body img.rm-style,
    .markdown-reply img.rm-style {
        border: none;
        box-shadow: none;
        -moz-box-shadow: none;
        -webkit-box-shadow: none;
        margin-bottom: 0px;
        margin-top: 0px;
    }

    .editor-preview-active-side code,
    .editor-preview code,
    .markdown-body code,
    .markdown-reply code {
        background: rgba(90, 87, 87, 0);
        margin: 5px;
        color: #858080;
        border-radius: 4px;
        background-color: #f9fafa;
        border: 1px solid #e4e4e4;
        max-width: 740px;
        overflow-x: auto;
        font-size: .9em;
        padding: 1px 2px 1px;
    }

    .editor-preview-active-side code:before,
    .editor-preview-active-side code:after,
    .editor-preview code:before,
    .editor-preview code:after,
    .markdown-body code:before,
    .markdown-body code:after,
    .markdown-reply code:before,
    .markdown-reply code:after {
        letter-spacing: -0.2em;
        content: "\A0";
    }

    .editor-preview-active-side pre > code,
    .editor-preview pre > code,
    .markdown-body pre > code,
    .markdown-reply pre > code {
        padding: 0;
        margin: 0;
        font-size: 100%;
        white-space: pre;
        background: transparent;
        border: 0;
    }

    .editor-preview-active-side .highlight,
    .editor-preview .highlight,
    .markdown-body .highlight,
    .markdown-reply .highlight {
        margin-bottom: 16px;
    }

    .editor-preview-active-side .highlight pre,
    .editor-preview-active-side pre,
    .editor-preview .highlight pre,
    .editor-preview pre,
    .markdown-body .highlight pre,
    .markdown-body pre,
    .markdown-reply .highlight pre,
    .markdown-reply pre {
        padding: 14px;
        overflow: auto;
        line-height: 1.45;
        border-radius: 3px;
        color: #fff;
        border: none;
    }

    .editor-preview-active-side .highlight pre,
    .editor-preview .highlight pre,
    .markdown-body .highlight pre,
    .markdown-reply .highlight pre {
        margin-bottom: 0;
    }

    .editor-preview-active-side pre,
    .editor-preview pre,
    .markdown-body pre,
    .markdown-reply pre {
        word-wrap: normal;
        background: rgba(249, 248, 247, 0.53);
        border: 1px solid #efefef;
        -webkit-box-shadow: 0 2px 5px 0 rgba(199, 199, 199, 0.16), 0 2px 10px 0 rgba(243, 243, 243, 0.12);
        box-shadow: 0 2px 5px 0 rgba(199, 199, 199, 0.16), 0 2px 10px 0 rgba(243, 243, 243, 0.12);
        border-radius: 3px;
    }

    .editor-preview-active-side pre code,
    .editor-preview pre code,
    .markdown-body pre code,
    .markdown-reply pre code {
        display: block;
        padding: 0;
        background: #272822;
        margin: 0;
        overflow: initial;
        line-height: inherit;
        word-wrap: normal;
        background-color: transparent;
        border: 0;
    }

    .editor-preview-active-side pre code:before,
    .editor-preview-active-side pre code:after,
    .editor-preview pre code:before,
    .editor-preview pre code:after,
    .markdown-body pre code:before,
    .markdown-body pre code:after,
    .markdown-reply pre code:before,
    .markdown-reply pre code:after {
        content: normal;
    }

    .editor-preview-active-side .highlight,
    .editor-preview .highlight,
    .markdown-body .highlight,
    .markdown-reply .highlight {
        background: #ffffff;
    }

    .editor-preview-active-side .highlight .c,
    .editor-preview .highlight .c,
    .markdown-body .highlight .c,
    .markdown-reply .highlight .c {
        color: #999988;
        font-style: italic;
    }

    .editor-preview-active-side .highlight .err,
    .editor-preview .highlight .err,
    .markdown-body .highlight .err,
    .markdown-reply .highlight .err {
        color: #a61717;
        background-color: #e3d2d2;
    }

    .editor-preview-active-side .highlight .k,
    .editor-preview .highlight .k,
    .markdown-body .highlight .k,
    .markdown-reply .highlight .k {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .o,
    .editor-preview .highlight .o,
    .markdown-body .highlight .o,
    .markdown-reply .highlight .o {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .cm,
    .editor-preview .highlight .cm,
    .markdown-body .highlight .cm,
    .markdown-reply .highlight .cm {
        color: #999988;
        font-style: italic;
    }

    .editor-preview-active-side .highlight .cp,
    .editor-preview .highlight .cp,
    .markdown-body .highlight .cp,
    .markdown-reply .highlight .cp {
        color: #999999;
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .c1,
    .editor-preview .highlight .c1,
    .markdown-body .highlight .c1,
    .markdown-reply .highlight .c1 {
        color: #999988;
        font-style: italic;
    }

    .editor-preview-active-side .highlight .cs,
    .editor-preview .highlight .cs,
    .markdown-body .highlight .cs,
    .markdown-reply .highlight .cs {
        color: #999999;
        font-weight: bold;
        font-style: italic;
    }

    .editor-preview-active-side .highlight .gd,
    .editor-preview .highlight .gd,
    .markdown-body .highlight .gd,
    .markdown-reply .highlight .gd {
        color: #000000;
        background-color: #ffdddd;
    }

    .editor-preview-active-side .highlight .gd .x,
    .editor-preview .highlight .gd .x,
    .markdown-body .highlight .gd .x,
    .markdown-reply .highlight .gd .x {
        color: #000000;
        background-color: #ffaaaa;
    }

    .editor-preview-active-side .highlight .ge,
    .editor-preview .highlight .ge,
    .markdown-body .highlight .ge,
    .markdown-reply .highlight .ge {
        font-style: italic;
    }

    .editor-preview-active-side .highlight .gr,
    .editor-preview .highlight .gr,
    .markdown-body .highlight .gr,
    .markdown-reply .highlight .gr {
        color: #aa0000;
    }

    .editor-preview-active-side .highlight .gh,
    .editor-preview .highlight .gh,
    .markdown-body .highlight .gh,
    .markdown-reply .highlight .gh {
        color: #999999;
    }

    .editor-preview-active-side .highlight .gi,
    .editor-preview .highlight .gi,
    .markdown-body .highlight .gi,
    .markdown-reply .highlight .gi {
        color: #000000;
        background-color: #ddffdd;
    }

    .editor-preview-active-side .highlight .gi .x,
    .editor-preview .highlight .gi .x,
    .markdown-body .highlight .gi .x,
    .markdown-reply .highlight .gi .x {
        color: #000000;
        background-color: #aaffaa;
    }

    .editor-preview-active-side .highlight .go,
    .editor-preview .highlight .go,
    .markdown-body .highlight .go,
    .markdown-reply .highlight .go {
        color: #888888;
    }

    .editor-preview-active-side .highlight .gp,
    .editor-preview .highlight .gp,
    .markdown-body .highlight .gp,
    .markdown-reply .highlight .gp {
        color: #555555;
    }

    .editor-preview-active-side .highlight .gs,
    .editor-preview .highlight .gs,
    .markdown-body .highlight .gs,
    .markdown-reply .highlight .gs {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .gu,
    .editor-preview .highlight .gu,
    .markdown-body .highlight .gu,
    .markdown-reply .highlight .gu {
        color: #800080;
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .gt,
    .editor-preview .highlight .gt,
    .markdown-body .highlight .gt,
    .markdown-reply .highlight .gt {
        color: #aa0000;
    }

    .editor-preview-active-side .highlight .kc,
    .editor-preview .highlight .kc,
    .markdown-body .highlight .kc,
    .markdown-reply .highlight .kc {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .kd,
    .editor-preview .highlight .kd,
    .markdown-body .highlight .kd,
    .markdown-reply .highlight .kd {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .kn,
    .editor-preview .highlight .kn,
    .markdown-body .highlight .kn,
    .markdown-reply .highlight .kn {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .kp,
    .editor-preview .highlight .kp,
    .markdown-body .highlight .kp,
    .markdown-reply .highlight .kp {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .kr,
    .editor-preview .highlight .kr,
    .markdown-body .highlight .kr,
    .markdown-reply .highlight .kr {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .kt,
    .editor-preview .highlight .kt,
    .markdown-body .highlight .kt,
    .markdown-reply .highlight .kt {
        color: #445588;
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .m,
    .editor-preview .highlight .m,
    .markdown-body .highlight .m,
    .markdown-reply .highlight .m {
        color: #009999;
    }

    .editor-preview-active-side .highlight .s,
    .editor-preview .highlight .s,
    .markdown-body .highlight .s,
    .markdown-reply .highlight .s {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .n,
    .editor-preview .highlight .n,
    .markdown-body .highlight .n,
    .markdown-reply .highlight .n {
        color: #333333;
    }

    .editor-preview-active-side .highlight .na,
    .editor-preview .highlight .na,
    .markdown-body .highlight .na,
    .markdown-reply .highlight .na {
        color: teal;
    }

    .editor-preview-active-side .highlight .nb,
    .editor-preview .highlight .nb,
    .markdown-body .highlight .nb,
    .markdown-reply .highlight .nb {
        color: #0086b3;
    }

    .editor-preview-active-side .highlight .nc,
    .editor-preview .highlight .nc,
    .markdown-body .highlight .nc,
    .markdown-reply .highlight .nc {
        color: #445588;
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .no,
    .editor-preview .highlight .no,
    .markdown-body .highlight .no,
    .markdown-reply .highlight .no {
        color: teal;
    }

    .editor-preview-active-side .highlight .ni,
    .editor-preview .highlight .ni,
    .markdown-body .highlight .ni,
    .markdown-reply .highlight .ni {
        color: purple;
    }

    .editor-preview-active-side .highlight .ne,
    .editor-preview .highlight .ne,
    .markdown-body .highlight .ne,
    .markdown-reply .highlight .ne {
        color: #990000;
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .nf,
    .editor-preview .highlight .nf,
    .markdown-body .highlight .nf,
    .markdown-reply .highlight .nf {
        color: #990000;
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .nn,
    .editor-preview .highlight .nn,
    .markdown-body .highlight .nn,
    .markdown-reply .highlight .nn {
        color: #555555;
    }

    .editor-preview-active-side .highlight .nt,
    .editor-preview .highlight .nt,
    .markdown-body .highlight .nt,
    .markdown-reply .highlight .nt {
        color: navy;
    }

    .editor-preview-active-side .highlight .nv,
    .editor-preview .highlight .nv,
    .markdown-body .highlight .nv,
    .markdown-reply .highlight .nv {
        color: teal;
    }

    .editor-preview-active-side .highlight .ow,
    .editor-preview .highlight .ow,
    .markdown-body .highlight .ow,
    .markdown-reply .highlight .ow {
        font-weight: bold;
    }

    .editor-preview-active-side .highlight .w,
    .editor-preview .highlight .w,
    .markdown-body .highlight .w,
    .markdown-reply .highlight .w {
        color: #bbbbbb;
    }

    .editor-preview-active-side .highlight .mf,
    .editor-preview .highlight .mf,
    .markdown-body .highlight .mf,
    .markdown-reply .highlight .mf {
        color: #009999;
    }

    .editor-preview-active-side .highlight .mh,
    .editor-preview .highlight .mh,
    .markdown-body .highlight .mh,
    .markdown-reply .highlight .mh {
        color: #009999;
    }

    .editor-preview-active-side .highlight .mi,
    .editor-preview .highlight .mi,
    .markdown-body .highlight .mi,
    .markdown-reply .highlight .mi {
        color: #009999;
    }

    .editor-preview-active-side .highlight .mo,
    .editor-preview .highlight .mo,
    .markdown-body .highlight .mo,
    .markdown-reply .highlight .mo {
        color: #009999;
    }

    .editor-preview-active-side .highlight .sb,
    .editor-preview .highlight .sb,
    .markdown-body .highlight .sb,
    .markdown-reply .highlight .sb {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .sc,
    .editor-preview .highlight .sc,
    .markdown-body .highlight .sc,
    .markdown-reply .highlight .sc {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .sd,
    .editor-preview .highlight .sd,
    .markdown-body .highlight .sd,
    .markdown-reply .highlight .sd {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .s2,
    .editor-preview .highlight .s2,
    .markdown-body .highlight .s2,
    .markdown-reply .highlight .s2 {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .se,
    .editor-preview .highlight .se,
    .markdown-body .highlight .se,
    .markdown-reply .highlight .se {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .sh,
    .editor-preview .highlight .sh,
    .markdown-body .highlight .sh,
    .markdown-reply .highlight .sh {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .si,
    .editor-preview .highlight .si,
    .markdown-body .highlight .si,
    .markdown-reply .highlight .si {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .sx,
    .editor-preview .highlight .sx,
    .markdown-body .highlight .sx,
    .markdown-reply .highlight .sx {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .sr,
    .editor-preview .highlight .sr,
    .markdown-body .highlight .sr,
    .markdown-reply .highlight .sr {
        color: #009926;
    }

    .editor-preview-active-side .highlight .s1,
    .editor-preview .highlight .s1,
    .markdown-body .highlight .s1,
    .markdown-reply .highlight .s1 {
        color: #dd1144;
    }

    .editor-preview-active-side .highlight .ss,
    .editor-preview .highlight .ss,
    .markdown-body .highlight .ss,
    .markdown-reply .highlight .ss {
        color: #990073;
    }

    .editor-preview-active-side .highlight .bp,
    .editor-preview .highlight .bp,
    .markdown-body .highlight .bp,
    .markdown-reply .highlight .bp {
        color: #999999;
    }

    .editor-preview-active-side .highlight .vc,
    .editor-preview .highlight .vc,
    .markdown-body .highlight .vc,
    .markdown-reply .highlight .vc {
        color: teal;
    }

    .editor-preview-active-side .highlight .vg,
    .editor-preview .highlight .vg,
    .markdown-body .highlight .vg,
    .markdown-reply .highlight .vg {
        color: teal;
    }

    .editor-preview-active-side .highlight .vi,
    .editor-preview .highlight .vi,
    .markdown-body .highlight .vi,
    .markdown-reply .highlight .vi {
        color: teal;
    }

    .editor-preview-active-side .highlight .il,
    .editor-preview .highlight .il,
    .markdown-body .highlight .il,
    .markdown-reply .highlight .il {
        color: #009999;
    }

    .editor-preview-active-side .highlight .gc,
    .editor-preview .highlight .gc,
    .markdown-body .highlight .gc,
    .markdown-reply .highlight .gc {
        color: #999;
        background-color: #EAF2F5;
    }

    .editor-preview-active-side pre.language-todo,
    .editor-preview pre.language-todo,
    .markdown-body pre.language-todo,
    .markdown-reply pre.language-todo {
        background: #fb6c53;
        color: white;
    }

    .editor-preview-active-side pre.language-todo code.language-todo,
    .editor-preview pre.language-todo code.language-todo,
    .markdown-body pre.language-todo code.language-todo,
    .markdown-reply pre.language-todo code.language-todo {
        color: white;
        text-shadow: none;
        font-weight: bold;
    }

    .markdown-reply h1,
    .markdown-reply h2,
    .markdown-reply h3,
    .markdown-reply h4,
    .markdown-reply h5,
    .markdown-reply h6 {
        position: relative;
        margin-top: .5em;
        margin-bottom: 16px;
        font-weight: bold;
        line-height: 1.4;
    }

    .markdown-reply h1 {
        padding-bottom: 0.3em;
        font-size: 1.4em;
        line-height: 1.2;
        border-bottom: 1px solid #eee;
    }

    .markdown-reply h2 {
        padding-bottom: 0.3em;
        font-size: 1.25em;
        line-height: 1.225;
        border-bottom: 1px solid #eee;
    }

    .markdown-reply h3 {
        font-size: 1.2em;
        line-height: 1.43;
    }

    .markdown-reply h4 {
        font-size: 1.1em;
    }

    .markdown-reply h5 {
        font-size: 1em;
    }

    .markdown-reply h6 {
        font-size: 1em;
        color: #777;
    }

    .markdown-body > h2:first-child {
        margin-top: 0.4em;
    }

    .markdown-body .ui.attached.block.header {
        border: 1px solid #d3e0e9;
    }

    .ui.segments > .segment.markdown-body {
        border-top: 0px;
    }

    .editor-preview-active-side pre > code {
        font-size: 90%;
    }
</style>
