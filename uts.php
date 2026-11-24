
<?php

// =====================================
// CLASS INDUK
// =====================================
class Keuangan {
    protected $nama;
    protected $saldo;

    public function __construct($nama, $saldo) {
        $this->nama = $nama;
        $this->saldo = $saldo;
    }

    public function getNama() {
        return $this->nama;
    }

    public function getSaldo() {
        return $this->saldo;
    }
}

// =====================================
// CLASS ANAK 1: PEMASUKAN
// =====================================
class Pemasukan extends Keuangan {

    public function tambahPemasukan($jumlah) {
        $this->saldo += $jumlah;
        return $this->saldo;
    }
}

// =====================================
// CLASS ANAK 2: PENGELUARAN
// =====================================
class Pengeluaran extends Keuangan {

    public function tambahPengeluaran($jumlah) {
        if ($jumlah > $this->saldo) {
            return false;
        }

        $this->saldo -= $jumlah;
        return $this->saldo;
    }
}

// =====================================
// DATA DEFAULT
// =====================================
$nama = "Muntazan";
$saldoAwal = 1000000;
$totalPemasukan = 500000;
$totalPengeluaran = 250000;
$error = "";

// =====================================
// MENGAMBIL DATA DARI FORM
// =====================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = trim($_POST["nama"]);
    $saldoAwal = (float) $_POST["saldo_awal"];
    $totalPemasukan = (float) $_POST["pemasukan"];
    $totalPengeluaran = (float) $_POST["pengeluaran"];

    if (
        $nama === "" ||
        $saldoAwal < 0 ||
        $totalPemasukan < 0 ||
        $totalPengeluaran < 0
    ) {
        $error = "Data tidak boleh kosong atau bernilai negatif!";
    } elseif ($totalPengeluaran > $saldoAwal + $totalPemasukan) {
        $error = "Pengeluaran melebihi total saldo yang tersedia!";
    }
}

// =====================================
// PROSES PERHITUNGAN
// =====================================
$pemasukan = new Pemasukan($nama, $saldoAwal);

$saldoSetelahPemasukan =
    $pemasukan->tambahPemasukan($totalPemasukan);

$pengeluaran = new Pengeluaran(
    $nama,
    $saldoSetelahPemasukan
);

$saldoAkhir = $pengeluaran->tambahPengeluaran(
    $totalPengeluaran
);

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Finance Dashboard</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: #f1f5f9;
    padding: 30px;
    color: #1e293b;
}

.container {
    max-width: 1000px;
    margin: auto;
}

.header {
    background: linear-gradient(135deg, #111827, #4338ca);
    padding: 35px;
    border-radius: 20px;
    color: white;
    margin-bottom: 25px;
}

.header h1 {
    font-size: 30px;
    margin-bottom: 10px;
}

.header p {
    color: #c7d2fe;
}

.section {
    background: white;
    padding: 25px;
    border-radius: 16px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px #00000008;
}

.section h2 {
    margin-bottom: 20px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    outline: none;
    font-size: 15px;
}

.form-group input:focus {
    border-color: #4f46e5;
}

button {
    margin-top: 20px;
    padding: 14px 25px;
    border: none;
    border-radius: 10px;
    background: #4338ca;
    color: white;
    font-weight: bold;
    cursor: pointer;
    font-size: 15px;
}

button:hover {
    background: #312e81;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 25px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 5px 20px #00000008;
}

.card h3 {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 15px;
}

.card h2 {
    font-size: 22px;
}

.green {
    color: #16a34a;
}

.red {
    color: #dc2626;
}

.blue {
    color: #4f46e5;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

th {
    color: #64748b;
}

.total {
    background: #eef2ff;
    font-weight: bold;
}

.error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.footer {
    text-align: center;
    color: #94a3b8;
    padding: 20px;
}

@media(max-width: 700px) {
    body {
        padding: 15px;
    }

    .cards, .form-grid {
        grid-template-columns: 1fr;
    }

    .section {
        overflow-x: auto;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>My Finance Dashboard</h1>
        <p>Selamat datang, <?= htmlspecialchars($nama); ?>!</p>
        <p>Kelola pemasukan dan pengeluaran keuangan Anda.</p>
    </div>

    <!-- FORM INPUT -->
    <div class="section">
        <h2>⚙️ Pengaturan Keuangan</h2>

        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input type="text" name="nama"
                    value="<?= htmlspecialchars($nama); ?>" required>
                </div>

                <div class="form-group">
                    <label>Saldo Awal (Rp)</label>
                    <input type="number" name="saldo_awal"
                    value="<?= htmlspecialchars((string)$saldoAwal); ?>"
                    min="0" required>
                </div>

                <div class="form-group">
                    <label>Total Pemasukan (Rp)</label>
                    <input type="number" name="pemasukan"
                    value="<?= htmlspecialchars((string)$totalPemasukan); ?>"
                    min="0" required>
                </div>

                <div class="form-group">
                    <label>Total Pengeluaran (Rp)</label>
                    <input type="number" name="pengeluaran"
                    value="<?= htmlspecialchars((string)$totalPengeluaran); ?>"
                    min="0" required>
                </div>

            </div>

            <button type="submit">Hitung Keuangan</button>

        </form>
    </div>

    <?php if (!$error): ?>

    <!-- KARTU SALDO -->
    <div class="cards">

        <div class="card">
            <h3>Saldo Awal</h3>
            <h2 class="blue">
                Rp <?= number_format($saldoAwal, 0, ',', '.'); ?>
            </h2>
        </div>

        <div class="card">
            <h3>Total Pemasukan</h3>
            <h2 class="green">
                + Rp <?= number_format($totalPemasukan, 0, ',', '.'); ?>
            </h2>
        </div>

        <div class="card">
            <h3>Total Pengeluaran</h3>
            <h2 class="red">
                - Rp <?= number_format($totalPengeluaran, 0, ',', '.'); ?>
            </h2>
        </div>

    </div>

    <!-- TABEL RINGKASAN -->
    <div class="section">
        <h2>📊 Ringkasan Keuangan</h2>

        <table>
            <tr>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>

            <tr>
                <td>Saldo Awal</td>
                <td>Rp <?= number_format($saldoAwal, 0, ',', '.'); ?></td>
            </tr>

            <tr>
                <td>Pemasukan</td>
                <td class="green">
                    + Rp <?= number_format($totalPemasukan, 0, ',', '.'); ?>
                </td>
            </tr>

            <tr>
                <td>Pengeluaran</td>
                <td class="red">
                    - Rp <?= number_format($totalPengeluaran, 0, ',', '.'); ?>
                </td>
            </tr>

            <tr class="total">
                <td>Saldo Akhir</td>
                <td class="blue">
                    Rp <?= number_format($saldoAkhir, 0, ',', '.'); ?>
                </td>
            </tr>
        </table>
    </div>

    <?php endif; ?>

    <div class="footer">
        © 2026 My Finance Dashboard | PHP OOP Inheritance
    </div>

</div>

</body>
</html>
