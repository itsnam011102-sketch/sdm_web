<!-- Tabel khusus Divisi ASKUM -->
<table class="table table-bordered align-middle" id="tabel-askum">
    <thead class="table-light">
        <tr>
            <th>KPI (Asuransi Komersial)</th>
            <th style="width: 120px;">Target Polis</th>
            <th style="width: 110px;">Bobot (%)</th>
            <th style="width: 120px;">Realisasi Polis</th>
            <th style="width: 120px;">Capaian (%)</th>
            <th style="width: 50px;" class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody id="tbody-askum">
        <!-- Baris Default ASKUM -->
        <tr>
            <td><input type="text" name="kpi[]" class="form-control form-control-sm" value="Jumlah Nasabah Baru" required></td>
            <td><input type="number" name="target[]" class="form-control form-control-sm kpi-target" value="50" oninput="hitungSemuaAskum()"></td>
            <td><input type="number" name="bobot[]" class="form-control form-control-sm kpi-bobot" value="100" oninput="hitungSemuaAskum()"></td>
            <td><input type="number" name="realisasi[]" class="form-control form-control-sm kpi-realisasi" value="0" oninput="hitungSemuaAskum()"></td>
            <td><input type="text" name="capaian[]" class="form-control form-control-sm bg-light text-end fw-bold kpi-capaian" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBarisAskum(this)" title="Hapus baris">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    </tbody>
</table>

<!-- Tombol Tambah Baris KPI -->
<div class="mb-3">
    <button type="button" class="btn btn-outline-primary btn-sm" onclick="tambahBarisAskum()">
        <i class="bi bi-plus-circle me-1"></i> Tambah Baris KPI
    </button>
</div>

<!-- JAVASCRIPT KHUSUS DIVISI ASKUM -->
<script>
// Fungsi Tambah Baris di Bawah
function tambahBarisAskum() {
    const tbody = document.getElementById('tbody-askum');
    if (!tbody) return;

    const row = tbody.insertRow();
    row.innerHTML = `
        <td><input type="text" name="kpi[]" class="form-control form-control-sm" placeholder="Nama KPI Askum..." required></td>
        <td><input type="number" name="target[]" class="form-control form-control-sm kpi-target" value="0" oninput="hitungSemuaAskum()"></td>
        <td><input type="number" name="bobot[]" class="form-control form-control-sm kpi-bobot" value="0" oninput="hitungSemuaAskum()"></td>
        <td><input type="number" name="realisasi[]" class="form-control form-control-sm kpi-realisasi" value="0" oninput="hitungSemuaAskum()"></td>
        <td><input type="text" name="capaian[]" class="form-control form-control-sm bg-light text-end fw-bold kpi-capaian" readonly></td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBarisAskum(this)" title="Hapus baris">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    hitungSemuaAskum();
}

// Fungsi Hapus Baris
function hapusBarisAskum(tombol) {
    const tbody = document.getElementById('tbody-askum');
    if (!tbody) return;

    if (tbody.rows.length <= 1) {
        alert('Minimal harus ada 1 baris KPI.');
        return;
    }
    
    tombol.closest('tr').remove();
    hitungSemuaAskum();
}

// Fungsi Hitung Capaian Askum
function hitungSemuaAskum() {
    const tbody = document.getElementById('tbody-askum');
    if (!tbody) return;

    const rows = tbody.querySelectorAll('tr');
    let totalSkor = 0;

    rows.forEach(row => {
        const target    = parseFloat(row.querySelector('.kpi-target')?.value)    || 0;
        const bobot     = parseFloat(row.querySelector('.kpi-bobot')?.value)     || 0;
        const realisasi = parseFloat(row.querySelector('.kpi-realisasi')?.value) || 0;
        const capaianEl = row.querySelector('.kpi-capaian');

        if (!capaianEl) return;

        let capaian = 0;
        if (target > 0) {
            capaian = (realisasi / target) * 100;
        }

        capaian = Math.min(capaian, 100);
        capaianEl.value = capaian.toFixed(2) + '%';
        totalSkor += (capaian * bobot) / 100;
    });

    // Update total skor di kpi.php
    const elSkor = document.getElementById('text-total-skor');
    if (elSkor) elSkor.innerText = totalSkor.toFixed(2) + '%';
}

// Jalankan perhitungan awal
hitungSemuaAskum();
</script>