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
                    <p>budget request telah <strong>disetujui</strong>. tetapi purchases harus dihentikan karna wallet department anda tetap tidak cukup. Berikut adalah rinciannya:</p>
                </td>
            </tr>
        </thead>

        <tbody>

            @php
            dd($budgetRequest);
            @endphp
            
            <tr>
                <th colspan="3" class="text-center">
                    <h5>Data purchase dengan status: <strong>{{ ucfirst($purchases->status) }}</strong></h5>
                </th>
            </tr>
            <tr class="tr-odd">
                <td colspan="2">Departemen Penganggar:</td>
                <td>{{ $purchases->department->department_name ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="2">No Purchase:</td>
                <td>{{ $purchases->purchase_no }}</td>
            </tr>
            <tr class="tr-odd">
                <td colspan="2">Department dituju:</td>
                <td>{{ $budgetRequest->toDepartment->department_name }}</td>
            </tr>
            <tr>
                <td colspan="2">Budget yang dipinjam:</td>
                <td><strong>Rp {{ number_format($budgetRequest->amount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr class="tr-odd">
                <td colspan="2">Total Purchase:</td>
                <td><strong>Rp {{ number_format($purchases->grand_total, 0, ',', '.') }}</strong></td>
            </tr>

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