<!doctype html>
<html lang="es_CO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>{{$user->name}}</h1>

<p>Gracias por crear tu cuenta, por favor verificala en al siguiente enlace</p>
<a href="{{route('verify', [$user->verification_token ?? 'NO TIENE TOKEN Y SE LE ASGINA MANUAL'])}}">Verificar !!!!!!!</a>
</body>
</html>
