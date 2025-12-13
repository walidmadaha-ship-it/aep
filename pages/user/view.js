$(document).ready(function () {
    // =================== ดึงข้อมูลมาแสดง ==================
    select_user();//ตั้งชือฟังก์ชันในการดึง user
    function select_user() {//ตั้งชือฟังก์ชันในการดึง user
        var html ="";
        $.ajax({
            type: "post",
            url: "pages/user/action.php",//ส่งไปที่หน้าฐานข้อมูล user
            data: {
                fn: "select_user"//ส่งไปที่หน้าฐานข้อมูล user
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

    //================== ปุ่มเพิ่ม ==========================

    $("#btnAdd").click(function () {
        $("#user_id").val(""); //กล่องid
        $("#user_name").val(""); //กล่องเพิ่มชือ
        $("#user_address").val(""); //กล่องเพิ่มที่อยู่
        $("#user_phone").val(""); //กล่องเบอร์โทรศัพท์

        $("#userModal").modal("show");//โชว์ modal
    });

    //================== ปุ่มแก้ไข ==========================

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
                $("#user_id").val(res.data.user_id);//
                $("#user_name").val(res.data.user_name);//
                $("#user_address").val(res.data.user_address);//
                $("#user_phone").val(res.data.user_phone);//

                $("#userModal").modal("show");
            }
        });
    });

    //========================= ปุ่มบันทึก =====================

    $("#btnSave").click(function () {

        let user_id = $("#user_id").val();
        let fn = user_id ? "update_user" : "insert_user";//ฟังก็ชัน แก้ไข และเพิ่ม

        $.ajax({
            type: "post",
            url: "pages/user/action.php",
            data: {
                fn: fn,
                user_id: user_id, //ส่ง user_id ไปบันทึก
                user_name: $("#user_name").val(),//ส่ง ser_name ไปบันทึก
                user_address: $("#user_address").val(), //ส่ง user_address ไปบันทึก
                user_phone: $("#user_phone").val() //ส่ง user_phone ไปบันทึก
            },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    $("#userModal").modal("hide");
                    alert("บันทึกสำเร็จ");//แจ้งเตือนว่าบันทึกสำเร็จ
                    select_user();
                } else {
                    alert(res.message);
                }
            }
        });
    });

    //================= ปุ่มลบ ===================================

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