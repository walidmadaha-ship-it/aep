$(document).ready(function () {
    var user_id = 3;
    var editingRow = null;
    var mode = "add"; // add หรือ edit

    // ===== เปิด Modal เพื่อเพิ่ม =====
    $('#openAddModal').click(function () {
        mode = "add";
        editingRow = null;

        // ล้างค่า input
        $('#user_name').val('');
        $('#user_address').val('');
        $('#user_phone').val('');

        // เปลี่ยน title และปุ่ม
        $('.modal-title').text('เพิ่มผู้ใช้น้ำ');
        $('#insert').text('เพิ่ม').removeClass('btn-primary').addClass('btn-success');

        $('#addUserModal').modal('show');
    });

    // ===== กดปุ่มใน modal (เพิ่มหรือแก้ไข) =====
    $('#insert').click(function (e) {
        e.preventDefault();

        var name = $('#user_name').val();
        var address = $('#user_address').val();
        var phone = $('#user_phone').val();

        if (name == '' || address == '' || phone == '') {
            alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }

        if (mode === "add") {
            // ------- เพิ่มข้อมูล -------
            user_id++;

            var row = `
                <tr>
                    <td>${user_id}</td>
                    <td>${name}</td>
                    <td>${address}</td>
                    <td>${phone}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit">แก้ไข</button>
                        <button class="btn btn-sm btn-danger delete">ลบ</button>
                    </td>
                </tr>
            `;

            $('tbody').append(row);
            alert("เพิ่มผู้ใช้น้ำสำเร็จ");

        } else if (mode === "edit") {
            // ------- อัปเดตข้อมูล -------
            editingRow.find('td:eq(1)').text(name);
            editingRow.find('td:eq(2)').text(address);
            editingRow.find('td:eq(3)').text(phone);

            alert("แก้ไขข้อมูลสำเร็จ");
        }

        $('#addUserModal').modal('hide');
    });

    // ===== ปุ่มลบ =====
    $(document).on('click', '.delete', function () {
        if (confirm("คุณต้องการลบข้อมูลนี้หรือไม่ ?")) {
            $(this).closest('tr').remove();
        }
    });

    // ===== ปุ่มแก้ไข =====
    $(document).on('click', '.edit', function () {
        mode = "edit";
        editingRow = $(this).closest('tr');

        var name = editingRow.find('td:eq(1)').text();
        var address = editingRow.find('td:eq(2)').text();
        var phone = editingRow.find('td:eq(3)').text();

        // ใส่ข้อมูลลง modal
        $('#user_name').val(name);
        $('#user_address').val(address);
        $('#user_phone').val(phone);

        // เปลี่ยน title และปุ่ม
        $('.modal-title').text('แก้ไขผู้ใช้น้ำ');
        $('#insert').text('บันทึก').removeClass('btn-success').addClass('btn-primary');

        $('#addUserModal').modal('show');
    });

});