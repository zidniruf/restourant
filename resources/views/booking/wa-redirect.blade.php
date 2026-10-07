<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mengarahkan ke WhatsApp...</title>
</head>
<body>
    <p style="font-family: sans-serif; text-align: center; margin-top: 50px;">
        Mengarahkan ke WhatsApp... <br>
        <small>Jika tidak otomatis, <a href="{{ $url }}">klik di sini</a></small>
    </p>
    <script>
        // JS redirect — tidak memotong URL panjang
        window.location.href = {!! json_encode($url) !!};
    </script>
</body>
</html>