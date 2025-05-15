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
                <td colspan="2">
                    <h2>Budget Request Approval</h2>
                    <h5>PT Sinar Meadow International Indonesia</h5>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Dear <strong>{{$requestData['to_department_name']}}</strong>,</p>
                    <p>Kami dari departemen {{ $requestData['from_department_name'] }} bermaksud untuk mengajukan
                        permohonan peminjaman
                        dana kepada department {{ $requestData['to_department_name'] }} dengan rincian sebagai berikut:
                    </p>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="2">
                    <h5 class="text-center">Peminjaman Dana Kepada department </h5>
                </th>
            </tr>

            <tr class="tr-odd">
                <td width="40%">Department pengaju:</td>
                <td width="60">{{ $requestData['from_department_name']}}</td>
            </tr>
            <tr>
                <td>Purchase No:</td>
                <td>{{ $requestData['budget_purchase_no']}}</td>
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
                <th colspan="2">
                    <a href="{{$approveLink}}" target="_blank" value="approve"
                        style="text-decoration: none; color: green; font-size: 24px; font-weight: bold; margin-right: 20px;">
                        Approve
                    </a>
                    <a href="{{$rejectLink}}" target="_blank" value="approve with review"
                        style="text-decoration: none; color: orange; font-size: 24px; font-weight: bold;">
                        Reject
                    </a>
                </th>
            </tr>

            <tr>
                <td colspan="2">
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