<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table>
    <thead>
    <tr>
    <th>Id đơn hàng</th>
    <th>Mã đơn hàng</th>
    <th>Tên khách hàng</th>
    <th>Địa chỉ</th>
    <th>Số điện thoại</th>
    <th>Email</th>
    <th>Tên công ty</th>
    <th>Địa chỉ công ty</th>
    <th>Số điện thoại công ty</th>
    <th>Email công ty</th>
    <th>Tổng số giỏ hàng</th>
    <th>Tổng giá</th>
    <th>Phương pháp vận chuyển</th>
    <th>Phương thức thanh toán</th>
    <th>Ngày đặt hàng</th>
    <th>Ngày xuất hàng</th>
    <th>Trạng thái</th>

    <th>Chờ xử lý</th>
    <th>Thời gian chờ thanh toán và chờ xử lý (Phút)</th>
    <th>Chờ thanh toán</th>
    <th>Thời gian đã thanh toán và chờ thanh toán  (Phút)</th>
    <th>Đã thanh toán</th>
    <th>Thời gian đang giao hàng và đã thanh toán  (Phút)</th>
    <th>Đang giao hàng</th>
    <th>Thời gian đã hoàn tất và đang giao hàng  (Phút)</th>
    <th>Đã hoàn tất</th>
    <th>Thời gian đã hủy bỏ và đã hoàn tất  (Phút)</th>
    <th>Đã hủy bỏ</th>
    <th>Thời gian hủy bỏ và đã hủy bỏ  (Phút)</th>
    <th>Khách hàng hủy bỏ</th>
  </tr>
    </thead>
    <tbody>
        @foreach($data as $key=> $item)
            <tr>
              <td>{{ $item['orderId'] }}</td>
              <td>{{ $item['orderCode'] }}</td>
              <td>{{ $item['name'] }}</td> 
              <td>{{$item['address'] }}</td>
              <td>{{ $item['phone'] }}</td>
              <td>{{  $item['email'] }}</td>
              <td>{{ $item['companyName'] }}</td> 
              <td>{{ $item['companyAddress'] }}</td> 
              <td>{{ $item['companyPhone'] }}</td>
              <td>{{  $item['companyEmail'] }}</td>
              <td>{{ number_format($item['totalCart'], 0, '', ',') }} <span>VNĐ</span></td> 
              <td>{{ number_format($item['totalPrice'] , 0, '', ',')  }} <span>VNĐ</span></td>
              <td>{{  $item['shippingMethod'] }}</td>
              <td>{{ $item['paymentMethod'] }}</td> 
              <td>{{ $item['dateOrder'] }}</td> 
              <td>{{  $item['shipDate'] }}</td>
              <td>{{ $item['status'] }}</td> 


              <td>{{ $item['date_order_status1'] }}</td> 
              <td>{{ $item['date_order_2_1'] }}</td> 
            
              <td>{{ $item['date_order_status2'] }}</td>

              <td>{{ $item['date_order_3_2'] }}</td> 
              <td>{{ $item['date_order_status3'] }}</td> 
              <td>{{ $item['date_order_4_3'] }}</td> 

              <td>{{ $item['date_order_status4'] }}</td>
              <td>{{ $item['date_order_5_4'] }}</td> 

              <td>{{ $item['date_order_status5'] }}</td> 
              <td>{{ $item['date_order_6_5'] }}</td> 

              <td>{{ $item['date_order_status6'] }}</td> 
              <td>{{ $item['date_order_7_6'] }}</td> 

              <td>{{ $item['date_order_status7'] }}</td> 

            </tr>
        @endforeach
        
    </tbody>
   
</table>
</body>
</html>