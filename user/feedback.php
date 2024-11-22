<?php
session_start();
require_once '../config/db.php';

// Cek apakah pengguna login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Ambil ID pengguna
$user_id = $_SESSION['user_id'];

// Proses pengiriman feedback oleh buyer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $query = "INSERT INTO feedback (user_id, message, created_at) VALUES ('$user_id', '$message', NOW())";
    if (mysqli_query($conn, $query)) {
        $feedback_success = "Feedback berhasil dikirim.";
        echo "<script>alert('Feedback berhasil dikirim.');</script>";
    } else {
        $feedback_error = "Terjadi kesalahan saat mengirim feedback.";
        echo "<script>alert('Terjadi kesalahan saat mengirim feedback.');</script>";
    }
}

// Proses pembalasan feedback oleh buyer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['response_id']) && isset($_POST['reply_message'])) {
    $response_id = mysqli_real_escape_string($conn, $_POST['response_id']);
    $reply_message = mysqli_real_escape_string($conn, $_POST['reply_message']);
    $query = "INSERT INTO feedback_reply (feedback_response_id, user_id, message, created_at) 
              VALUES ('$response_id', '$user_id', '$reply_message', NOW())";
    if (mysqli_query($conn, $query)) {
        $reply_success = "Balasan berhasil dikirim.";
        echo "<script>alert('Balasan berhasil dikirim.');</script>";
    } else {
        $reply_error = "Terjadi kesalahan saat mengirim balasan.";
        echo "<script>alert('Terjadi kesalahan saat mengirim balasan.');</script>";
    }
}

// Ambil feedback yang sudah ada dari buyer
$feedback_query = "SELECT 
                        feedback.id AS feedback_id,
                        feedback.message AS feedback_message,
                        feedback.created_at AS feedback_date,
                        feedback_response.message AS admin_response,
                        feedback_response.created_at AS admin_response_date,
                        feedback_reply.message AS buyer_reply,
                        feedback_reply.created_at AS buyer_reply_date,
                        feedback_admin_reply.message AS admin_reply_to_buyer,
                        feedback_admin_reply.created_at AS admin_reply_to_buyer_date
                   FROM feedback
                   LEFT JOIN feedback_response ON feedback.id = feedback_response.feedback_id
                   LEFT JOIN feedback_reply ON feedback_response.id = feedback_reply.feedback_response_id
                   LEFT JOIN feedback_admin_reply ON feedback_reply.id = feedback_admin_reply.feedback_reply_id
                   WHERE feedback.user_id = '$user_id'
                   ORDER BY feedback.created_at DESC";
$feedbacks = mysqli_query($conn, $feedback_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Feedback Pembeli</title>
    <link rel="stylesheet" href="../assets/css/feedback.css">
</head>
<body>
    <header>
        <h1>Feedback Pembeli</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="orders.php">Daftar Pesanan</a>
            <a href="feedback.php">Feedback</a>
            <a href="profile.php">Profil</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <!-- Feedback Success/Error Messages -->
        <?php if (isset($feedback_success)) : ?>
            <div class="alert success"><?= $feedback_success; ?></div>
        <?php elseif (isset($feedback_error)) : ?>
            <div class="alert error"><?= $feedback_error; ?></div>
        <?php endif; ?>

        <!-- Balasan Success/Error Messages -->
        <?php if (isset($reply_success)) : ?>
            <div class="alert success"><?= $reply_success; ?></div>
        <?php elseif (isset($reply_error)) : ?>
            <div class="alert error"><?= $reply_error; ?></div>
        <?php endif; ?>

        <!-- Form untuk Mengirimkan Feedback -->
        <h2>Kirim Feedback Baru</h2>
        <form method="POST">
            <textarea name="message" placeholder="Tuliskan feedback Anda..." required></textarea>
            <button type="submit">Kirim Feedback</button>
        </form>

        <!-- Tabel Riwayat Feedback -->
        <h2>Riwayat Feedback Anda</h2>
        <table>
            <thead>
                <tr>
                    <th>Pesan Feedback</th>
                    <th>Tanggal Feedback</th>
                    <th>Balasan Admin</th>
                    <th>Balasan Anda</th>
                    <th>Balasan Admin terhadap Anda</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($feedback = mysqli_fetch_assoc($feedbacks)) : ?>
                    <tr>
                        <!-- Pesan Feedback -->
                        <td><?= htmlspecialchars($feedback['feedback_message']) ?></td>
                        <td><?= date('F j, Y g:i A', strtotime($feedback['feedback_date'])) ?></td>

                        <!-- Balasan Admin -->
                        <td>
                            <?php if ($feedback['admin_response']) : ?>
                                <?= htmlspecialchars($feedback['admin_response']) ?><br>
                                <small><?= date('F j, Y g:i A', strtotime($feedback['admin_response_date'])) ?></small>
                            <?php else : ?>
                                <em>Belum ada balasan admin</em>
                            <?php endif; ?>
                        </td>

                        <!-- Balasan Anda -->
                        <td>
                            <?php if ($feedback['buyer_reply']) : ?>
                                <?= htmlspecialchars($feedback['buyer_reply']) ?><br>
                                <small><?= date('F j, Y g:i A', strtotime($feedback['buyer_reply_date'])) ?></small>
                            <?php elseif ($feedback['admin_response']) : ?>
                                <form method="POST">
                                    <input type="hidden" name="response_id" value="<?= $feedback['feedback_id'] ?>">
                                    <textarea name="reply_message" placeholder="Balas admin..." required></textarea>
                                    <button type="submit">Kirim Balasan</button>
                                </form>
                            <?php else : ?>
                                <em>Tunggu balasan admin</em>
                            <?php endif; ?>
                        </td>

                        <!-- Balasan Admin terhadap Anda -->
                        <td>
                            <?php if ($feedback['admin_reply_to_buyer']) : ?>
                                <?= htmlspecialchars($feedback['admin_reply_to_buyer']) ?><br>
                                <small><?= date('F j, Y g:i A', strtotime($feedback['admin_reply_to_buyer_date'])) ?></small>
                            <?php else : ?>
                                <em>Belum ada balasan</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
