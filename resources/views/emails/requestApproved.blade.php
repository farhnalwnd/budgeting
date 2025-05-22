<!DOCTYPE html>
<html lang="en">

<head>
    <title>Form detail</title>
    <style>
        .header-style {
            background-color: yellow;
            padding: 5px;
            padding-left: 15px;
        }

        .tr-odd {
            background-color: rgb(238, 238, 238);
        }

        td,
        th {
            padding: 0.3rem 0.5rem;
        }
    </style>
</head>

<body>
    <table style="border: 1px solid black; width:100%; max-width:1000px; margin:auto;">
        <thead>
            <tr class="header-style">
                <td colspan="3">
                    <h2>Budget Approved Notification</h2>
                    <h5>PT Sinar Meadow International Indonesia</h5>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    @if($isAdmin)
                    <p>Dear <strong>{{$user->name}}</strong>,</p>
                    <p>System mencatat adanya data budget approved baru yang masuk dengan rincian sebagai berikut:
                    </p>
                    @else
                    <p>Dear <strong>{{$user->name}}</strong>,</p>
                    <p>permohonan anda kepada department{{ $deptName[0] }} telah disetujui dan status purchases sudah menjadi approved.
                    </p>
                    <p>berikut rincianya:</p>
                    @endif
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="3">
                    @if($isAdmin)
                    <h5 class="text-center">data purchases baru dengan status {{$purchases->status}}</h5>
                    @else
                    <h5 class="text-center">department {{ $deptName[0]}} menyetujui peminjaman dana oleh department {{$deptName[1]}}
                        sehingga status purchases
                        saat ini adalah {{$purchases->status}}</h5>
                    @endif
                </th>
            </tr>
            <tr>
                <td colspan="2">penganggar:</td>
                <td>{{ $deptName[1] }}</td>
            </tr>
            @foreach ($purchaseDetails as $detail)
            <tr class="tr-odd">
                <td style="width:auto">{{$loop->iteration}}</td>
                <td>Item:</td>
                <td>{{ $detail->item_name}}</td>
            </tr>
            <tr>
                <td style="width:auto"></td>
                <td>Jumlah:</td>
                <td>{{ $detail->quanitity}}</td>
            </tr>
            <tr class="tr-odd">
                <td style="width:auto"></td>
                <td>Total:</td>
                <td>{{ $detail->total_amount}}</td>
            </tr>
            @endforeach
            <tr>
                <td style="width:auto"></td>
                <td>total purchase:</td>
                <td>{{$purchases->grand_total}}</td>
            </tr>
            <tr class="tr-odd">
                <td style="width:auto"></td>
                <td>saldo department:</td>
                <td>{{$purchases->department->balance}}</td>
            </tr>
            @if($isAdmin)
            <tr>
                <th colspan="3">
                    <a href=""
                        style="text-decoration: none; color: green; font-size: 24px; font-weight: bold; margin-right: 20px;">
                        Edit
                    </a>
                </th>
            </tr>
            @endif
            <tr>
                <td colspan="3">
                    <p style="text-align: center;">Kindly approve it at your earliest convenience so we can proceed.</p>
                    <p style="text-align: center;">Thank you for your attention.</p>
                    <br>
                    <p style="text-align: center;">Best regards,</p>
                    <p style="text-align: center;">PT Sinar Meadow International Indonesia</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>