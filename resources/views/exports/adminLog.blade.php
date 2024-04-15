<table>
    <thead>
        <tr>
           
            <th>Tên đăng nhập</th>
            <th>Tên hiển thị</th>
            <th>Trang</th>
            <th>Hoạt động</th>
            <th>Thời gian</th>
           
            <th>Mô tả hoạt động</th>
        </tr>
    </thead>
    <tbody>

        @foreach($data as $key=> $val)
            <tr>
                    <td>{{ $val['username'] }}</td> 
                    <td>{{ $val['display_name'] }}</td> 
                    <td>{{ $val['cat'] }}</td>
                    <td>{{ $val['action'] }}</td>
                    <td>{{ $val['time'] }}</td>
                    <td>{{ $val['description'] }}</td>
                    <!-- <td>{{$val['action']}}</td> -->
            </tr>
        @endforeach
    </tbody>
</table>