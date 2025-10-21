<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Halo, {{ session('admin_role') }} {{ session('admin_username') }}</h2>
    <a href="{{ route('logout') }}">Logout</a>
    @if(session('admin_role') === 'admin' || session('admin_role') === 'guru' || session('admin_role') === 'siswa')
        | <a href="{{ route('kbm.index') }}">Lihat Jadwal KBM</a>
    @endif
    <br><br>

    {{-- ================= GURU ================= --}}
    @if (session('admin_role') === 'guru' && isset($guru))
        <h3>Data Guru</h3>
        <p><b>Nama :</b> {{ $guru->nama }}</p>
        <p><b>Mapel :</b> {{ $guru->mapel }}</p>

        {{-- Jika guru ini walas --}}
        @if($guru->walas)
            <h4>Wali Kelas: {{ $guru->walas->namakelas }} ({{ $guru->walas->jenjang }})</h4>
            <h5>Daftar Siswa:</h5>
            <table border="1" cellpadding="8">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tinggi Badan</th>
                        <th>Berat Badan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guru->walas->kelas as $i => $kelas)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $kelas->siswa->nama }}</td>
                            <td>{{ $kelas->siswa->tb }}</td>
                            <td>{{ $kelas->siswa->bb }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <br>
    @endif

    {{-- ================= SISWA ================= --}}
    @if (session('admin_role') === 'siswa' && isset($siswaLogin))
        <h3>Data Siswa</h3>
        <p><b>Nama :</b> {{ $siswaLogin->nama }}</p>
        <p><b>BB :</b> {{ $siswaLogin->bb }}</p>
        <p><b>TB :</b> {{ $siswaLogin->tb }}</p>

        {{-- Jika siswa punya kelas --}}
        @if($siswaLogin->kelas)
            <p><b>Kelas :</b> {{ $siswaLogin->kelas->walas->namakelas }} ({{ $siswaLogin->kelas->walas->jenjang }})</p>
            <p><b>Wali Kelas :</b> {{ $siswaLogin->kelas->walas->guru->nama }}</p>
        @endif
        <br>
    @endif

    {{-- ================= TABEL SISWA (HANYA ADMIN) ================= --}}
    @if (session('admin_role') === 'admin')
        <h2>Daftar Siswa</h2>
        <a href="{{ route('siswa.create') }}">
            <button>+ Tambah Siswa</button>
        </a>
        <br><br>

        <p><label>Cari Siswa: </label><input type="text" id="search" placeholder="Ketik nama..."></p>

        <table border="1" cellpadding="8" id="tabel-siswa">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tinggi Badan</th>
                    <th>Berat Badan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <script>
        $(document).ready(function(){
            function renderTable(data) {
                let rows = '';
                if (data.length === 0) {
                    rows = '<tr><td colspan="5">Tidak ada data ditemukan</td></tr>';
                } else {
                    data.forEach((s, index) => {
                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${s.nama}</td>
                                <td>${s.tb}</td>
                                <td>${s.bb}</td>
                                @if (session('admin_role') === 'admin')
                                <td>
                                    <a href="/siswa/${s.idsiswa}/edit">Edit</a> |
                                    <a href="/siswa/${s.idsiswa}/delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                                @endif
                            </tr>
                        `;
                    });
                }
                $('#tabel-siswa tbody').html(rows);
            }

            function loadSiswa() {
                $.ajax({
                    url: "{{ route('siswa.data') }}",
                    method: "GET",
                    success: function(response) {
                        renderTable(response);
                    },
                    error: function() {
                        alert('Gagal memuat data siswa.');
                    }
                });
            }

            function searchSiswa(keyword) {
                $.ajax({
                    url: "{{ route('siswa.search') }}",
                    method: "GET",
                    data: { q: keyword },
                    success: function(response) {
                        renderTable(response);
                    },
                    error: function() {
                        console.error('Gagal mencari data siswa.');
                    }
                });
            }

            $('#search').on('keyup', function() {
                const keyword = $(this).val().trim();
                if (keyword.length > 0) {
                    searchSiswa(keyword);
                } else {
                    loadSiswa();
                }
            });

            loadSiswa();
        });
        </script>
    @endif
</body>
</html>