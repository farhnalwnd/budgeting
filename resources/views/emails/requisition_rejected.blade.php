<!DOCTYPE html>
<html>
<head>
    <title>Requisition Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #d9534f;
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            background: #f9f9f9;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Requisition Rejected</h1>
        <p>Dear {{ $dataEmail['enterby'] }},</p>
        <p>Your requisition with the following details has been <strong style="color: red;">REJECTED</strong>:</p>
        <ul>
            <li><strong>Requisition Number:</strong> {{ $dataEmail['rqmNbr'] }}</li>
            @if (!empty($dataEmail['rqmVend']))
                <li><strong>Vendor:</strong> {{ $dataEmail['rqmVend'] }}</li>
            @endif
            <li><strong>Request Date:</strong> {{ \Carbon\Carbon::parse($dataEmail['rqmReqDate'])->format('d/m/Y') }}</li>
            <li><strong>Need Date:</strong> {{ \Carbon\Carbon::parse($dataEmail['rqmNeedDate'])->format('d/m/Y') }}</li>
            <li><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($dataEmail['rqmDueDate'])->format('d/m/Y') }}</li>
            @if (!empty($dataEmail['rqmRmks']))
                <li><strong>Remarks:</strong> {{ $dataEmail['rqmRmks'] }}</li>
            @endif
            @if (!empty($dataEmail['rqmReason']))
                <li><strong>Reason:</strong> {{ $dataEmail['rqmReason'] }}</li>
            @endif
        </ul>
        <p>Please contact the approver for more details.</p>
        <p>Thank you.</p>
    </div>
</body>
</html>
