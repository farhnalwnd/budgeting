<!DOCTYPE html>
<html lang="en">

<head>
    <title>Form Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header-style {
            background-color: yellow;
            padding: 5px 15px;
        }

        .tr-odd {
            background-color: rgb(238, 238, 238);
        }

        td,
        th {
            padding: 0.3rem 0.5rem;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        table {
            border: 1px solid black;
            width: 100%;
            max-width: 1000px;
            margin: auto;
            border-collapse: collapse;
        }

        h2,
        h5,
        p {
            margin: 0.3em 0;
        }

        a.btn {
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
            margin-right: 20px;
        }

        .edit-link {
            color: green;
        }
    </style>
</head>

<body>
    <table>
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
                    @if($isAdmin)
                    <p>System mencatat adanya data budget approved baru yang masuk dengan rincian sebagai berikut:</p>
                    @else
                    <p>Permohonan anda kepada department <strong>{{ $deptName[0] }}</strong> telah disetujui dan status
                        purchases sudah menjadi <strong>approved</strong>.</p>
                    <p>Berikut rinciannya:</p>
                    @endif
                </td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th colspan="3" class="text-center">
                    @if($isAdmin)
                    <h5>Data purchases baru dengan status <strong>{{ $purchases->status }}</strong></h5>
                    @else
                    <h5>Department <strong>{{ $deptName[0] }}</strong> menyetujui peminjaman dana oleh department
                        <strong>{{ $deptName[1] }}</strong> sehingga status purchases saat ini adalah <strong>{{
                            $purchases->status }}</strong></h5>
                    @endif
                </th>
            </tr>

            <tr>
                <td colspan="2">Penganggar:</td>
                <td>{{ $deptName[1] }}</td>
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
                <td></td>
                <td>Total Purchase:</td>
                <td>{{ $purchases->grand_total }}</td>
            </tr>
            <tr class="tr-odd">
                <td></td>
                <td>Saldo Department:</td>
                <td>{{ $purchases->department->balance }}</td>
            </tr>

            @if($isAdmin)
            <tr>
                <th colspan="3" class="text-center">
                    <a href="{{ route('purchase-request.edit', $purchases->id) }}" class="btn edit-link">Edit</a>
                </th>
            </tr>
            @endif

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