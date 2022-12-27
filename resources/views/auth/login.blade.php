<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

    <form method="post" action="/login" style="max-width: 300px;margin:40px auto 0">

    <div>
        <label style="display:block">E-mail</label>
        <input type="text" name="email" style="width:100%;height:24px;line-height:24px;" autofocus="autofocus" />
    </div>
    <div style="margin-top:8px;">
        <label style="display:block">Password</label>
        <input type="password" name="password" style="width:100%;height:24px;line-height:24px;" />
    </div>

    @foreach ( $errors->all() as $message )
    <div style="margin-top:24px;padding:12px;background:#ff4141;color:#fff;border-radius:4px">{{ str_replace('auth.failed', 'Wrong email or password!', $message) }}</div>
    @endforeach

    <div style="margin-top:24px;text-align: center;">
        <button type="submit" class="button button-primary">Login</button>
    </div>

</form>
</body>
</html>