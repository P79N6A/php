$(".js_forbidden").click(function () {
    var _uid = $(this).data("uid");
    $('#forbiddenModal').find(".uid").text(_uid);
    $('#forbiddenModal').modal('show');
});

$(".js_forbidden_sub").click(function () {
    var _uid = $('#forbiddenModal').find(".uid").text();
    var _reason = $('#forbiddenModal').find("input[name=reason]:checked").val();
    var _expire = $('#forbiddenModal').find("input[name=expire]:checked").val();
    if (typeof(_reason) == "undefined" || _reason == "") {
        error($('#forbiddenModal .modal-body'), '请选择封禁理由')
        return;
    }

    post("/UserForbidden/addforbidden", {uid: _uid, reason: _reason, expire: _expire},
        function (ret) {
            if (ret.errno == "0") {
                $('#forbiddenModal').modal('hide');
                message($('#forbiddenModal .modal-body'), "处理成功")
                window.location.reload();
            } else {
                error($('#forbiddenModal .modal-body'), ret.errmsg)
            }
        }
    );
});

$(".js_unforbidden").click(function () {
    var _uid = $(this).data("uid");

    post("/UserForbidden/unforbidden", {uid: _uid},
        function (ret) {
            if (ret.errno == "0") {
                message("处理成功")
                window.location.reload();
            } else {
                error(ret.errmsg)
            }
        }
    );
});

$(".js_rank").click(function () {
    var _uid = $(this).data("uid");
    $('#rankModal').find(".modalId").text(_uid);
    $(".newRanks").prop('checked', false);

    post("/UserOld/getUserRank", {uid: _uid},
        function (ret) {
            if (ret.errno == "0") {
                for (var i in ret.data) {
                    $(".newRanks[value='" + ret.data[i] + "']").prop('checked', true);
                }
            } else {
                error($('#rankModal .modal-body'), ret.errmsg)
            }
        }
    );

    $('#rankModal').modal('show');

});

$(".js_rank_sub").click(function () {
    var _uid = $('#rankModal').find(".modalId").text();
    var _ranks = new Array();

    $(".newRanks:checked").each(function (i, n) {
        _ranks.push($(n).val());
    });

    post("/UserOld/addRank", {uid: _uid, ranks: _ranks},
        function (ret) {
            if (ret.errno == "0") {
                $('#rankModal').modal('hide');
                message($('#rankModal .modal-body'), "处理成功")
            } else {
                error($('#rankModal .modal-body'), ret.errmsg)
            }
        }
    );
});

