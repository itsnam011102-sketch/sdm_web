<!-- Top Navbar Bagian Atas -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container-fluid px-4">
    <!-- Brand Title Form -->

    <!-- Menu Navbar Atas -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTopMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTopMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
        <li class="nav-item">
          <a href="form_divisi/halaman_renbis/renbis.php" class="nav-link active">DIREKTUR</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white-50" href="#">DEVISI</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white-50" href="#">KANTOR WILAYAH</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white-50" href="#">KANTOR CABANG</a>
        </li>
      </ul>

      <!-- Tombol Kembali Kanan -->
      <a href="kpi.php" class="btn btn-outline-light btn-sm fw-bold">← Kembali</a>
    </div>
  </div>
</nav>

<!-- ================= PERSPEKTIF KEUANGAN ================= -->
<h3 class="fw-bold text-dark mt-4 mb-2">PERSPEKTIF KEUANGAN BOBOT </h3> 
  <th>
  <input type="text" name="total_bobot_keuangan" class="form-control form-control-sm text-end" placeholder="%" onblur style="max-width: 100px; margin-left: left;"="formatPersen(this)" onfocus="unformatPersen(this)">
</th>
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
        <th input = style="width: 100px;">BOBOT (%)</th>
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
          <select name="polaritas[]" class="form-select">
            <option value="Max">Max</option>
            <option value="Min">Min</option>
          </select>
        </td>
        <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0"></td>
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
<h2 style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; font-size: 12px;">
  Bobot keseluruhan Perspektif Pelanggan
</h2> 
  <th>
  <input type="text" name="total_bobot_keuangan" class="form-control form-control-sm text-end" placeholder="%" onblur style="max-width: 100px; margin-left: left;"="formatPersen(this)" onfocus="unformatPersen(this)">
</th>
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
          <select name="polaritas[]" class="form-select">
            <option value="Max">Max</option>
            <option value="Min">Min</option>
          </select>
        </td>
        <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0"></td>
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
<h2 style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; font-size: 12px;">
  Bobot keseluruhan Perspektif Internal
</h2> 
  <th>
  <input type="text" name="total_bobot_keuangan" class="form-control form-control-sm text-end" placeholder="%" onblur style="max-width: 100px; margin-left: left;"="formatPersen(this)" onfocus="unformatPersen(this)">
</th>
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
          <select name="polaritas[]" class="form-select">
            <option value="Max">Max</option>
            <option value="Min">Min</option>
          </select>
        </td>
        <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0"></td>
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
<h2 style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; font-size: 12px;">
  Bobot keseluruhan Perspektif Pembelajaran & Pertumbuhan
</h2> 
  <th>
  <input type="text" name="total_bobot_keuangan" class="form-control form-control-sm text-end" placeholder="%" onblur style="max-width: 100px; margin-left: left;"="formatPersen(this)" onfocus="unformatPersen(this)">
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
          <select name="polaritas[]" class="form-select">
            <option value="Max">Max</option>
            <option value="Min">Min</option>
          </select>
        </td>
        <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0"></td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm" onclick="hapusBarisDirops(this)">✕</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<button type="button" class="btn btn-secondary btn-sm mb-4" onclick="tambahBarisDirops('body-pertumbuhan')">+ Tambah Baris</button>


<script>
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
      <select name="polaritas[]" class="form-select">
        <option value="Max">Max</option>
        <option value="Min">Min</option>
      </select>
    </td>
    <td><input type="number" name="bobot[]" class="form-control text-end" step="0.01" placeholder="0"></td>
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
  }
}
</script>