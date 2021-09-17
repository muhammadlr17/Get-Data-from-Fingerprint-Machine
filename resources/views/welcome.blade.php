<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Get Data from Machine</title>
</head>

<body>
    <form action="{{ route('presence-log.getData') }}" method="POST">
        @csrf
        <button type="submit">Sync</button>
    </form>
    @if (Session::has('success'))
        <p style="color: red">{{ Session::get('success') }}</p>
    @endif
</body>

</html>
