<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>KPI (Asuransi Personal)</th>
            <th>Target Polis</th>
            <th>Bobot (%)</th>
            <th>Realisasi Polis</th>
            <th>Capaian (%)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="text" name="kpi[]" class="form-control form-control-sm" value="Jumlah Nasabah Baru" readonly></td>
            <td><input type="number" id="target_asper" name="target[]" class="form-control form-control-sm" value="50" oninput="hitungAsper()"></td>
            <td><input type="number" id="bobot_asper" name="bobot[]" class="form-control form-control-sm" value="100" oninput="hitungAsper()"></td>
            <td><input type="number" id="realisasi_asper" name="realisasi[]" class="form-control form-control-sm" placeholder="0" oninput="hitungAsper()"></td>
            <td><input type="text" id="capaian_asper" name="capaian[]" class="form-control form-control-sm bg-light text-end fw-bold" readonly></td>
        </tr>
    </tbody>
</table>

<script>
function hitungAsper() {
    let target = parseFloat(document.getElementById('target_asper').value) || 0;
    let realisasi = parseFloat(document.getElementById('realisasi_asper').value) || 0;
    let bobot = parseFloat(document.getElementById('bobot_asper').value) || 0;
    
    let hasil = target > 0 ? (realisasi / target) * 100 : 0;
    document.getElementById('capaian_asper').value = hasil.toFixed(2) + "%";
    document.getElementById('text-total-skor').innerText = ((hasil * bobot) / 100).toFixed(2) + "%";
}
</script>