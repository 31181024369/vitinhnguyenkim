<table>
    <thead>
        <tr>
            <th>ID Product</th>
            <th>ID Category</th>
            <th>Price</th>
            <th>Price Old</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key=>$val)
            <tr>
                    <td>{{$val->product_id}}</td>
                    <td>{{$val->cat_id}}</td>
                    <td>{{$val->price}}</td>
                    <td>{{$val->price_old}}</td>
                    
            </tr>
        @endforeach
    </tbody>
</table>