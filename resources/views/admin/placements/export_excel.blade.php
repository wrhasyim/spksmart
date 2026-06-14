<table>
    <thead>
        <tr>
            <th><b>Nama Siswa</b></th>
            <th><b>NISN</b></th>
            <th><b>Gender</b></th>
            <th><b>Jurusan</b></th>
            <th><b>Industri Mitra</b></th>
            <th><b>Gelombang Lowongan</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach($placements as $p)
            @if($p->student)
                <tr>
                    <td>{{ $p->student->name }}</td>
                    <td>'{{ $p->student->nisn }}</td>
                    <td>{{ $p->student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $p->student->major->name }} ({{ $p->student->major->code }})</td>
                    <td>{{ $p->company->name ?? '-' }}</td>
                    <td>{{ $p->companySlot->batch_name ?? '-' }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>