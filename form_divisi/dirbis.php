<!-- ================= PERSPEKTIF KEUANGAN ================= -->
<h3 class="fw-bold text-dark mt-4 mb-2">PERSPEKTIF KEUANGAN</h3>
<div class="table-responsive mb-2">
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-primary text-center">
      <tr>
        <th style="width: 50px;">NO</th>
        <th>SASARAN STRATEGIS</th>
        <th>PROGRAM / INISIATIF</th>
        <th>KEY PERFORMANCE INDIKATOR (KPI)</th>
        <th>FORMULA</th>
        <th style="width: 100px;">SATUAN</th>
        <th style="width: 110px;">POLARITAS</th>
        <th style="width: 100px;">BOBOT (%)</th>
        <th style="width: 120px;">TARGET</th>
        <th style="width: 120px;">REALISASI</th>
        <th style="width: 120px;">CAPAIAN (%)</th>
        <th style="width: 60px;">AKSI</th>
      </tr>
    </thead>
    <tbody id="body-keuangan">
      <tr>
        <td class="text-center row-no">1</td>
        <td><input type="text" name="sasaran_strategis[]" class="form-control" placeholder="Contoh: Pendapatan & Penerimaan"></td>
        <td><input type="text" name="program_inisiatif[]" class="form-control" placeholder="Contoh: Pendapatan premi"></td>
        <td><input type="text" name="kpi[]" class="form-control" placeholder="Contoh: Pencapaian target premi baru Netto"></td>
        <td><input type="text" name="formula[]" class="form-control" placeholder="Contoh: Realisasi / anggaran"></td>
        <td>
          <select name="satuan[]" class="form-select">
            <option value="Rp">Rp</option>
            <option value="%">%</option>
            <option value="Jumlah">Jumlah</option>
          </select>
        </td>
        <td>
          <select name="polaritas[]" class="form-select" onchange="hitungCapaian(this.closest('tr'))">
            <option value="Max">Max</option>
            <option value="Min">Min</option>
          </select>
        </td>
        <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="number" name="target[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="number" name="realisasi[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="text" name="capaian[]" class="form-control text-end" readonly placeholder="0.00%"></td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm" onclick="hapusBarisDirops(this)">✕</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<button type="button" class="btn btn-secondary btn-sm mb-4" onclick="tambahBarisDirops('body-keuangan')">+ Tambah Baris</button>


<!-- ================= PERSPEKTIF PELANGGAN ================= -->
<h3 class="fw-bold text-dark mt-4 mb-2">PERSPEKTIF PELANGGAN</h3>
<div class="table-responsive mb-2">
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-primary text-center">
      <tr>
        <th style="width: 50px;">NO</th>
        <th>SASARAN STRATEGIS</th>
        <th>PROGRAM / INISIATIF</th>
        <th>KEY PERFORMANCE INDIKATOR (KPI)</th>
        <th>FORMULA</th>
        <th style="width: 100px;">SATUAN</th>
        <th style="width: 110px;">POLARITAS</th>
        <th style="width: 100px;">BOBOT (%)</th>
        <th style="width: 120px;">TARGET</th>
        <th style="width: 120px;">REALISASI</th>
        <th style="width: 120px;">CAPAIAN (%)</th>
        <th style="width: 60px;">AKSI</th>
      </tr>
    </thead>
    <tbody id="body-pelanggan">
      <tr>
        <td class="text-center row-no">1</td>
        <td><input type="text" name="sasaran_strategis[]" class="form-control" placeholder="Contoh: Kepuasan Pelanggan"></td>
        <td><input type="text" name="program_inisiatif[]" class="form-control" placeholder="Contoh: Survei Layanan"></td>
        <td><input type="text" name="kpi[]" class="form-control" placeholder="Contoh: Indeks Kepuasan"></td>
        <td><input type="text" name="formula[]" class="form-control" placeholder="Contoh: Skor / Total"></td>
        <td>
          <select name="satuan[]" class="form-select">
            <option value="Rp">Rp</option>
            <option value="%">%</option>
            <option value="Jumlah">Jumlah</option>
          </select>
        </td>
        <td>
          <select name="polaritas[]" class="form-select" onchange="hitungCapaian(this.closest('tr'))">
            <option value="Max">Max</option>
            <option value="Min">Min</option>
          </select>
        </td>
        <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="number" name="target[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="number" name="realisasi[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="text" name="capaian[]" class="form-control text-end" readonly placeholder="0.00%"></td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm" onclick="hapusBarisDirops(this)">✕</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<button type="button" class="btn btn-secondary btn-sm mb-4" onclick="tambahBarisDirops('body-pelanggan')">+ Tambah Baris</button>


<!-- ================= PERSPEKTIF PROSES BISNIS INTERNAL ================= -->
<h3 class="fw-bold text-dark mt-4 mb-2">PERSPEKTIF PROSES BISNIS INTERNAL</h3>
<div class="table-responsive mb-2">
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-primary text-center">
      <tr>
        <th style="width: 50px;">NO</th>
        <th>SASARAN STRATEGIS</th>
        <th>PROGRAM / INISIATIF</th>
        <th>KEY PERFORMANCE INDIKATOR (KPI)</th>
        <th>FORMULA</th>
        <th style="width: 100px;">SATUAN</th>
        <th style="width: 110px;">POLARITAS</th>
        <th style="width: 100px;">BOBOT (%)</th>
        <th style="width: 120px;">TARGET</th>
        <th style="width: 120px;">REALISASI</th>
        <th style="width: 120px;">CAPAIAN (%)</th>
        <th style="width: 60px;">AKSI</th>
      </tr>
    </thead>
    <tbody id="body-bisnis">
      <tr>
        <td class="text-center row-no">1</td>
        <td><input type="text" name="sasaran_strategis[]" class="form-control" placeholder="Contoh: Efisiensi Proses"></td>
        <td><input type="text" name="program_inisiatif[]" class="form-control" placeholder="Contoh: Digitalisasi Dokumen"></td>
        <td><input type="text" name="kpi[]" class="form-control" placeholder="Contoh: Waktu Penyelesaian"></td>
        <td><input type="text" name="formula[]" class="form-control" placeholder="Contoh: Standar waktu"></td>
        <td>
          <select name="satuan[]" class="form-select">
            <option value="Rp">Rp</option>
            <option value="%">%</option>
            <option value="Jumlah">Jumlah</option>
          </select>
        </td>
        <td>
          <select name="polaritas[]" class="form-select" onchange="hitungCapaian(this.closest('tr'))">
            <option value="Max">Max</option>
            <option value="Min">Min</option>
          </select>
        </td>
        <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="number" name="target[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="number" name="realisasi[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="text" name="capaian[]" class="form-control text-end" readonly placeholder="0.00%"></td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm" onclick="hapusBarisDirops(this)">✕</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<button type="button" class="btn btn-secondary btn-sm mb-4" onclick="tambahBarisDirops('body-bisnis')">+ Tambah Baris</button>


<!-- ================= PERSPEKTIF PEMBELAJARAN & PERTUMBUHAN ================= -->
<h3 class="fw-bold text-dark mt-4 mb-2">PERSPEKTIF PEMBELAJARAN & PERTUMBUHAN</h3>
<div class="table-responsive mb-2">
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-primary text-center">
      <tr>
        <th style="width: 50px;">NO</th>
        <th>SASARAN STRATEGIS</th>
        <th>PROGRAM / INISIATIF</th>
        <th>KEY PERFORMANCE INDIKATOR (KPI)</th>
        <th>FORMULA</th>
        <th style="width: 100px;">SATUAN</th>
        <th style="width: 110px;">POLARITAS</th>
        <th style="width: 100px;">BOBOT (%)</th>
        <th style="width: 120px;">TARGET</th>
        <th style="width: 120px;">REALISASI</th>
        <th style="width: 120px;">CAPAIAN (%)</th>
        <th style="width: 60px;">AKSI</th>
      </tr>
    </thead>
    <tbody id="body-pertumbuhan">
      <tr>
        <td class="text-center row-no">1</td>
        <td><input type="text" name="sasaran_strategis[]" class="form-control" placeholder="Contoh: Pelatihan Karyawan"></td>
        <td><input type="text" name="program_inisiatif[]" class="form-control" placeholder="Contoh: Workshop Skill"></td>
        <td><input type="text" name="kpi[]" class="form-control" placeholder="Contoh: Jam Pelatihan"></td>
        <td><input type="text" name="formula[]" class="form-control" placeholder="Contoh: Total Jam"></td>
        <td>
          <select name="satuan[]" class="form-select">
            <option value="Rp">Rp</option>
            <option value="%">%</option>
            <option value="Jumlah">Jumlah</option>
          </select>
        </td>
        <td>
          <select name="polaritas[]" class="form-select" onchange="hitungCapaian(this.closest('tr'))">
            <option value="Max">Max</option>
            <option value="Min">Min</option>
          </select>
        </td>
        <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="number" name="target[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="number" name="realisasi[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
        <td><input type="text" name="capaian[]" class="form-control text-end" readonly placeholder="0.00%"></td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm" onclick="hapusBarisDirops(this)">✕</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<button type="button" class="btn btn-secondary btn-sm mb-4" onclick="tambahBarisDirops('body-pertumbuhan')">+ Tambah Baris</button>


<script>
// Fungsi Hitung Capaian Per Baris
function hitungCapaian(row) {
  if (!row) return;

  const polaritas = row.querySelector('select[name="polaritas[]"]').value;
  const target = parseFloat(row.querySelector('input[name="target[]"]').value) || 0;
  const realisasi = parseFloat(row.querySelector('input[name="realisasi[]"]').value) || 0;
  const inputCapaian = row.querySelector('input[name="capaian[]"]');

  if (target === 0) {
    inputCapaian.value = '0.00%';
    if (typeof updateTotalSkorGlobal === 'function') updateTotalSkorGlobal();
    return;
  }

  let capaian = 0;
  if (polaritas === 'Max') {
    capaian = (realisasi / target) * 100;
  } else if (polaritas === 'Min') {
    capaian = (target / realisasi) * 100;
  }

  inputCapaian.value = capaian.toFixed(2) + '%';
  if (typeof updateTotalSkorGlobal === 'function') updateTotalSkorGlobal();
}

// Fungsi Tambah Baris Dinamis Per Perspektif Tabel
function tambahBarisDirops(bodyId) {
  const tbody = document.getElementById(bodyId);
  if (!tbody) return;

  const no = tbody.children.length + 1;
  const row = document.createElement('tr');

  row.innerHTML = `
    <td class="text-center row-no">${no}</td>
    <td><input type="text" name="sasaran_strategis[]" class="form-control" placeholder="Contoh: Sasaran Baru"></td>
    <td><input type="text" name="program_inisiatif[]" class="form-control" placeholder="Contoh: Program Baru"></td>
    <td><input type="text" name="kpi[]" class="form-control" placeholder="Contoh: KPI Baru"></td>
    <td><input type="text" name="formula[]" class="form-control" placeholder="Contoh: Formula"></td>
    <td>
      <select name="satuan[]" class="form-select">
        <option value="Rp">Rp</option>
        <option value="%">%</option>
        <option value="Jumlah">Jumlah</option>
      </select>
    </td>
    <td>
      <select name="polaritas[]" class="form-select" onchange="hitungCapaian(this.closest('tr'))">
        <option value="Max">Max</option>
        <option value="Min">Min</option>
      </select>
    </td>
    <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
    <td><input type="number" name="target[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
    <td><input type="number" name="realisasi[]" class="form-control text-end" placeholder="0" oninput="hitungCapaian(this.closest('tr'))"></td>
    <td><input type="text" name="capaian[]" class="form-control text-end" readonly placeholder="0.00%"></td>
    <td class="text-center">
      <button type="button" class="btn btn-danger btn-sm" onclick="hapusBarisDirops(this)">✕</button>
    </td>
  `;

  tbody.appendChild(row);
}

// Fungsi Hapus Baris dan Re-Index Nomor Urut
function hapusBarisDirops(btn) {
  const row = btn.closest('tr');
  if (row) {
    const tbody = row.closest('tbody');
    row.remove();
    
    if (tbody) {
      const rows = tbody.querySelectorAll('tr');
      rows.forEach((tr, index) => {
        const noCell = tr.querySelector('.row-no') || tr.cells[0];
        if (noCell) noCell.innerText = index + 1;
      });
    }
    if (typeof updateTotalSkorGlobal === 'function') updateTotalSkorGlobal();
  }
}
</script>