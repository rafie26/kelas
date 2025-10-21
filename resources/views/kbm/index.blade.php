<!DOCTYPE html>
<html>

<head>
    <title>Jadwal KBM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        @if(session('admin_role') === 'admin')
            <h2 class="mb-4">Jadwal Kegiatan Belajar Mengajar (KBM) - Semua Guru</h2>
        @elseif(session('admin_role') === 'guru' && isset($guru))
            <h2 class="mb-4">Jadwal Mengajar Saya</h2>
            <div class="alert alert-info">
                <strong>Guru:</strong> {{ $guru->nama }} | <strong>Mata Pelajaran:</strong> {{ $guru->mapel }}
            </div>
        @elseif(session('admin_role') === 'siswa' && isset($kelasData))
            <h2 class="mb-4">Jadwal Pelajaran Kelas Saya</h2>
            <div class="alert alert-info">
                <strong>Siswa:</strong> {{ $siswaData->nama }} | 
                <strong>Kelas:</strong> {{ $kelasData->namakelas }} ({{ $kelasData->jenjang }}) | 
                <strong>Wali Kelas:</strong> {{ $kelasData->guru->nama }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="mb-3">
            <label for="filter-hari" class="form-label"><strong>Filter Hari:</strong></label>
            <select id="filter-hari" class="form-select" style="width: 200px;">
                <option value="">Semua Hari</option>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
                <option value="Sabtu">Sabtu</option>
            </select>
        </div>
        
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle" id="tabel-jadwal">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            @if(session('admin_role') === 'admin')
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                            @elseif(session('admin_role') === 'siswa')
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                            @endif
                            @if(session('admin_role') === 'guru')
                                <th>Kelas</th>
                            @endif
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            @if(session('admin_role') === 'admin')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function(){
        const role = '{{ session("admin_role") }}';
        
        function renderTable(data) {
            let rows = '';
            let colspan = 5;
            
            if (role === 'admin') {
                colspan = 8;
            }
            
            if (data.length === 0) {
                let message = 'Belum ada jadwal pelajaran';
                if (role === 'guru') {
                    message = 'Anda belum memiliki jadwal mengajar';
                } else if (role === 'siswa') {
                    message = 'Belum ada jadwal pelajaran untuk kelas Anda';
                }
                rows = `<tr><td colspan="${colspan}" class="text-center text-muted">${message}</td></tr>`;
            } else {
                data.forEach((jadwal, index) => {
                    rows += '<tr>';
                    rows += `<td class="text-center">${index + 1}</td>`;
                    
                    if (role === 'admin') {
                        rows += `<td>${jadwal.guru.nama}</td>`;
                        rows += `<td>${jadwal.guru.mapel}</td>`;
                        rows += `<td>${jadwal.walas.namakelas}</td>`;
                    } else if (role === 'siswa') {
                        rows += `<td>${jadwal.guru.nama}</td>`;
                        rows += `<td>${jadwal.guru.mapel}</td>`;
                    }
                    
                    if (role === 'guru') {
                        rows += `<td>${jadwal.walas.namakelas}</td>`;
                    }
                    
                    rows += `<td>${jadwal.hari}</td>`;
                    rows += `<td>${jadwal.mulai}</td>`;
                    rows += `<td>${jadwal.selesai}</td>`;
                    
                    if (role === 'admin') {
                        rows += `<td class="text-center">`;
                        rows += `<a href="/kbm/kelas/${jadwal.idwalas}" class="btn btn-sm btn-info">Lihat Kelas</a>`;
                        rows += `</td>`;
                    }
                    
                    rows += '</tr>';
                });
            }
            
            $('#tabel-jadwal tbody').html(rows);
        }
        
        function loadJadwal(hari = '') {
            $.ajax({
                url: "{{ route('kbm.data') }}",
                method: "GET",
                data: { hari: hari },
                success: function(response) {
                    renderTable(response);
                },
                error: function(xhr) {
                    let message = 'Gagal memuat data jadwal.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        message = xhr.responseJSON.error;
                    }
                    alert(message);
                }
            });
        }
        
        $('#filter-hari').on('change', function() {
            const hari = $(this).val();
            loadJadwal(hari);
        });
        
        loadJadwal();
    });
    </script>
</body>

</html>
