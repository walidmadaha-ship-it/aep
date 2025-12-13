<script src="pages/user/view.js"></script>
<button class="btn btn-success" id="btnAdd">เพิ่มผู้ใช้</button>
<!-- ปุ่มเพิ่ม -->

<!-- ตาราง -->
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>ชื่อ-สกุล</th>
            <th>บ้านเลขที่</th>
            <th>เบอร์โทร</th>
            <th>การจัดการ</th>
        </tr>
    </thead>
    <!-- ส่วนตัว -->
    <tbody id="tbody_user">
    </tbody>
</table>

<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">จัดการผู้ใช้</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="user_id">

                <div class="mb-2">
                    <label>ชื่อผู้ใช้</label>
                    <input type="text" id="user_name" class="form-control">
                </div>

                <div class="mb-2">
                    <label>ที่อยู่</label>
                    <input type="text" id="user_address" class="form-control">
                </div>

                <div class="mb-2">
                    <label>เบอร์โทร</label>
                    <input type="text" id="user_phone" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="btnSave">บันทึก</button>
            </div>
        </div>
    </div>
</div>
