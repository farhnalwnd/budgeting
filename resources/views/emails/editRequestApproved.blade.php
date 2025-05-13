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
                    <h2>Budget Approved Notification</h2>
                    <h5>PT Sinar Meadow International Indonesia</h5>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Dear <strong>{{$admin->name}}</strong>,</p>
                    <p>System mencatat adanya data budget approved baru yang masuk dengan rincian sebagai berikut:
                    </p>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="2">
                    <h5 class="text-center">data approved  baru yang masuk</h5>
                </th>
            </tr>
            <tr>
                <td>penganggar:</td>
                <td>{{ $mailData[department] }}</td>
            </tr>

            @foreach ($mailData['purchases'] as $purchase)
            <tr class="tr-odd">
                <td>Item:</td>
                <td>{{ $purchase['item_name'] }}</td>
            </tr>
            <tr>
                <td>Purchase No:</td>
                <td>{{ $purchase['purchase_no'] }}</td>
            </tr>
            <tr class="tr-odd">
                <td>Jumlah:</td>
                <td>{{ $purchase['quantity'] }}</td>
            </tr>
            <tr>
                <td>Total:</td>
                <td>{{ $purchase['total'] }}</td>
            </tr>

            <tr>
                <th colspan="2">
                    <a href=""
                        style="text-decoration: none; color: green; font-size: 24px; font-weight: bold; margin-right: 20px;">
                        Edit
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