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

        .approve {
            color: green;
        }

        .reject {
            color: orange;
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
                <td colspan="2">
                    <h2>Budget Request Approval</h2>
                    <h5>PT Sinar Meadow International Indonesia</h5>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Dear <strong>{{ $requestData['to_department_name'] }}</strong>,</p>
                    <p>
                        Kami dari departemen <strong>{{ $requestData['from_department_name'] }}</strong> bermaksud
                        mengajukan permohonan peminjaman dana kepada departemen
                        <strong>{{ $requestData['to_department_name'] }}</strong> dengan rincian sebagai berikut:
                    </p>
                </td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th colspan="2" class="text-center">
                    <h5>Peminjaman Dana Kepada Departemen</h5>
                </th>
            </tr>

            <tr class="tr-odd">
                <td width="40%">Departemen Pengaju:</td>
                <td width="60%">{{ $requestData['from_department_name'] }}</td>
            </tr>
            <tr>
                <td>Purchase No:</td>
                <td>{{ $requestData['budget_purchase_no'] }}</td>
            </tr>
            <tr class="tr-odd">
                <td>Jumlah:</td>
                <td>{{ $requestData['amount'] }}</td>
            </tr>
            <tr>
                <td>Alasan:</td>
                <td>{{ $requestData['reason'] }}</td>
            </tr>

            <tr class="tr-odd">
                <th colspan="2" class="text-center">
                    <a href="{{ $approveLink }}" target="_blank" class="btn approve">Approve</a>
                    <a href="{{ $rejectLink }}" target="_blank" class="btn reject">Reject</a>
                </th>
            </tr>

            <tr>
                <td colspan="2" class="text-center">
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