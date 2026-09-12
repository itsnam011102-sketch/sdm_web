<table class="table table-bordered align-middle" id="tabel-it">
    <thead class="table-light">
        <tr>
            <th>KPI (IT)</th>
            <th>Aturan (Polaritas)</th>
            <th>Target</th>
            <th>Bobot (%)</th>
            <th>Realisasi</th>
            <th>Capaian (%)</th>
            <th style="width:50px;"></th>
        </tr>
    </thead>
    <!-- Tag <tbody> yang benar membungkus semua baris <tr> -->
    <tbody id="tbody-it">
        <!-- Baris pertama (default) -->
        <tr>
            <td><input type="text" name="kpi[]" class="form-control form-control-sm" value="Jumlah System Error / Bug"></td>
            <td>
                <select name="polaritas[]" class="form-select form-select-sm" onchange="hitungSemuaIT()">
                    <option value="min" selected>MIN (Makin kecil makin bagus)</option>
                    <option value="max">MAX (Makin besar makin bagus)</option>
                </select>
            </td>
            <td><input type="number" name="target[]" class="form-control form-control-sm kpi-target" value="2" oninput="hitungSemuaIT()"></td>
            <td><input type="number" name="bobot[]" class="form-control form-control-sm kpi-bobot" value="100" oninput="hitungSemuaIT()"></td>
            <td><input type="number" name="realisasi[]" class="form-control form-control-sm kpi-realisasi" placeholder="0" oninput="hitungSemuaIT()"></td>
            <td><input type="text" name="capaian[]" class="form-control form-control-sm bg-light text-end fw-bold kpi-capaian" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBaris(this)" title="Hapus baris">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    </tbody> <!-- Tag penutup </tbody> dipindah ke sini -->
</table>

<!-- Tombol Tambah Baris -->
<div class="mb-3">
    <button type="button" class="btn btn-outline-primary btn-sm" onclick="tambahBaris()">
        <i class="bi bi-plus-circle me-1"></i> Tambah Baris KPI
    </button>
</div>

<script>
// Jalankan perhitungan otomatis begitu halaman selesai di-load
document.addEventListener('DOMContentLoaded', function () {
    hitungSemuaIT();
});

// Tambah baris baru ke tabel
function tambahBaris() {
    const tbody = document.getElementById('tbody-it');
    if (!tbody) {
        console.error("Elemen tbody-it tidak ditemukan!");
        return;
    }

    const row = tbody.insertRow();
    row.innerHTML = 
        '<td><input type="text" name="kpi[]" class="form-control form-control-sm" placeholder="Nama KPI..."></td>' +
        '<td>' +
            '<select name="polaritas[]" class="form-select form-select-sm" onchange="hitungSemuaIT()">' +
                '<option value="min">MIN (Makin kecil makin bagus)</option>' +
                '<option value="max" selected>MAX (Makin besar makin bagus)</option>' +
            '</select>' +
        '</td>' +
        '<td><input type="number" name="target[]" class="form-control form-control-sm kpi-target" placeholder="0" oninput="hitungSemuaIT()"></td>' +
        '<td><input type="number" name="bobot[]" class="form-control form-control-sm kpi-bobot" placeholder="0" oninput="hitungSemuaIT()"></td>' +
        '<td><input type="number" name="realisasi[]" class="form-control form-control-sm kpi-realisasi" placeholder="0" oninput="hitungSemuaIT()"></td>' +
        '<td><input type="text" name="capaian[]" class="form-control form-control-sm bg-light text-end fw-bold kpi-capaian" readonly></td>' +
        '<td class="text-center">' +
            '<button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBaris(this)" title="Hapus baris">' +
                '<i class="bi bi-trash"></i>' +
            '</button>' +
        '</td>';
        
    hitungSemuaIT();
}

// Hapus baris
function hapusBaris(tombol) {
    const tbody = document.getElementById('tbody-it');
    if (!tbody) return;

    if (tbody.rows.length <= 1) {
        alert('Minimal harus ada 1 baris KPI.');
        return;
    }
    tombol.closest('tr').remove();
    hitungSemuaIT();
}

// Hitung semua baris & total skor
function hitungSemuaIT() {
    const tbody = document.getElementById('tbody-it');
    if (!tbody) return;

    const rows = tbody.querySelectorAll('tr');
    let totalSkor = 0;

    rows.forEach(row => {
        const polSelect = row.querySelector('select[name="polaritas[]"]');
        const target    = parseFloat(row.querySelector('.kpi-target')?.value)    || 0;
        const bobot     = parseFloat(row.querySelector('.kpi-bobot')?.value)     || 0;
        const realisasi = parseFloat(row.querySelector('.kpi-realisasi')?.value) || 0;
        const capaianEl = row.querySelector('.kpi-capaian');

        if (!capaianEl) return;

        let capaian = 0;
        const pol = polSelect ? polSelect.value : 'max';

        if (pol === 'min') {
            if (realisasi === 0 || realisasi <= target) {
                capaian = 100;
            } else {
                capaian = (target / realisasi) * 100;
            }
        } else {
            if (target === 0) {
                capaian = 0;
            } else {
                capaian = (realisasi / target) * 100;
            }
        }

        capaian = Math.min(capaian, 100);
        capaianEl.value = capaian.toFixed(2) + '%';
        totalSkor += (capaian * bobot) / 100;
    });

    const elSkor = document.getElementById('text-total-skor');
    if (elSkor) elSkor.innerText = totalSkor.toFixed(2) + '%';

    const elBadge = document.getElementById('badge-status');
    if (elBadge) {
        if (totalSkor >= 100)      { elBadge.className = 'badge bg-success fs-6';  elBadge.innerText = 'Sangat Baik'; }
        else if (totalSkor >= 85)  { elBadge.className = 'badge bg-primary fs-6';  elBadge.innerText = 'Baik'; }
        else if (totalSkor >= 70)  { elBadge.className = 'badge bg-warning fs-6 text-dark'; elBadge.innerText = 'Cukup'; }
        else                       { elBadge.className = 'badge bg-danger fs-6';   elBadge.innerText = 'Kurang'; }
    }
}
</script>