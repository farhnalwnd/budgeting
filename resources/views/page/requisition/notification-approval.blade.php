<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intra SMII - Approval Notification</title>
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/horizontal-menu.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/style.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/skin_color.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/custom.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div class="">
        <div class="box box-inverse box-success">
            <div class="box-body">
                <a class=" float-start me-20" href="javascript:void(0)">
                    <img src="{{ asset('assets') }}/images/logowhite.png" alt="" width="100">
                </a>
                <div>
                    <small class="float-end">{{ now()->format('Y-m-d H:i:s') }}</small>
                    <div class="fs-18">{{ $rqmNbr }}</div>
                    <div class="fs-14 mb-10">Requisition Number</div>
                    <blockquote class="blockquote cover-quote fs-16 text-white">
                        {{ $message }}
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            window.close();
        }, 3000);
    </script>
</body>
</html>
