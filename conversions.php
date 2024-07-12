<?php

require_once 'db.php';

$conversions = DB::getConversions();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Currency Conversions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Currency Conversions</h1>
    <table class="table table-striped mt-3">
        <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>From Currency</th>
            <th>To Currency</th>
            <th>Converted Amount</th>
            <th>Conversion Time</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($conversions as $conversion): ?>
            <tr>
                <td><?= htmlspecialchars($conversion['id']) ?></td>
                <td><?= htmlspecialchars($conversion['user_id']) ?></td>
                <td><?= htmlspecialchars($conversion['amount']) ?></td>
                <td><?= htmlspecialchars($conversion['from_currency']) ?></td>
                <td><?= htmlspecialchars($conversion['to_currency']) ?></td>
                <td><?= htmlspecialchars($conversion['converted_amount']) ?></td>
                <td><?= htmlspecialchars($conversion['conversion_time']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
