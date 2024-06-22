<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            max-width: 210mm;
            margin: auto;
            padding: 10mm;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            width: 50px;
            height: 50px;
        }
        .header .title {
            font-size: 20px;
            font-weight: bold;
        }
        .header .date {
            font-size: 12px;
            font-weight: bold;
        }
        .content {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content th, .content td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .content th {
            background-color: #f2f2f2;
        }
        .remarks {
            margin-top: 10px;
            font-size: 10px;
        }
        .button {
            text-align: center;
            margin-top: 20px;
        }
        .button a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('sinarmeadow.png') }}" alt="Logo" style="float: left;">
            <div class="title" style="text-align: center;">Requisition Report - SMII</div>
            <div class="date" style="float: right;">{{ date('d/m/y H:i:s') }}</div>
        </div>
        <div class="req-nbr">
            <div style="float: left;">Req Nbr:</div>
            <div style="text-align: center;">{{ $item->supplier->vd_addr ? $item->supplier->vd_addr : '' }}</div>
            <div style="float: right;">Sinar Meadow International Indonesia<br>Kawasan Industri Pulogadung No 6<br>Blok III.5.16-18<br>Pulo Ayang I No.6, RW.9<br>Jakarta 13920</div>
        </div>

        <table class="content">
            <tr>
                <th>Req Nbr</th>
                <td>{{ $item->rqmNbr }}</td>
                <th>Supplier</th>
                <td>{{ $item->supplier->vd_addr }}</td>
            </tr>
            <tr>
                <th>Rqstn Date</th>
                <td>{{ $item->rqmReqDate }}</td>
                <th>Cost Center</th>
                <td>{{ $item->rqmCc }}</td>
            </tr>
            <tr>
                <th>Need Date</th>
                <td>{{ $item->rqmNeedDate }}</td>
                <th>Currency</th>
                <td>{{ $item->rqmCurr }}</td>
            </tr>
            <tr>
                <th>Due Date</th>
                <td>{{ $item->rqmDueDate }}</td>
                <th>Direct Matls</th>
                <td>{{ $item->rqmDirect }}</td>
            </tr>
            <tr>
                <th>Entered By</th>
                <td>{{ $item->enterby }}</td>
                <th>Requested By</th>
                <td>{{ $item->rqmRqbyUserid }}</td>
            </tr>
            <tr>
                <th>End User</th>
                <td>{{ $item->rqmEndUserid }}</td>
                <th>Aprvl Status</th>
                <td>{{ $item->rqmAprvStat }}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td colspan="3">{{ $item->rqmRmks }}</td>
            </tr>
        </table>

        <table class="content">
            <thead>
                <tr>
                    <th>Line</th>
                    <th>Site</th>
                    <th>Item Number</th>
                    <th>Supplier</th>
                    <th>Req Qty UM</th>
                    <th>Unit Cost</th>
                    <th>Disc%</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $extCostTotal = 0;
                    $maxExtCostTotal = 0;
                @endphp
                @foreach ($item->rqdDets as $detail)
                    @php
                        $extCost = $detail->rqdReqQty * $detail->rqdPurCost;
                        $maxExtCost = $detail->rqdReqQty * $detail->rqdPurCost;
                        $extCostTotal += $extCost;
                        $maxExtCostTotal += $maxExtCost;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->rqmSite }}</td>
                        <td>{{ $detail->rqdPart }}</td>
                        <td>{{ $detail->rqdVend }}</td>
                        <td>{{ $detail->rqdReqQty }} {{ $detail->rqdUm }}</td>
                        <td>{{ number_format($detail->rqdPurCost, 2, ',', '.') }}</td>
                        <td>0.00%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="remarks">
            @foreach ($item->rqdDets as $detail)
                @if (strlen($detail->rqdCmt) > 50)
                    <p>{{ substr($detail->rqdCmt, 0, 50) }}</p>
                    <p>{{ substr($detail->rqdCmt, 50) }}</p>
                @else
                    <p>{{ $detail->rqdCmt }}</p>
                @endif
            @endforeach
        </div>

        <table class="content">
            <tr>
                <th>Requisition Totals</th>
                <td>Ext Cost Total: {{ number_format($extCostTotal, 2, ',', '.') }} IDR</td>
                <td>Max Ext Cost Total: {{ number_format($maxExtCostTotal, 2, ',', '.') }} IDR</td>
            </tr>
        </table>
    </div>
</body>
</html>
