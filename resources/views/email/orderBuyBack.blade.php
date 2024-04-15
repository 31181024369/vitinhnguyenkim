<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>email order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
      table, th, td {
        border: 1px solid black;
      }
</style>
</head>
<body>
    <!-- <h1 style="text-align: center;">Vi tính Nguyên Kim xin trân trọng cảm ơn quý khách đã mua hàng.</h2><br> -->
    <h2 style="text-align: center;">  Bảng thông tin đơn hàng Khách hàng đã mua lại.</h2>
    Mã đơn hàng: <span>{{ $data['d_code'] }}</span><br>
    Tên khách hàng: <span>{{ $data['d_name'] }}</span><br>
    Địa chỉ: <span>{{ $data['d_adress'] }}</span><br>
    Số điện thoại: <span>{{ $data['d_phone'] }}</span><br>
    Gmail: <span>{{ $data['d_gmail'] }}</span><br>
    <!-- Tổng số giỏ hàng: <span>{{  number_format($data['total_cart'], 0, '', ',') }} <span></span>VNĐ</span><br> -->
    Tổng tạm tính: <span>{{  number_format($data['total_price'], 0, '', ',') }} <span></span>VNĐ</span><br>
    
    Tổng giá phiếu giảm giá: <span>{{  number_format($data['CouponDiscout'], 0, '', ',') }} <span>VNĐ</span></span><br>
    Thành tiền: <span>{{  number_format($data['total_price']-$data['CouponDiscout'], 0, '', ',')  }} <span>VNĐ</span></span><br>
    Tặng thẻ cào trị giá : <span> {{  number_format($data['cardPromotion'], 0, '', ',')  }} <span>VNĐ</span></span><br>


    <table class="table table-striped">
  <thead>
    <tr>
    <th scope="col">Id đơn hàng</th>
      <th scope="col">Tên sản phẩm</th>
      <th scope="col">Giá sản phẩm</th>
      <th scope="col">Số lượng</th>
      <th scope="col">Tổng cộng</th>
    </tr>
  </thead>
  <tbody>
  @foreach($data['listProduct'] as $item)
    <tr>
      <td>{{ $item['order_id'] }}</td>
      <td>{{ $item['item_title'] }}</td>
      <td>{{  number_format($item['item_price'], 0, '', ',') }} <span>VNĐ</span></td>
      <td>{{ $item['quantity'] }}</td>
      <td>{{  number_format( $item['subtotal'], 0, '', ',') }} <span>VNĐ</span></td>
    </tr>
  @endforeach
  </tbody>
</table>

</body>
</html>
