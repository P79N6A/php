var JQ = jQuery;
function post(url, data, call) {/*{{{*/
    if (typeof data == 'function') {
        call = [data, data = undefined][0]
    }

    return $.ajax({
        method: "post",
        dataType: "json",
        data: $.extend(data, {json_call: "Y"}),
        url: url,
        xhrFields: {
            withCredentials: true
        },
        success: call
    });
}
/*}}}*/
function popup(content, title, width, height, block) {/*{{{*/
    var fun = popup.caller.name || "";
    var pid = "";

    title = title || "";
    content = content || "";
    width = width || 400;
    height = height || 200;
    block = block || true;

    if (fun != "") {
        pid = '_popup_' + fun;
    } else {
        $.popupID = $.popupID ? ++$.popupID : 1;
        pid = '_popup_' + $.popupID;
    }

    var _popup = {
        init: function () {

            var div_mk = $("#_mask_" + pid);
            if (block) {
                if (!div_mk[0]) {
                    $.pupupZidx = $.pupupZidx ? $.pupupZidx + 1 : 999;
                    var div = $('<div id="_mask_' + pid + '" />').css({
                        zIndex: $.pupupZidx,
                        position: "absolute",
                        top: 0,
                        left: 0,
                        background: "#bbb",
                        width: "100%",
                        height: Math.max($(document).height(), $(window).height())
                    }).css('opacity', 0.1)
                    $('body').append(div);
                }

                div_mk = $("#_mask_" + pid);
                div_mk.show();
            }

            var div_fd = $("#" + pid);
            if (!div_fd[0]) {
                $.pupupZidx = $.pupupZidx ? $.pupupZidx + 1 : 1000;

                var div = $('<div id="' + pid + '" />').css({
                        zIndex: $.pupupZidx,
                        position: "fixed",
                        background: "#eee",
                        border: "1px solid #000",
                        textAlign: "center",
                        color: "#fff",
                        padding: "3px",
                        width: width,
                        height: height,
                        top: Math.abs($(window).height() - height) / 2,
                        left: Math.abs($(window).width() - width) / 2
                    })
                    .html('<div style="position:relative;border-bottom:1px solid #ccc;height:23px;"><span class="tit" style="float:left;color:#000;font-weight:bold;font-size:14px"></span><a style="background:#ddd;position:absolute;top:0;right:0;font-size:16px;font-weight:bold;border:1px solid #fff;border-radius:10px;width:20px;text-decoration:none;display:block;line-height:20px;" href="javascript:(function(){$(\'#' + pid + '\').hide();$(\'#_mask_' + pid + '\').hide()})()">&times;</a></div><div class="con" style="text-align:left;color:#333;height:' + (height - 25) + 'px"></div>');
                $('body').append(div);
                div_fd = $("#" + pid);
            }else{
            	div_fd.css({width:width,height:height,left:Math.abs($(window).width() - width) / 2,top:Math.abs($(window).height() - height) / 2});
            }

            div_fd.find(".tit").html(title);
            div_fd.find(".con").html(content);
            div_fd.show();
        },

        close: function () {
            $("#_mask_" + pid).hide();
            $("#" + pid).hide();
        }
    };

    _popup.init();

    return _popup;
}
/*}}}*/


function message(obj, msg, css) {/*{{{*/
    var parentIsBody = false;
    if (undefined == obj && undefined == msg) return;

    if (typeof obj != 'object') {
        css = [msg, msg = obj][0];
        parentIsBody = true;
    } else if (!obj[0]) {
        parentIsBody = true;
    }

    JQ.topmessageID = ++JQ.topmessageID || 1;
    JQ.timer_topmessage = {};

    var topmessageID = JQ.topmessageID, msgBody;
    if (parentIsBody) {
        msgBody = JQ('#_topmessage');
        if (!msgBody[0]) {
            JQ('body').prepend('<div id="_topmessage" class="topmessageWrapper flowtip" style="text-align:center;position:absolute;width:auto;white-space:nowrap;padding:2px 4px 2px 4px;z-index:10000"></div>');
            msgBody = JQ('#_topmessage');
        }
    } else {
        msgBody = JQ('#_topmessage' + topmessageID);
        if (msgBody[0]) {
            msgBody.empty();
        } else {
            obj.prepend('<span id="_topmessage' + topmessageID + '" class="topmessageWrapper flowtip" style="margin:0;padding:0;clear:both;position:absolute"></span>');
            msgBody = JQ('#_topmessage' + topmessageID);
        }
    }

    var m = JQ('<span ' + (parentIsBody ? 'id="_topmessage' + topmessageID + '"' : '') + ' class="topmessage msg" style="display:block;margin:0;padding:0;clear:both;"><span style="font-weight:bold;margin:0;line-height:1.5;clear:both"> ' + msg + ' </span><a style="width:0;opacity:0; filter:alpha(opacity=0)" href="javascript://" ></a></span>');
    msgBody.append(m);
    if (!parentIsBody) {
        var offset = obj.offset(), pobj = obj.offsetParent(), t = l = 0;
        if (pobj[0]) {
            var offp = pobj.offset();
            t = offp.top;
            l = offp.left
        }
    }
    msgBody.css({left: parentIsBody ? (JQ(window).width() - m.width()) / 2 : (obj.width() - m.width()) / 2, top: parentIsBody ? JQ(window).scrollTop() : 0});
    if (typeof(css) === 'object') {
        m.find('span').css(css);
    } else if (typeof(css) === 'string') {
        m.find('span').addClass(css);
    } else {
        m.find('span').css({color: '#000', padding: '1px 4px 1px 4px', background: '#FFF9DE'});
    }
    m.find('span').html(msg);
    m.css('opacity', 0).show().fadeTo('slow', 1);
    JQ.timer_topmessage[topmessageID] = setTimeout("JQ('#_topmessage" + topmessageID + "').fadeTo('slow',0,function(){JQ(this).remove();JQ(document).focus();})", 3E3);
    JQ("#_topmessage" + topmessageID + " a").focus();
    JQ(window).unload(function () {
        for (var i in JQ.timer_topmessage)clearTimeout(JQ.timer_topmessage[i])
    });
}
/*}}}*/

function error(obj, msg, css) {/*{{{*/
    var errorcss = {color: '#fff', padding: '1px 4px 1px 4px', background: '#CC0000'};
    if (typeof obj != 'object') {
        if (typeof msg === 'undefined')msg = errorcss;
    } else {
        if (typeof css === 'undefined')css = errorcss;
    }
    message(obj, msg, css);
}
/*}}}*/
