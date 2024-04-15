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
    <th>product_id</th>
    <th>title</th>
    <th>catName</th>
    <th>maso</th>
    <th>macn</th>
    <th>price</th>
    <th>price_old</th>
    <th>brand_name</th>
    <th>picture</th>
    <th>static</th>
    <th>technology</th>
    <th>describe</th>
  </tr>
    </thead>
    <tbody>
        @foreach($data as $key=> $item)
            <tr>
              <td>{{ $item['product_id'] }}</td>
              <td>{{ $item['title'] }}</td>
              <td>{{ $item['cat_name'] }}</td> 
              <td>{{$item['maso'] }}</td>
              <td>{{ $item['macn'] }}</td>
              <td>{{  $item['price'] }}</td>
              <td>{{ $item['price_old'] }}</td> 
              <td>{{ $item['brand_name'] }}</td> 
              <td>{{ $item['picture'] }}</td> 
              <td>{{ $item['static'] }}</td> 
              <td>{{ $item['technology'] }}</td> 
              <td>{{ $item['describe'] }}</td> 
             
            </tr>
        @endforeach
        
    </tbody>
   
</table>
</body>
</html>