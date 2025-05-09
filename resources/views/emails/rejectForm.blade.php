<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Alasan Penolakan</h2>
<form method="POST" action="{{ route('budgeting.request.reject.feedback') }}">
    @csrf
    <input type="hidden" name="budget_req_no" value="{{ $budget_req_no }}">
    <input type="hidden" name="nik" value="{{ $nik }}">

    <textarea name="feedback" rows="5" placeholder="Tuliskan alasan penolakan..." required></textarea>
    <br>
    <button type="submit">Kirim</button>
</form>

</body>
</html>