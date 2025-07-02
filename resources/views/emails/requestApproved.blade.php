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
                    <h5>Data purchases baru dengan status <strong>{{ $purchases->status ?? '-' }}</strong></h5>
                    @else
                    <h5>Department <strong>{{ $deptName[1] }}</strong> menyetujui permintaan dana oleh department
                        <strong>{{ $deptName[0] }}</strong> sehingga status purchases saat ini adalah <strong>{{
                            $purchases->status ?? '-' }}</strong></h5>
                    @endif
                </th>
            </tr>

            <tr class="tr-odd">
                <td colspan="2">Peminta:</td>
                <td>{{ $deptName[0] }}</td>
            </tr>
            <tr>
                <td colspan="2">Pemberi:</td>
                <td>{{ $deptName[1] }}</td>
            </tr>
            <tr class="tr-odd">
                <td colspan="2">budget Request No:</td>
                <td>{{ $budgetRequest->budget_req_no }}</td>
            </tr>
            <tr>
                <td colspan="2">jumlah pinjaman:</td>
                <td>{{ $budgetRequest->amount }}</td>
            </tr>

            @if($isAdmin && !empty($purchaseDetails))
            <tr>
                <th colspan="3" class="text-center">
                    <a href="{{ route('purchase-request.edit', $purchases->id) }}" class="btn edit-link">Edit</a>
                </th>
            </tr>
            @endif

            <tr>
                <td colspan="3" class="text-center">
                    <p class="text-center">Thank you for your attention.</p>
                    <br>
                    <p class="text-center">Best regards,</p>
                    <p class="text-center">PT Sinar Meadow International Indonesia</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
<script>
    setTimeout(function () {
            window.close();
        }, 3000);
</script>

</html>