<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Report</title>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="6"
        style="font-family: Arial, sans-serif; color: #000000; background-color: #ffffff; border: 6px solid black;">
        <tr style="border: 2px solid black;">
            <td align="center">
                <table width="600" cellpadding="20" cellspacing="0" style="background-color: #ffffff;">
                    <tr>
                        <td align="center">
                            <p style="font-size: 24px; font-weight: bold; margin: 0;">New Requisition</p>
                            <p style="font-size: 18px; font-weight: bold; margin: 10px 0 0 0;">SMII</p>
                        </td>
                    </tr>
                </table>

                <hr style="border: 2px solid black; width: 600; margin: 20px 0;">

                <table width="600" cellpadding="10" cellspacing="0" style="background-color: #ffffff;">
                    <tr>
                        <td style="font-size: 18px; font-weight: bold;">Req Nbr: {{ $rqmNbr }}</td>
                    </tr>
                </table>

                <table width="600" cellpadding="10" cellspacing="0" style="background-color: #ffffff;">
                    <tr>
                        <td width="33%" style="vertical-align: top;">
                            <p style="font-size: 14px; font-weight: bold;">Rqstn Date:
                                {{ \Carbon\Carbon::parse($rqmReqDate)->format('d/m/Y') }}</p>
                            <p style="font-size: 14px; font-weight: bold;">Need Date:
                                {{ \Carbon\Carbon::parse($rqmNeedDate)->format('d/m/Y') }}</p>
                            <p style="font-size: 14px; font-weight: bold;">Due Date:
                                {{ \Carbon\Carbon::parse($rqmDueDate)->format('d/m/Y') }}</p>
                        </td>
                        <td width="33%" style="vertical-align: top;">
                            <p style="font-size: 14px; font-weight: bold;">Entered By: {{ $enterby }}</p>
                            <p style="font-size: 14px; font-weight: bold;">End User: {{ $rqmEndUserid }}</p>
                            <p style="font-size: 14px; font-weight: bold;">PR non PO: {{ $rqm__log01 }}</p>
                        </td>
                        <td width="33%" style="vertical-align: top;">
                            <p style="font-size: 14px; font-weight: bold;">Cost Center: {{ $rqmCc }}</p>
                            <p style="font-size: 14px; font-weight: bold;">Currency: {{ $rqmCurr }}</p>
                            <p style="font-size: 14px; font-weight: bold;">Direct Matls: {{ $rqmDirect }}</p>
                        </td>
                    </tr>
                </table>
                <table width="600" cellpadding="10" cellspacing="0" style="background-color: #ffffff;">
                    <tr>
                        <td style="font-size: 14px; font-weight: bold;">Aprvl Status: {{ $rqmAprvStat }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px; font-weight: bold;">Remarks: {{ $rqmRmks }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px; font-weight: bold;">Reason: {{ $rqmReason }}</td>
                    </tr>
                </table>

                <table width="600" style="background-color: #ffffff;">
                    <thead>
                        <tr>
                            <th style="font-size: 14px; font-weight: bold; text-align: center;">Line</th>
                            <th style="font-size: 14px; font-weight: bold; text-align: center;">Site</th>
                            <th style="font-size: 14px; font-weight: bold; text-align: left;">Item Number</th>
                            <th style="font-size: 14px; font-weight: bold; text-align: center;">Supplier</th>
                            <th style="font-size: 14px; font-weight: bold; text-align: c;">Req Qty </th>
                            <th style="font-size: 14px; font-weight: bold; text-align: left;">UM </th>
                            <th style="font-size: 14px; font-weight: bold; text-align: right;">Unit Cost</th>
                            <th style="font-size: 14px; font-weight: bold; text-align: right;">Disc%</th>
                            <th style="font-size: 14px; font-weight: bold; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $extCostTotal = 0;
                            $maxExtCostTotal = 0;
                        @endphp
                        @foreach ($rqdDets as $detail)
                            @php
                                $extCost = $detail['rqdReqQty'] * $detail['rqdPurCost'];
                                $maxExtCost = $detail['rqdReqQty'] * $detail['rqdPurCost'];
                                $extCostTotal += $extCost;
                                $maxExtCostTotal += $maxExtCost;
                            @endphp
                            <tr>
                                <td style="font-size: 14px; text-align: center;">{{ $detail['rqdLine'] }}</td>
                                <td style="font-size: 14px; text-align: center;">1000</td>
                                <td style="font-size: 14px; text-align: left;">{{ $detail['rqdPart'] }}</td>
                                <td style="font-size: 14px; text-align: center;">
                                    {{ $detail['rqdVend'] ? $detail['supplier']['ad_name'] : '' }}</td>
                                <td style="font-size: 14px; text-align: right;">
                                    {{ number_format(floatval($detail['rqdReqQty']), 0, ',', '.') }} </td>
                                <td style="font-size: 14px; text-align: left;">
                                    {{ $detail['rqdUm'] ? $detail['rqdUm'] : '' }}</td>
                                <td style="font-size: 14px; text-align: right;">
                                    {{ number_format(floatval($detail['rqdPurCost']), 2, ',', '.') }}</td>
                                <td style="font-size: 14px; text-align: right;">0.00%</td>
                                <td style="font-size: 14px; text-align: right;">
                                    {{ number_format(floatval($maxExtCost), 2, ',', '.') }}</td>
                            </tr>
                            @if ($detail['rqdCmt'])
                                <tr>
                                    <td colspan="8" style="font-size: 8px; text-align: left;">
                                        @if (strlen($detail['rqdCmt']) > 50)
                                            <p>{{ substr($detail['rqdCmt'], 0, 50) }}</p>
                                            <p>{{ substr($detail['rqdCmt'], 50) }}</p>
                                        @else
                                            <p>{{ $detail['rqdCmt'] }}</p>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <table width="600" cellpadding="10" cellspacing="0" style="background-color: #ffffff;">
                    <tr>
                        <td style="font-size: 14px;"><strong>Disc Pct:</strong> 0.00%</td>
                        <td style="font-size: 14px;"><strong>Discount Table:</strong> 0.00%</td>
                    </tr>
                </table>

                <table width="600" cellpadding="10" cellspacing="0" style="background-color: #ffffff;">
                    <tr>
                        <td style="font-size: 14px; font-weight: bold;">Requisition Totals</td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px;"><strong>Max Ext Cost Total:</strong>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ number_format($maxExtCostTotal, 2, ',', '.') }} {{ $rqmCurr }}</td>
                    </tr>
                </table>

                <table width="600" cellpadding="30" cellspacing="0" border="0"
                    style="background-color: #ffffff; text-align: center;">
                    <tr>
                        <td>
                            <a
                                href="{{ $approval_link }}"style="text-decoration: none; color: green; font-size: 30px; font-weight:bold">Approve</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ $decline_link }}"
                                style="text-decoration: none; color: red; font-size:30px; font-weight:bold">Reject</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
