<?php
session_start();
require_once '../config/db.php';

// Cek apakah pengguna login dan apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses balasan admin terhadap feedback pembeli
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['feedback_id'], $_POST['response_message'])) {
        $stmt = $conn->prepare("INSERT INTO feedback_response (feedback_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param('iis', $_POST['feedback_id'], $user_id, $_POST['response_message']);
        if ($stmt->execute()) {
            echo "Balasan berhasil dikirim.";
        } else {
            echo "Terjadi kesalahan: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['reply_to_buyer'], $_POST['reply_message'])) {
        $stmt = $conn->prepare("INSERT INTO feedback_admin_reply (feedback_reply_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param('iis', $_POST['reply_to_buyer'], $user_id, $_POST['reply_message']);
        if ($stmt->execute()) {
            echo "Balasan terhadap balasan pembeli berhasil dikirim.";
        } else {
            echo "Terjadi kesalahan: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Query untuk feedback yang belum dibalas
$pending_feedback_query = "SELECT id, message, created_at FROM feedback WHERE id NOT IN (SELECT feedback_id FROM feedback_response) ORDER BY created_at DESC";
$pending_feedbacks = $conn->query($pending_feedback_query);

// Query untuk feedback yang sudah dibalas
$responded_feedback_query = "
    SELECT 
        f.id AS feedback_id, f.message AS feedback_message, f.created_at AS feedback_date,
        fr.id AS response_id, fr.message AS response_message, fr.created_at AS response_date,
        fr2.id AS reply_id, fr2.message AS reply_message, fr2.created_at AS reply_date,
        fr3.id AS admin_reply_id, fr3.message AS admin_reply_message, fr3.created_at AS admin_reply_date
    FROM feedback f
    LEFT JOIN feedback_response fr ON f.id = fr.feedback_id
    LEFT JOIN feedback_reply fr2 ON fr.id = fr2.feedback_response_id
    LEFT JOIN feedback_admin_reply fr3 ON fr2.id = fr3.feedback_reply_id
    ORDER BY f.created_at DESC";
$responded_feedbacks = $conn->query($responded_feedback_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD - Feedback</title>
    <link rel="stylesheet" href="../assets/css/feedback.css">
</head>
<body>
    <header>
        <h1>ADMIN DASHBOARD - Feedback</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="products.php">Daftar Produk</a>
            <a href="orders.php">Daftar Pesanan</a>
            <a href="feedback.php">Daftar Feedback</a>
            <a href="users.php">Daftar User</a>
            <a href="profile.php">Profil</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Feedback yang Sudah Dibalas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Pesan Pembeli</th>
                        <th>Balasan Admin</th>
                        <th>Balasan Pembeli</th>
                        <th>Balasan Admin ke Pembeli</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $responded_feedbacks->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['feedback_message']) ?><br>
                                <small><?= date('F j, Y g:i A', strtotime($row['feedback_date'])) ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['response_message']) ?><br>
                                <small><?= date('F j, Y g:i A', strtotime($row['response_date'])) ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['reply_message']) ?><br>
                                <small><?= date('F j, Y g:i A', strtotime($row['reply_date'])) ?></small>
                            </td>
                            <td>
                                <?php if ($row['admin_reply_message']) : ?>
                                    <?= htmlspecialchars($row['admin_reply_message']) ?><br>
                                    <small><?= date('F j, Y g:i A', strtotime($row['admin_reply_date'])) ?></small>
                                <?php else : ?>
                                    <form method="POST">
                                        <input type="hidden" name="reply_to_buyer" value="<?= $row['reply_id'] ?>">
                                        <textarea name="reply_message" placeholder="Balas balasan pembeli..." required></textarea>
                                        <button type="submit">Kirim Balasan</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Feedback Pending</h2>
            <table>
                <thead>
                    <tr>
                        <th>Pesan Pembeli</th>
                        <th>Balasan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $pending_feedbacks->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['message']) ?><br>
                                <small><?= date('F j, Y g:i A', strtotime($row['created_at'])) ?></small>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="feedback_id" value="<?= $row['id'] ?>">
                                    <textarea name="response_message" placeholder="Balas feedback ini..." required></textarea>
                                    <button type="submit">Kirim Balasan</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
