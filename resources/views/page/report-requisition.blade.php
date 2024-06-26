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
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100">
    <div class="max-w-7xl mx-auto p-4">
        <div class="bg-white dark:bg-gray-900 text-black dark:text-white rounded-lg shadow overflow-hidden printable"
            id="printableArea">
            <!-- Header -->
            <div
                class="grid grid-cols-3 items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <div class="col-span-1 flex items-center">
                    <img src="https://via.placeholder.com/64" alt="QAD Logo" class="w-16 h-16" />
                </div>
                <div class="col-span-1 text-center">
                    <p class="text-lg font-bold">Requisition Report</p>
                    <p class="text-lg font-bold">SMII</p>
                </div>
                <div class="col-span-1 text-sm font-medium text-right">
                    <p>{{ $item->rqmReqDate }}</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-4 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                    <div>
                        <p class="text-sm font-medium">Req Nbr: {{ $item->rqmNbr }}</p>
                    </div>
                    <div>
                        @if($item->supplier)
                            <p class="text-sm font-medium">Supplier: {{ $item->supplier->vd_addr}}</p>
                            <p class="text-sm font-medium">{{ $item->supplier->ad_name}}</p>
                            <p class="text-sm font-medium">{{ $item->supplier->ad_line1}}</p>
                            <p class="text-sm font-medium">{{ $item->supplier->ad_line2}}</p>
                            <p class="text-sm font-medium">{{ $item->supplier->ad_city}}</p>
                            <p class="text-sm font-medium">INDONESIA</p>
                        @else
                            <p class="text-sm font-medium">Supplier: -</p>
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-medium">Rqstn Date: {{ $item->rqmReqDate }}</p>
                        <p class="text-sm font-medium">Need Date: {{ $item->rqmNeedDate }}</p>
                        <p class="text-sm font-medium">Due Date: {{ $item->rqmDueDate }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Entered By: {{ $item->enterby }}</p>
                        <p class="text-sm font-medium">Requested By: {{ $item->rqmRqbyUserid }}</p>
                        <p class="text-sm font-medium">End User: {{ $item->rqmEndUserid }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Cost Center: {{ $item->rqmCc }}</p>
                        <p class="text-sm font-medium">Currency: {{ $item->rqmCurr }}</p>
                        <p class="text-sm font-medium">Direct Matls: {{ $item->rqmDirect }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Aprvl Status: {{ $item->rqmAprvStat }}</p>
                        <p class="text-sm font-medium">Remarks: {{ $item->rqmRmks }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-sm font-medium">
                        <p>Disc Pct: 0.00%</p>
                    </div>
                    <div class="text-sm font-medium">
                        <p>Discount Table: 0.00%</p>
                    </div>
                </div>

                <table class="w-full text-sm font-medium">
                    <thead>
                        <tr>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Line</th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Site</th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Item Number
                            </th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Supplier
                            </th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Req Qty UM
                            </th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Unit Cost
                            </th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">Disc%</th>
                        </tr>
                    </thead>
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
                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                {{ $loop->iteration }}</td>
                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                {{ $item->rqmSite }}</td>
                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                {{ $detail->rqdPart }}</td>
                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                {{ $detail->rqdVend }}</td>
                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                {{ $detail->rqdReqQty }} {{ $detail->rqdUm }} </td>
                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
                                {{ number_format($detail->rqdPurCost, 2, ',', '.') }}</td>
                            <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">0.00%
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                @if (strlen($detail->rqdCmt) > 50)
                                    <p class="text-xs">{{ substr($detail->rqdCmt, 0, 50) }}</p>
                                    <p class="text-xs">{{ substr($detail->rqdCmt, 50) }}</p>
                                @else
                                    <p class="text-xs">{{ $detail->rqdCmt }}</p>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-sm font-medium">
                        <p>Requisition Totals</p>
                        <p>Ext Cost Total: {{ number_format($extCostTotal, 2, ',', '.') }} IDR</p>
                        <p>Max Ext Cost Total: {{ number_format($maxExtCostTotal, 2, ',', '.') }} IDR</p>
                    </div>
                    <div class="text-sm font-medium">
                        <p>{{ number_format($maxExtCostTotal, 2, ',', '.') }} IDR</p>
                        <p>{{ number_format($maxExtCostTotal, 2, ',', '.') }} IDR</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                window.print();
            }, 1000); // Menunggu 1 detik setelah DOMContentLoaded
        });
    </script>
</body>

</html>

