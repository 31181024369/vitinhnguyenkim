<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <title>Thông tin khách hành đăng kí</title>
</head>
<body>
    <h2 style="text-align: center;">  Bảng thông tin khách hàng đăng kí.</h2>

    

    Mã đơn hàng: <span>{{ $info['username'] }}</span><br>
    Tên khách hàng: <span>{{ $info['email'] }}</span><br>
    Địa chỉ: <span>{{  $info['address'] }}</span><br>
    Số điện thoại: <span>{{  $info['full_name'] }}</span><br>
    Phone: <span>{{  $info['phone'] }}</span><br>
    Ngày tham gia: <span>{{  $info['date_join'] }}</span><br>
    Tên công ty: <span>{{  $info['Tencongty'] }}</span><br>
    Mã số thuế: <span>{{  $info['Masothue'] }}</span><br>
    Địa chỉ công ty: <span>{{  $info['Diachicongty'] }}</span><br>
    Sđt công ty: <span>{{  $info['Sdtcongty'] }}</span><br>

    Email công ty: <span>{{  $info['emailcty'] }}</span><br>
    Mã khách hàng: <span>{{  $info['MaKH'] }}</span><br>
    Địa chỉ phường: <span>{{  $info['ward'] }}</span><br>
    Địa chỉ huyện : <span>{{  $info['district'] }}</span><br>

    Số điện thoại: <span>{{  $info['city_province'] }}</span><br>
    Mã KH định danh: <span>{{  $info['MaKHDinhDanh'] }}</span><br>
</body>
</html>