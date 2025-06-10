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
                    <p>Dear <strong>{{ $user->name }}</strong>,</p>
                    <p>System mencatat adanya penolakan peminjaman dana yang telah anda request sebelumnya dengan
                        rincian sebagai berikut:</p>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="3">
                    <h5 class="text-center">data yang ditolak oleh department {{ $deptName[0] }}</h5>
                </th>
            </tr>
            <tr>
                <td colspan="2">penganggar:</td>
                <td>{{ $deptName[1] }}</td>
            </tr>
            <tr>
                <td colspan="2">Purchase No:</td>
                <td>{{ $purchases->purchase_no }}</td>
            </tr>

            @foreach ($purchaseDetails as $detail)
            @php $rowClass = $loop->odd ? 'tr-odd' : ''; @endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $loop->iteration }}</td>
                <td>Item:</td>
                <td>{{ $detail->item_name }}</td>
            </tr>
            <tr class="{{ $rowClass }}">
                <td></td>
                <td>Jumlah:</td>
                <td>{{ $detail->quantity }}</td>
            </tr>
            <tr class="{{ $rowClass }}">
                <td></td>
                <td>Total:</td>
                <td>{{ $detail->total_amount }}</td>
            </tr>
            @endforeach

            <tr>
                <td colspan="2">grand total:</td>
                <td>{{ $purchases->grand_total }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-center">
                    <p class="text-center">Kindly approve it at your earliest convenience so we can proceed.</p>
                    <p class="text-center">Thank you for your attention.</p>
                    <br>
                    <p class="text-center">Best regards,</p>
                    <p class="text-center">PT Sinar Meadow International Indonesia</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>