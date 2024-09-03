<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requisition Report</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <style>
        @media print {
            @page {
                margin: 0;
                size: A4;
            }

            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #ffffff;
                color: #000000;
            }

            .page-break {
                page-break-after: always;
            }
        }

        .bg-gray-100 {
            background-color: #f3f4f6;
        }

        .text-gray-900 {
            color: #1f2937;
        }

        .dark\:bg-gray-800 {
            background-color: #1f2937;
        }

        .dark\:text-gray-100 {
            color: #ffffff;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100">
    @php
        $page = 1;
    @endphp
    @foreach ($rqmreports as $report)
        @foreach ($report->rqdDets->chunk(17) as $chunk)
            <div class="max-w-7xl mx-auto p-4 page">
                <div class="bg-white dark:bg-gray-900 text-black dark:text-white rounded-lg shadow overflow-hidden printable" id="printableArea">
                    <!-- Header -->
                    <div class="grid grid-cols-3 items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="col-span-1 flex items-center">
                            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="QAD Logo" class="w-25 h-16 ml-10" />
                        </div>
                        <div class="col-span-1 text-center">
                            <p class="text-lg font-bold">Requisition Report</p>
                            <p class="text-lg font-bold">SMII</p>
                        </div>
                        <div class="col-span-1 text-sm font-medium text-right mr-10">
                            <p>{{ now()->format('Y-m-d H:i:s') }}</p>
                            <p class="mt-5 font-bold">Page: {{ $page }}</p>
                        </div>
                    </div>

                    <!-- Body -->
                    @if ($loop->first)
                        <div class="p-4 space-y-4 text-sm">
                            <div class="grid grid-cols-3 gap-4 mb-2">
                                <div>
                                    <p class="text-sm font-medium">Req Nbr: {{ $report->rqmNbr }}</p>
                                </div>
                                <div>
                                    @if ($report->supplier)
                                        <p class="text-sm font-medium">Supplier: {{ $report->supplier->vd_addr }}</p>
                                        <p class="text-sm font-medium">{{ $report->supplier->ad_name }}</p>
                                        <p class="text-sm font-medium">{{ $report->supplier->ad_line1 }}</p>
                                        <p class="text-sm font-medium">{{ $report->supplier->ad_line2 }}</p>
                                        <p class="text-sm font-medium">{{ $report->supplier->ad_city }}</p>
                                        <p class="text-sm font-medium">INDONESIA</p>
                                    @else
                                        <p class="text-sm font-medium">Supplier: </p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Sinar Meadow International Indonesia</p>
                                    <p class="text-sm font-medium">Kawasan Industri Pulogadung No 6</p>
                                    <p class="text-sm font-medium">Blok III.5.16-18</p>
                                    <p class="text-sm font-medium">Pulo Ayang I No.6, RW.9</p>
                                    <p class="text-sm font-medium">Jakarta 13920</p>
                                    <p class="text-sm font-medium">INDONESIA</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm font-medium">Rqstn Date: {{ \Carbon\Carbon::parse($report->rqmReqDate)->format('d/m/Y') }}</p>
                                    <p class="text-sm font-medium">Need Date: {{ \Carbon\Carbon::parse($report->rqmNeedDate)->format('d/m/Y') }}</p>
                                    <p class="text-sm font-medium">Due Date: {{ \Carbon\Carbon::parse($report->rqmDueDate)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Entered By: {{ $report->enterby }}</p>
                                    <p class="text-sm font-medium">End User: {{ $report->rqmEndUserid }}</p>
                                    <p class="text-sm font-medium">PR non PO: {{ $report->rqm__log01 }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Cost Center: {{ $report->rqmCc }}</p>
                                    <p class="text-sm font-medium">Currency: {{ $report->rqmCurr }}</p>
                                    <p class="text-sm font-medium">Direct Matls: {{ $report->rqmDirect }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">Aprvl Status: {{ $report->rqmAprvStat }}</p>
                                    <p class="text-sm font-medium">Remarks: {{ $report->rqmRmks }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <table class="w-full text-sm font-medium">
                        <thead>
                            <tr>
                                <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Line</th>
                                <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Site</th>
                                <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left">Part Number</th>
                                <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Supplier</th>
                                <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">Req Qty</th>
                                <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left">UM</th>
                                <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">Unit Cost</th>
                                <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">Disc%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $extCostTotal = 0;
                                $maxExtCostTotal = 0;
                            @endphp
                            @foreach ($chunk as $detail)
                                @php
                                    $extCost = $detail->rqdReqQty * $detail->rqdPurCost;
                                    $maxExtCost = $detail->rqdReqQty * $detail->rqdPurCost;
                                    $extCostTotal += $extCost;
                                    $maxExtCostTotal += $maxExtCost;
                                @endphp
                                <tr>
                                    <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">{{ $detail->rqdLine }}</td>
                                    <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">{{ $report->rqmSite }}</td>
                                    <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left">{{ $detail->rqdPart }}</td>
                                    <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">{{ $detail->supplier ? $detail->supplier->ad_name : '' }}</td>
                                    <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">{{ number_format(floatval($detail->rqdReqQty), 0, ',', '.') }} </td>
                                    <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-left">{{ $detail->rqdUm ? $detail->rqdUm : '' }} </td>
                                    <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">{{ number_format(floatval($detail->rqdPurCost), 0, ',', '.') }}</td>
                                    <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-right">{{ $detail->rqdDiscPct ? number_format(floatval($detail->rqdDiscPct), 2, ',', '.') : '0.00' }}%</td>
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        @if (strlen($detail->rqdCmt) > 120)
                                        @php
                                            $comment = $detail->rqdCmt;
                                            while (strlen($comment) > 120) {
                                                echo '<pre class="text-sm text-left">' .
                                                    substr($comment, 0, 120) .
                                                    '</pre>';
                                                $comment = substr($comment, 120);
                                            }
                                        @endphp
                                        <pre class="text-sm text-left">{{ $comment }}</pre>
                                    @else
                                        <pre class="text-sm text-left">{{ $detail->rqdCmt }}</pre>
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    @if ($loop->last)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-sm font-medium">
                            <p>Requisition Totals</p>
                            <p>Ext Cost Total:</p>
                            <p>Max Ext Cost Total:</p>
                        </div>
                        <div class="text-sm font-medium mt-5 flex justify-end" style="padding-right: 5rem;">
                           <div class="text-right">
                            <p>{{ number_format($extCostTotal, 2, ',', '.') }} {{ $report->rqmCurr }}</p>
                            <p>{{ number_format($maxExtCostTotal, 2, ',', '.') }} {{ $report->rqmCurr }}</p>
                           </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @php
                $page++;
            @endphp
            <div class="page-break"></div>
        @endforeach
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.print();
            }, 1000); // Menunggu 1 detik setelah DOMContentLoaded
        });
    </script>
</body>

</html>
