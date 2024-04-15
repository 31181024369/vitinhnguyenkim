<table>
    <thead>
        <tr>
            <th>Sản phẩm</th>
            <th>Danh mục</th>
            <th>Giá sản phẩm</th>
            <th>Tên khách hàng</th>
            <th>Sdt</th>
            <th>Email</th>
            <th>Số lượng</th>
            <th>Giá giảm</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $val)
            <tr>
                    <td>{{$val['product_id']}}</td>
                    <td>{{$val['price']}}</td>
                    <td>{{$val['category']}}</td>
                    <td>{{$val['name']}}</td>
                    <td>{{$val['phone']}}</td>
                    <td>{{$val['email']}}</td>
                    <td>{{$val['quantity']}}</td>
                    <td>{{$val['reducePrice']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>