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
    <th>Id thương hiệu</th>
    <th>Tên thương hiệu</th>
    <th>Url thương hiệu</th>
  
  </tr>
    </thead>
    <tbody>
        @foreach($data as $key=> $item)
            <tr>
              <td>{{ $item['brand_id'] }}</td>
              <td>{{ $item['title'] }}</td>
              <td>{{ $item['friendly_url'] }}</td> 
            </tr>
        @endforeach
        
    </tbody>
   
</table>
</body>
</html>