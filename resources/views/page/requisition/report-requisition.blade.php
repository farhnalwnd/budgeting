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
                size: auto;
                /* Gunakan ukuran kertas default */
            }

            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                /* Ganti dengan font yang Anda inginkan */
                background-color: #ffffff;
                /* Warna latar belakang untuk cetak */
                color: #000000;
                /* Warna teks untuk cetak */
            }

            .printable {
                width: 100%;
                margin: 0;
                padding: 0;
                page-break-inside: avoid;
                /* Hindari pemotongan elemen di tengah halaman */
            }

            /* Penyesuaian untuk menghapus margin antar bagian */
            .section {
                margin-top: 0;
                margin-bottom: 0;
                padding-top: 0;
                padding-bottom: 0;
            }

            /* Styling tambahan untuk header dan footer jika diperlukan */
            .header {
                /* Gaya header cetak */
            }

            .footer {
                /* Gaya footer cetak */
            }
        }

        /* Gaya untuk tampilan layar normal */
        .bg-gray-100 {
            background-color: #f3f4f6;
        }

        .text-gray-900 {
            color: #1f2937;
        }

        .dark:bg-gray-800 {
            background-color: #1f2937;
        }

        .dark:text-gray-100 {
            color: #ffffff;
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
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="QAD Logo" class="w-25 h-16 ml-10" />
                </div>
                <div class="col-span-1 text-center">
                    <p class="text-lg font-bold">Requisition Report</p>
                    <p class="text-lg font-bold">SMII</p>
                </div>
                <div class="col-span-1 text-sm font-medium text-right mr-10">
                    <p>{{ now()->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-4 space-y-4 text-sm">
                <div class="grid grid-cols-3 md:grid-cols-3 gap-4 mb-2">
                    <div>
                        <p class="text-sm font-medium">Req Nbr: {{ $item->rqmNbr }}</p>
                    </div>
                    <div>
                        @if ($item->supplier)
                            <p class="text-sm font-medium">Supplier: {{ $item->supplier->vd_addr }}</p>
                            <p class="text-sm font-medium">{{ $item->supplier->ad_name }}</p>
                            <p class="text-sm font-medium">{{ $item->supplier->ad_line1 }}</p>
                            <p class="text-sm font-medium">{{ $item->supplier->ad_line2 }}</p>
                            <p class="text-sm font-medium">{{ $item->supplier->ad_city }}</p>
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

                <div class="grid grid-cols-3 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-medium">Rqstn Date:
                            {{ \Carbon\Carbon::parse($item->rqmReqDate)->format('d/m/Y') }}</p>
                        <p class="text-sm font-medium">Need Date:
                            {{ \Carbon\Carbon::parse($item->rqmNeedDate)->format('d/m/Y') }}</p>
                        <p class="text-sm font-medium">Due Date:
                            {{ \Carbon\Carbon::parse($item->rqmDueDate)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Entered By: {{ $item->enterby }}</p>
                        <p class="text-sm font-medium">End User: {{ $item->rqmEndUserid }}</p>
                        <p class="text-sm font-medium">PR non PO: {{ $item->rqm__log01 }}</p>
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

                <div class="grid grid-cols-3 md:grid-cols-3 gap-4">
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
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Line</th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Site</th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Item Number
                            </th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Supplier
                            </th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Req Qty UM
                            </th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Unit Cost
                            </th>
                            <th class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">Disc%</th>
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
                                <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                    {{ $detail->rqdLine }}</td>
                                <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                    {{ $item->rqmSite }}</td>
                                <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                    {{ $detail->rqdPart }}</td>
                                <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                    {{ $detail->supplier ? $detail->supplier->ad_name : '' }}</td>
                                <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                    {{ number_format(floatval($detail->rqdReqQty), 0, ',', '.') }}
                                    {{ $detail->rqdUm ? $detail->rqdUm : '' }}</td>
                                <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">
                                    {{ number_format(floatval($detail->rqdPurCost), 2, ',', '.') }}</td>
                                <td class="border-b border-gray-200 dark:border-gray-600 px-2 py-1 text-center">0.00%
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="border-b border-gray-200 dark:border-gray-600 px-2 py-1">
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

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-sm font-medium">
                        <p>Requisition Totals</p>
                        <p>Ext Cost Total:</p>
                        <p>Max Ext Cost Total:</p>
                    </div>
                    <div class="text-sm font-medium mt-5 flex justify-end" style="padding-right: 5rem;">
                        <div class="text-right">
                            <p>{{ number_format($extCostTotal, 2, ',', '.') }} {{ $item->rqmCurr }}</p>
                            <p>{{ number_format($maxExtCostTotal, 2, ',', '.') }} {{ $item->rqmCurr }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.print();
            }, 1000); // Menunggu 1 detik setelah DOMContentLoaded
        });
    </script>
</body>

</html>
