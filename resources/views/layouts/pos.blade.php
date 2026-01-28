<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>POS Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bootstrap/css/bootstrap.min.css') }}">

    <style>
        body {
            margin: 0;
            background: #f4f6f9;
            height: 100vh;
            overflow: hidden;
        }
        .pos-container {
            display: flex;
            height: 100vh;
        }
        .produk {
            width: 70%;
            padding: 20px;
            overflow-y: auto;
        }
        .keranjang {
            width: 30%;
            background: #fff;
            padding: 20px;
            border-left: 2px solid #ddd;
        }
        .total {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>

@yield('content')

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
