@component('mail::message')
# Kepada Yth.
**{{ $requestData['to_department_name'] }} Department**

Dengan hormat,

Kami dari departemen **{{ $requestData['from_department_name'] }}** bermaksud untuk mengajukan permohonan peminjaman
dana kepada department **{{ $requestData['to_department_name'] }}** dengan rincian sebagai berikut:

@component('mail::table')
| Keterangan            | Nilai                                     |
|-----------------------|-------------------------------------------|
| Department pengaju:   | {{ $requestData['from_department_name']}} |
| Purchase No       :   | {{ $requestData['budget_purchase_no']}}   |
| Jumlah            :   | {{ $requestData['amount'] }}              |
| Alasan            :   | {{ $requestData['reason'] }}              |
@endcomponent

Dana tersebut akan digunakan sepenuhnya untuk keperluan divisi kami dan akan dipertanggungjawabkan sesuai dengan
ketentuan yang berlaku. Kami juga berkomitmen untuk tidak menyalahgunakan dana yang dipinjamkan.

Demikian permohonan ini kami sampaikan. Atas perhatian dan kerjasama Bapak/Ibu, kami ucapkan terima kasih.

Hormat kami,
**{{ $requestData['from_department_name'] }} Department**

@component('mail::button', ['url' => url($approveLink)])
✅ Setuju
@endcomponent

@component('mail::button', ['url' => url($rejectLink)])
❌ Reject
@endcomponent
@endcomponent