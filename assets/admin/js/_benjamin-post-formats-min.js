!function e(t,a,o){function r(i,l){if(!a[i]){if(!t[i]){var s="function"==typeof require&&require;if(!l&&s)return s(i,!0);if(n)return n(i,!0);var f=new Error("Cannot find module '"+i+"'");throw f.code="MODULE_NOT_FOUND",f}var p=a[i]={exports:{}};t[i][0].call(p.exports,function(e){var a=t[i][1][e];return r(a?a:e)},p,p.exports,e,t,a,o)}return a[i].exports}for(var n="function"==typeof require&&require,i=0;i<o.length;i++)r(o[i]);return r}({1:[function(e,t,a){e("./post-formats/gallery"),e("./post-formats/media"),e("./post-formats/hide-boxes")},{"./post-formats/gallery":2,"./post-formats/hide-boxes":3,"./post-formats/media":4}],2:[function(e,t,a){function o(){var e=document.querySelector(".gallery_remove");e.addEventListener("click",function(e){e.preventDefault();var t=e.target,a=t.closest(".postbox");a.querySelector(".post_format_value").value="",a.querySelector(".pfp-shortcode-holder").innerHTML=""},!1);var t=document.querySelector("#post_format_gallery_add");t.addEventListener("click",function(e){e.preventDefault();var t=e.target,a=t.closest(".postbox");r(a)})}function r(e){var t=e.querySelector(".post_format_value").value;t=t.split(","),wp.media.frames.galleryBox&&delete wp.media.frames.galleryBox,wp.media.frames.galleryBox=wp.media({title:"Gallery",library:{type:"image"},multiple:!0,toolbar:"main-gallery",state:"gallery-library",frame:"post"}),wp.media.frames.galleryBox.on("open",function(){var e=wp.media.frames.galleryBox.state().get("selection");t.forEach(function(t){attachment=wp.media.attachment(t),attachment.fetch(),e.add(attachment?[attachment]:[])})}),wp.media.frames.galleryBox.on("update",function(){selection=wp.media.frames.galleryBox.state().get("library");var t="",a=[];selection.map(function(e){a.push(e.id)}),t=a.join(","),e.querySelector(".post_format_value").value=t,n('[gallery link="none" ids="'+t+'"]',e)}),wp.media.frames.galleryBox.open()}function n(e,t){var a={action:"benjamin_postformat_shortcode",pfpSTR:e};jQuery.ajax({type:"POST",url:ajaxurl,data:a,complete:function(e){if(200==e.status){var a=e.responseText;""==a?t.querySelector(".post_format_value").value="":t.querySelector(".pfp-shortcode-holder").innerHTML=a}}})}o()},{}],3:[function(e,t,a){function o(){var e=["aside","status","gallery","image","link","quote","audio","video","chat"];e=e.map(function(e){return"#post_formats_"+e}).join(", "),$(e).hide()}function r(){$(".pfp-js-remove-link").on("click",function(e){e.preventDefault(),$(this).closest(".link-box").find("input").val("")})}jQuery(document).ready(function(e){if(o(),r(),e("#post-formats-select").length){var t=e("input[name='post_format']:checked").val(),a=["aside","status","gallery","image","link","quote","audio","video","chat"];"-1"!=e.inArray(t,a)&&e("#post_formats_"+t).show(),e("input[name='post_format']:radio").change(function(){o(),"-1"!=e.inArray(e(this).val(),a)&&e("#post_formats_"+e(this).val()).show()})}})},{}],4:[function(e,t,a){function o(e,t,a){pfpAJAXMarkup(e.url,t,a);a.find(".post_format_value").val(e.url)}function r(e){return e.charAt(0).toUpperCase()+e.slice(1)}jQuery(document).ready(function(e){e(".post_format_value").on("change",function(t){t.preventDefault();var a=e(this),o=a.closest(".postbox"),r=a.val(),n=a.data("media");console.log(r),window.pfpAJAXMarkup(r,n,o)}),e(".pfp-js-remove-media").on("click",function(t){t.preventDefault();var a=e(this),o=a.closest(".postbox");o.find(".pfp-media-holder").html(""),o.find(".post_format_url").val(""),o.find(".post_format_value").val("")}),e(".pfp-js-media-library").on("click",function(t){t.preventDefault();var a=e(this),n=a.data("media"),i=a.closest(".postbox");i.find(".post_format_value").val();wp.media.frames.mediaBox&&delete wp.media.frames.mediaBox,wp.media.frames.mediaBox=wp.media({title:n,button:{text:"Select "+n},library:{type:n},multiple:!1}),wp.media.frames.mediaBox.on("select",function(){media_attachment=wp.media.frames.mediaBox.state().get("selection").first().toJSON();"pfp"+r(n)+"Select";o(media_attachment,n,i)}),wp.media.frames.mediaBox.open()})}),window.pfpAJAXMarkup=function(e,t,a){var o={action:"benjamin_postformat_oembed",pfpURL:e,pfpType:t};jQuery.ajax({type:"POST",url:ajaxurl,data:o,complete:function(e){if(200==e.status){var t=e.responseText;""==t&&(a.find(".post_format_url").val(""),a.find(".post_format_value").val("")),a.find(".pfp-media-holder").html(t)}}})}},{}]},{},[1]);