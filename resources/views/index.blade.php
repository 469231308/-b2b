<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action={{url("/addconfig")}} method="post">
    短信模版编号：<input type="text" name="code"><br>
    短信模版名称：<input type="text" name="smstype"><br>
    短信模版accessKeyId:<input type="text" name="appid"><br>
    短信模版accessKeySecret:<input type="text" name="appsc"><br>
    短信模版signName：<input type="text" name="signName"><br>
    短信模版参数（可为空）：<input type="text" name="query"><br>
    {{ csrf_field() }}
    <br>
    <input type="submit" value="提交">
</form>


</body>
</html>
