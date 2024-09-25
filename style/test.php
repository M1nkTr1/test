<?php
 //ket noi db
 $con=mysqli_connect('localhost','root','','72dctt22_qlthuvien')
 or die('lỗi kết nối');
 $ml=$_GET['maloai'];
 $sql1="SELECT * FROM loaisach where maloai='$ml'";
 $data=mysqli_query($con,$sql1);
 //xy li nut luu
 $ml='';
$tl='';
$mt='';
$gc='';
 if(isset($_POST['btluu'])){
    $ml=$_POST['txtmaloai'];
    $tl=$_POST['txttenloai'];
    $mt=$_POST['txtmota'];
    $gc=$_POST['txtghichu'];
     //chay truy van chen du lieu\
 $sql="UPDATE  loaisach SET tenloai='$tl',mota='$mt',ghichu='$gc' where maloai='$ml'";
 $kq=mysqli_query($con,$sql);
 if($kq){ 
    header("location:baithi/dsloaisach.php");
    exit;

 }
 else "<script>alert('that bai')<script>";
}
 mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    include_once'./header1.php';
    ?>
    <div class="aside">
        <form method="post" action="">
        <table >
            <tr>
                <td colspan="2" STYLE ="text-align:center;">
                    <hs>sua thong tin loai sach</hs>
                </td>
            </tr>
            <?php
                if(isset($data)&&$data!=NULL){
                    while($row=mysqli_fetch_array($data)){
                        ?>


            <tr>
                <td class="col1">ma loai sach</td>
                <td class=""col2>
                    <input type="text" name="txtmaloai" value="<?php echo $row['maloai']?>">
                </td>
            </tr>
            <tr>
                <td class="col1">ten loai sach</td>
                <td class=""col2>
                    <input type="text" name="txttenloai" value="<?php echo $row['tenloai']?>">
                </td>
            </tr>
            <tr>
                <td class="col1">mo ta</td>
                <td class=""col2>
                    <input type="text" name="txtmota" value="<?php echo $row['mota']?>">
                </td>
            </tr>
            <tr>
                <td class="col1">ghi chu</td>
                <td class=""col2>
                    <input type="text" name="txtghichu" value="<?php echo $row['ghichu']?>">
                </td>
            </tr>
            <?php
                    }
                }
            ?>
            <tr>
                <td class="col1"></td>
            
            <td class="col2">
             <input type="submit" value="luu" name="btluu"></td>
            </tr>

        </table>
        </form>
    </div>
    
</body>
</html>