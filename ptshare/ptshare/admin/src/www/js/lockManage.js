;(function(){
    var $addLock = $(".addLock"),
        $wrapper = $(".wrapper"),
        $mask = $(".mask"),
        $formAdd = $(".formAdd"),
        $nackName = $(".nackName"),
        $dialog  = $(".dialog");

    function ajaxFuns(param){
        $.ajax({
            url:  param.url,
            data: param.data,
            dataType:'json'
        }).done(function(data){
            param.successCallBack && param.successCallBack(data);
        }).fail(function(){
            console.log('网络有问题，请重试');
        });
    }

    var Lock = {
        init: function(){
            var self = this;
            self.initEvents();
        },
        initEvents: function(){
            // 显示浮层
            $addLock.on("click", function(){
                $dialog.show();
                $mask.show();
                $formAdd[0].reset();
                $dialog.find("h1").html("锁定申请");
                $dialog.find(".nackName").html("");
                $dialog.find("select").find("option").removeAttr("selected");
                $dialog.find(".js-true").attr("type", "0");
            });

            // 关闭浮层
            $wrapper.on("click", ".js-close", function(){
                $dialog.hide();
                $mask.hide();
            });

            // 获取用户的昵称
            $wrapper.on("blur", ".js-userid", function(){
                var sendData = {
                    uid: $.trim($(this).val())
                };
                ajaxFuns({
                    url: "/family/getFamilyMemberInfo",
                    data: sendData,
                    successCallBack: function(data){
                        var nickname = data.userinfo.nickname;
                        $("input[name=uname]").val( nickname );
                        $nackName.html( nickname );
                    }
                });
            });

            // 确定 || 添加节目锁定 || 修改节目锁定。
            $wrapper.on("click", ".js-true", function(){
                if(!$("input[name=uname]").val()){
                    alert("请填写正确的uid");
                    return;
                }
                var url;
                if($(this).attr("type") == "0"){
                    // 添加
                    url = "/LockManage/addApplication";
                }else{
                    // 修改
                    url = "/LockManage/alterApplication";
                    if(!confirm("确定修改吗")){
                        return;
                    }
                }

                var LockTime 
                if (true) {

                }

                var sendData = $formAdd.serialize();
                    ajaxFuns({
                        url: url,
                        data: sendData,
                        successCallBack: function(data){
                        if(data.errno == "0"){
                            alert("操作成功");
                            location.reload();
                        }
                    }
                });

            });

            // 确定 || 添加节目锁定
            $wrapper.on("click", ".btn-edit", function(){
                $dialog.show();
                $mask.show();
                var item = $(this).data("item");
                console.log(item);
                $dialog.find("h1").html("修改锁定");
                $dialog.find("[name=uid]").val(item.uid);
                $dialog.find("[name=uname]").val(item.uname);
                $dialog.find("[name=id]").val(item.id);
                $dialog.find(".nackName").html(item.uname);
                $dialog.find("[name=lockDate]").val(item.lock_date);
                $dialog.find("[name=programName]").val(item.program_name);
                $dialog.find("[name=startTime]").val(item.start_time);
                $dialog.find("[name=endTime]").val(item.end_time);
                $dialog.find("[name=remark]").val(item.remark);
                $dialog.find("[name=programLevel]").find("[value="+ item.program_level +"]").attr("selected", "selected");
                $dialog.find("[name=lockLocation]").find("[value="+ item.lock_location +"]").attr("selected", "selected");
                $dialog.find("[name=lockTime]").find("[value="+ item.lock_time +"]").attr("selected", "selected");
                $dialog.find("[name=finsCount]").find("[value="+ item.fins_count +"]").attr("selected", "selected");
                $dialog.find(".js-true").attr("type", "1");


            });

            $wrapper.on("click", ".btn-pass", function(){
                if(!confirm("确定通过吗")){
                    return;
                }
                var sendData = {
                    id: $(this).attr("ids"),
                    reviewerId: $(this).attr("reviewerId")
                };
                ajaxFuns({
                    url: "/LockManage/agree",
                    data: sendData,
                    successCallBack: function(data){
                        if(data.errno == "0"){
                            alert("操作成功");
                            location.reload();
                        }
                    }
                });
            });
            // 驳回
            $wrapper.on("click", ".btn-refuse", function(){
                if(!confirm("确定驳回吗")){
                    return;
                }
                var sendData = {
                    id: $(this).attr("ids"),
                    reviewerId: $(this).attr("reviewerId")
                };
                ajaxFuns({
                    url: "/LockManage/reject",
                    data: sendData,
                    successCallBack: function(data){
                        if(data.errno == "0"){
                            alert("操作成功");
                            location.reload();
                        }
                    }
                });
            });
            // 取消
            $wrapper.on("click", "#all_cancle", function(){
                if(!confirm("确定取消吗")){
                    return;
                }
                var sendData = {
                    id: $(this).attr("ids"),
                    //reviewerId: $(this).attr("reviewerId")
                };
                ajaxFuns({
                    url: "/LockManage/cancel",
                    data: sendData,
                    successCallBack: function(data){
                        if(data.errno == "0"){
                            alert("操作成功");
                            location.reload();
                        }
                    }
                });
            });            
        }
    };
    Lock.init();
})();
