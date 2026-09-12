<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>KPI (SDM)</th>
            <th>Target Pelatihan</th>
            <th>Bobot (%)</th>
            <th>Realisasi Pelatihan</th>
            <th>Capaian (%)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="text" name="kpi[]" class="form-control form-control-sm" value="Pelaksanaan Training Karyawan" readonly></td>
            <td><input type="number" id="target_sdm" name="target[]" class="form-control form-control-sm" value="12" oninput="hitungSDM()"></td>
            <td><input type="number" id="bobot_sdm" name="bobot[]" class="form-control form-control-sm" value="100" oninput="hitungSDM()"></td>
            <td><input type="number" id="realisasi_sdm" name="realisasi[]" class="form-control form-control-sm" placeholder="0" oninput="hitungSDM()"></td>
            <td><input type="text" id="capaian_sdm" name="capaian[]" class="form-control form-control-sm bg-light text-end fw-bold" readonly></td>
        </tr>
    </tbody>
</table>

<script>
function hitungSDM() {
    let target = parseFloat(document.getElementById('target_sdm').value) || 0;
    let realisasi = parseFloat(document.getElementById('realisasi_sdm').value) || 0;
    let bobot = parseFloat(document.getElementById('bobot_sdm').value) || 0;
    
    let hasil = target > 0 ? (realisasi / target) * 100 : 0;
    document.getElementById('capaian_sdm').value = hasil.toFixed(2) + "%";
    document.getElementById('text-total-skor').innerText = ((hasil * bobot) / 100).toFixed(2) + "%";
}
</script>