<table>
    <thead>
        <tr>
            <th>Mã khách hàng</th>
            <th>Link sản phẩm</th>
            <th>Tên khách hàng</th>
            <th>SĐT</th>
            <th>Tên sản phẩm</th>
            <th>Số lần</th>
            <th>Thời gian</th>
            <th>Danh mục</th>
            <!-- <th>Action</th> -->
        </tr>
    </thead>
    <tbody>
        @foreach($data as $val)
            <tr>
                    <td>{{$val['maKH']}}</td>
                    <td>{{$val['url']}}</td>
                    <td>{{$val['membername']}}</td>
                    <td>{{$val['phone']}}</td>
                    <td>{{$val['nameProduct']}}</td>
                    <td>{{$val['count']}}</td>
                    <td>{{$val['date']}}</td>
                    <td>{{$val['module']}}</td>
                    <!-- <td>{{$val['action']}}</td> -->
            </tr>
        @endforeach
    </tbody>
</table>