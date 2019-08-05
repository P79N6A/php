(function ($) {
    $.fn.player = function () {
        $(this).each(function () {
            var obj = $(this);
            obj.css({position: 'relative'});
            var video_logo = "<div class='video-logo' style='position:absolute;left:3px;top:4px;width:20px;height:12px;border:2px solid #000;background-color:rgba(0,255,0,0.4);padding-left:5px'><div class='video-logo-btn' style='position:relative;width:0;height:0;border-top:5px solid transparent;border-left:10px solid #000;border-bottom:5px solid transparent;top:1px;left:4px'></div></div>";
            obj.html("<div class='video-player' style='position:absolute'></div><div class='video-info' style='position:absolute'>" + obj.html() + "</div>" + video_logo)
            var mp4 = obj.attr("url");
            obj.click(function () {
                var logo = $(this).find(".video-logo")
                var info = $(this).find(".video-info")
                var player = $(this).find(".video-player")
                if (info.is(":visible")) {
                    logo.hide();
                    info.hide();
                    player.show();
                    play();
                } else {
                    logo.show();
                    info.show();
                    stop();
                }

                function play() {
                    if (!player.is(".video_inited")) {
                        player.html('<video class="myVideo" controls="controls" preload="metadata" style="display:none"><source src="' + mp4 + '" type="video/mp4"></video><canvas width="160" height="226" class="canvas" onclick="stop()"></canvas>').show();

                        var video = player.find(".myVideo");
                        var _video = video.get(0);
                        var canvas = player.find(".canvas");
                        var _canvas = canvas.get(0);
                        var context = _canvas.getContext("2d");

                        video.bind("play", function () {
                            drawCanvas();
                        });

                        video.bind("ended", function () {
                            player.hide();
                            info.show();
                            logo.show();
                        });

                        function drawCanvas() {
                            if (_video.paused || _video.ended) {
                                return;
                            }

                            if (_video.videoWidth > _video.videoHeight) {
                                context.clearRect(0, 0, _canvas.width, _canvas.height);
                                context.save();
                                context.translate(0, 0);
                                context.rotate(90 * Math.PI / 180);
                                context.transform(1, 0.0, 0, 1, 0, -160);
                                context.drawImage(_video, 0, 0, 226, 160);
                                context.restore();
                            } else {
                                context.drawImage(_video, 0, 0, 160, 226);
                            }

                            setTimeout(drawCanvas, 30);
                        }

                        player.addClass("video_inited");
                    }
                    player.find(".myVideo").get(0).play();
                }

                function stop() {
                    player.find(".myVideo").get(0).pause();
                }

            });
        });
    };
})(jQuery)
