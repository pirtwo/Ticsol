<html>
<head></head>
<body style="background: white; color: black">
<h1>{{$title}}</h1>
<p>
    Dear {{$email}},
    This is a invitation email for registering in Ticsol application, click the following link to register in app.
    <br/>
    
    {{ url($url . '?token=' . $token) }}
    
    
</p>
</body>
</html>