<?php
session_start();
require_once __DIR__ . '/../app/database.php';
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}

$query = "
SELECT 
    t.id_transaksi,
    t.tgl_transaksi,
    t.total_harga,
    p.id_pelanggan,
    p.nama_pelanggan,
    p.alamat,
    p.no_hp,
    u.id_users,
    u.username,
    u.email,
    u.role
FROM transaksi t
JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
JOIN users u ON t.id_users = u.id_users
";

// Siapkan statement dan eksekusi query
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset dasar */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: sans-serif;
      background-color: #f4f6f9;
      line-height: 1.6;
    }
    /* Sidebar */
    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: #1a202c; /* Warna gelap */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: fixed;
      left: 0;
      top: 0;
    }
    .sidebar .header {
      padding: 1rem;
      text-align: center;
      border-bottom: 1px solid #2d3748;
    }
    .sidebar .header h2 {
      color: rgb(142, 123, 37);
      font-size: 1.5rem;
      font-weight: bold;
    }
    .sidebar nav {
      margin-top: 1rem;
    }
    .sidebar nav a {
      display: block;
      padding: 1rem;
      color: #cbd5e0; /* Warna teks */
      text-decoration: none;
      transition: background 0.3s, color 0.3s;
    }
    .sidebar nav a:hover {
      background-color: rgb(142, 123, 37);
      color: #fff;
    }
    .sidebar nav a.logout:hover {
      background-color: #e53e3e;
      color: #fff;
    }
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: rgb(142, 123, 37);
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="header">
      <h2>Dashboard</h2>
    </div>
    <nav>
    <?php if ($user['role'] === 'admin') : ?>
        <a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <?php else : ?>
        <a href="petugas_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <?php endif; ?>
      
      <a href="barang.php"><i class="fa-solid fa-box"></i> Barang</a>
      <a href="pelanggan.php"><i class="fa-solid fa-truck-field"></i> Pelanggan</a>

      <?php if ($user['role'] === 'admin') : ?>
      <a href="transaksi.php"><i class="fa-solid fa-cart-shopping"></i> Transaksi</a>
      <?php endif; ?>    
      
      <a href="laporan.php"><i class="fa-regular fa-file"></i> Laporan</a>

      <?php if ($user['role'] === 'admin') : ?>
      <a href="register.php"><i class="fa-solid fa-user"></i> User</a>
      <?php endif; ?>

      <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    </div>

    <div class="content">
        <h1>Laporan Transaksi</h1>
        <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Total Harga</th>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>ID User</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_transaksi']) ?></td>
                    <td><?= htmlspecialchars($row['tgl_transaksi']) ?></td>
                    <td><?= htmlspecialchars(number_format($row['total_harga'], 2, ',', '.')) ?></td>
                    <td><?= htmlspecialchars($row['id_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= htmlspecialchars($row['no_hp']) ?></td>
                    <td><?= htmlspecialchars($row['id_users']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>