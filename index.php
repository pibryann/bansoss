<?php
require_once __DIR__ . '/includes/init.php';
$judul = 'Ringkasan';
$halaman = 'dashboard';

$total      = (int)$pdo->query('SELECT COUNT(*) FROM penerima')->fetchColumn();
$layak      = (int)$pdo->query("SELECT COUNT(*) FROM penerima WHERE status_kelayakan='layak'")->fetchColumn();
$tersalur   = (int)$pdo->query("SELECT COUNT(*) FROM penyaluran WHERE status='tersalurkan'")->fetchColumn();
$dana       = (int)$pdo->query("SELECT COALESCE(SUM(p.nilai),0) FROM penyaluran s JOIN program_bantuan p ON p.id=s.program_id WHERE s.status='tersalurkan'")->fetchColumn();

$sebaran = $pdo->query('SELECT status_kelayakan, COUNT(*) jml FROM penerima GROUP BY status_kelayakan')->fetchAll(PDO::FETCH_KEY_PAIR);

$program = $pdo->query("SELECT p.nama, p.kuota, COUNT(s.id) terpakai
    FROM program_bantuan p LEFT JOIN penyaluran s ON s.program_id=p.id AND s.status<>'dibatalkan'
    GROUP BY p.id ORDER BY p.nama")->fetchAll();

$terbaru = $pdo->query("SELECT s.tanggal, s.status, n.nama AS penerima, p.nama AS program
    FROM penyaluran s JOIN penerima n ON n.id=s.penerima_id JOIN program_bantuan p ON p.id=s.program_id
    ORDER BY s.tanggal DESC, s.id DESC LIMIT 6")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="angka-grid">
  <div class="angka"><b><?= $total ?></b><span>Penerima terdata</span></div>
  <div class="angka"><b><?= $layak ?></b><span>Berstatus layak</span></div>
  <div class="angka"><b><?= $tersalur ?></b><span>Bantuan tersalurkan</span></div>
  <div class="angka"><b style="font-size:1.45rem"><?= rupiah($dana) ?></b><span>Nilai tersalurkan</span></div>
</div>

<div class="dua-kolom">
  <div class="kartu">
    <h2>Sebaran kelayakan</h2>
    <?php foreach (['layak', 'dipertimbangkan', 'tidak_layak'] as $s):
        $n = (int)($sebaran[$s] ?? 0); $persen = $total ? round($n / $total * 100) : 0; ?>
      <div class="batang">
        <div class="batang-judul"><span><?= label_status($s) ?></span><span><?= $n ?> orang</span></div>
        <div class="batang-jalur"><div class="batang-isi" style="width:<?= $persen ?>%"></div></div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="kartu">
    <h2>Pemakaian kuota program</h2>
    <?php foreach ($program as $p):
        $persen = $p['kuota'] ? min(100, round($p['terpakai'] / $p['kuota'] * 100)) : 0; ?>
      <div class="batang">
        <div class="batang-judul"><span><?= e($p['nama']) ?></span><span><?= (int)$p['terpakai'] ?> / <?= (int)$p['kuota'] ?></span></div>
        <div class="batang-jalur"><div class="batang-isi" style="width:<?= $persen ?>%"></div></div>
      </div>
    <?php endforeach; ?>
    <?php if (!$program): ?><p class="bantu">Belum ada program bantuan.</p><?php endif; ?>
  </div>
</div>

<h2>Penyaluran terbaru</h2>
<div class="tabel">
  <table>
    <tr><th>Tanggal</th><th>Penerima</th><th>Program</th><th>Status</th></tr>
    <?php foreach ($terbaru as $r): ?>
      <tr>
        <td><?= tanggal($r['tanggal']) ?></td>
        <td><?= e($r['penerima']) ?></td>
        <td><?= e($r['program']) ?></td>
        <td><span class="lencana <?= e($r['status']) ?>"><?= label_status($r['status']) ?></span></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <?php if (!$terbaru): ?><div class="kosong">Belum ada penyaluran. Mulai dari menu Penyaluran.</div><?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
