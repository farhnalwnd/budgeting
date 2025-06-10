<!DOCTYPE html>
<html lang="en">

<head>
    <title>Budget Approval Notification</title>
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
            padding: 0.5rem;
            vertical-align: top;
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
            margin: 0.2em 0;
        }

        a.btn {
            text-decoration: none;
            color: green;
            font-size: 18px;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
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
                    <p>Data purchase baru telah <strong>disetujui</strong>. Berikut adalah rinciannya:</p>
                </td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th colspan="3" class="text-center">
                    <h5>Data purchase dengan status: <strong>{{ ucfirst($data->status) }}</strong></h5>
                </th>
            </tr>
            <tr class="tr-odd">
                <td colspan="2">Departemen Penganggar:</td>
                <td>{{ $data->department->department_name ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="2">No Purchase:</td>
                <td>{{ $data->department_id }}</td>
            </tr>

            @foreach ($purchaseDetails as $index => $detail)
            @php $isOddGroup = $index % 2 === 0; @endphp

            <tr class="{{ $isOddGroup ? 'tr-odd' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>Item:</td>
                <td>{{ $detail->item_name }}</td>
            </tr>
            <tr class="{{ $isOddGroup ? 'tr-odd' : '' }}">
                <td></td>
                <td>Jumlah:</td>
                <td>{{ $detail->quantity }}</td>
            </tr>
            <tr class="{{ $isOddGroup ? 'tr-odd' : '' }}">
                <td></td>
                <td>Total:</td>
                <td>Rp {{ number_format($detail->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            <tr>
                <td></td>
                <td>Total Purchase:</td>
                <td><strong>Rp {{ number_format($data->grand_total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr class="tr-odd">
                <td></td>
                <td>Saldo Departemen:</td>
                <td>
                    @php
                    $balance = optional($data->department)->balanceForYear(now()->year);
                    @endphp
                    {{ $balance !== null ? 'Rp ' . number_format($balance, 0, ',', '.') : '-' }}
                </td>
            </tr>

            @if ($isAdmin)
            <tr>
                <td colspan="3" class="text-center">
                    <a href="{{ route('purchase-request.edit', $data->id) }}" class="btn">edit</a>
                </td>
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