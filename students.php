<?php
$con = mysqli_connect('localhost', 'root', '', 'quanLyKyTuc') or die('Lỗi kết nối');
$rowNumber = 1; 
$p='';

if (isset($_GET['find'])) {
    $p = $_GET['p'];
    $query = "SELECT students.image_path, students.MSV, students.birthday, students.sex, students.phoneNumber, students.class, students.id AS student_id, students.name AS student_name, contracts.id AS contract_id, rooms.name FROM students INNER JOIN contracts ON students.id = contracts.studentId INNER JOIN rooms ON contracts.roomId = rooms.id where contracts.cancelledAt is null and (students.name like '%$p%' or rooms.name like '%$p%')";
    $students = mysqli_query($con, $query);
} else {
    $query = "SELECT students.image_path, students.MSV, students.birthday, students.sex, students.phoneNumber, students.class, students.id AS student_id, students.name AS student_name, contracts.id AS contract_id, rooms.name FROM students INNER JOIN contracts ON students.id = contracts.studentId INNER JOIN rooms ON contracts.roomId = rooms.id where contracts.cancelledAt is null";
    $students = mysqli_query($con, $query);
}
mysqli_close($con);
?>

<body>
    <div class="flex">
        <?php include_once './sidebar.php'; ?>
        <div class="flex-1 flex flex-col max-h-screen overflow-scroll">
            <div class="py-8 px-4 border-b flex justify-between">
                <div></div>
                <button class="p-2 px-4 rounded-full hover:bg-gray-100 transition">Chào mừng bạn quay trở lại </button>
            </div>
            <div class="flex-1 p-4">
                <form method="GET">
                    <div class="flex gap-3 items-center">
                        <input name="p" class="border p-3 focus:outline-none outline-none flex-1" type="text"
                            placeholder="Tìm kiếm phòng theo tên, theo phòng" value="<?php echo $p ?>">
                        <button name="find" type="submit" class="border border-slate-800 p-3">Tìm kiếm</button>
                        <a href="students_add.php" class="text-white bg-slate-800 p-3">+ Thêm mới</a>
                    </div>
                </form>
                <?php if (mysqli_num_rows($students) == 0) : ?>
                    <div class="h-[60vh] grid place-items-center">Hiện tại không có sinh viên nào trong danh sách.</div>
                <?php else : ?>
                <div class="py-8">
                    <table class="w-full border-spacing-2 overflow-scroll text-sm rounded-xl">
                        <tr class="text-left bg-slate-800 text-white">
                            <th class="px-2 py-4 w-[60px]">STT</th>
                            <th class="px-2 py-4 w-[60px]">Ảnh</th>
                            <th class="px-2 py-4 w-[200px]">Họ và tên</th>
                            <th class="px-2 py-4 w-[150px]">Mã sinh viên</th>
                            <th class="px-2 py-4 w-[220px]">Ngày tháng năm sinh</th>
                            <th class="px-2 py-4 w-[120px]">Giới tính</th>
                            <th class="px-2 py-4 w-[150px]">Số điện thoại</th>
                            <th class="px-2 py-4 w-[100px]">Lớp</th>
                            <th class="px-2 py-4 w-[50px]">Phòng</th>
                            <th class="px-2 py-4 w-[150px]"></th>
                        </tr>
                        <?php
                            while ($student = mysqli_fetch_assoc($students)) :
                        ?>
                        <tr class="text-slate-600">
                            <td><?php echo $rowNumber < 10 ? '0' . $rowNumber : $rowNumber; ?></td>
                            <td>
                                <img class="h-[40px] w-[40px] object-cover"
                                    src="<?php echo ('images/' . $student['image_path']); ?>" alt="">
                            </td>
                            <td>
                                <?php echo $student['student_name']; ?>
                            </td>
                            <td>
                                <?php echo $student['MSV']; ?>
                            </td>
                            <td>
                                <?php echo $student['birthday']; ?>
                            </td>
                            <td>
                                <?php echo $student['sex'] == 'male' ? "Nam" : "Nữ"; ?>
                            </td>
                            <td>
                                <?php echo $student['phoneNumber']; ?>
                            </td>
                            <td>
                                <?php echo $student['class']; ?>
                            </td>
                            <td>
                                <?php echo $student['name']; ?>
                            </td>
                            <td>
                                <div class="flex gap-4 items-center justify-center">
                                        <a href="students_edit.php?ma=<?php echo $student['student_id']; ?>" class="material-symbols-outlined">
                                            edit
                                        </a>
                                </div>
                            </td>
                        </tr>
                        <?php
                            $rowNumber++;
                            endwhile;
                        ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>