$(document).ready(function () {
    select_user();
    function select_user() {
        var html ="";
        $.ajax({
            type: "post",
            url: "pages/user/action.php",
            data: {
                fn: "select_user"
            },
            dataType: "json",
            success: function (response) {
                let index = 1;
                $.each(response.data, function (i, user) {
                    html += `
                        <tr>
                            <td>${index++}</td>
                            <td>${user.user_name}</td>
                            <td>${user.user_address}</td>
                            <td>${user.user_phone}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit" data-id="${user.user_id}">แก้ไข</button>
                                <button class="btn btn-sm btn-danger delete" data-id="${user.user_id}">ลบ</button>
                            </td>
                        </tr>
                    `;
                });

                $("#tbody_user").html(html);
            }

        });
    }

    $("#btnAdd").click(function () {
        $("#user_id").val("");
        $("#user_name").val("");
        $("#user_address").val("");
        $("#user_phone").val("");

        $("#userModal").modal("show");
    });

    $(document).on("click", ".edit", function () {
        let id = $(this).data("id");

        $.ajax({
            type: "post",
            url: "pages/user/action.php",
            data: {
                fn: "get_user",
                user_id: id
            },
            dataType: "json",
            success: function (res) {
                $("#user_id").val(res.data.user_id);
                $("#user_name").val(res.data.user_name);
                $("#user_address").val(res.data.user_address);
                $("#user_phone").val(res.data.user_phone);

                $("#userModal").modal("show");
            }
        });
    });

    $("#btnSave").click(function () {

        let user_id = $("#user_id").val();
        let fn = user_id ? "update_user" : "insert_user";

        $.ajax({
            type: "post",
            url: "pages/user/action.php",
            data: {
                fn: fn,
                user_id: user_id,
                user_name: $("#user_name").val(),
                user_address: $("#user_address").val(),
                user_phone: $("#user_phone").val()
            },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    $("#userModal").modal("hide");
                    select_user();
                } else {
                    alert(res.message);
                }
            }
        });
    });

    $(document).on("click", ".delete", function () {
        let id = $(this).data("id");

        if (!confirm("ยืนยันการลบข้อมูล ?")) return;

        $.ajax({
            type: "post",
            url: "pages/user/action.php",
            data: {
                fn: "delete_user",
                user_id: id
            },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    select_user();
                } else {
                    alert(res.message);
                }
            }
        });
    });
    
});