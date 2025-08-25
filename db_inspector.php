<?php
include 'db_connect.php';

// Get all tables & their columns
$tables = [];
$tablesRes = $conn->query("SHOW TABLES");
while ($row = $tablesRes->fetch_array()) {
    $tables[] = $row[0];
}

$schema = [];
foreach ($tables as $tbl) {
    $colsRes = $conn->query("SHOW COLUMNS FROM `$tbl`");
    while ($col = $colsRes->fetch_assoc()) {
        $schema[$tbl][] = $col;
    }
}

// Handle ad-hoc SELECT query
$querySQL = "";
$resultRows = [];
$resultFields = [];
$errorMsg = "";

if (!empty($_POST['sql'])) {
    $querySQL = trim($_POST['sql']);

    // allow only SELECT statements for safety
    if (preg_match('/^\s*select/i', $querySQL)) {
        $queryRes = $conn->query($querySQL);
        if ($queryRes) {
            $resultFields = $queryRes->fetch_fields();
            while ($row = $queryRes->fetch_assoc()) {
                $resultRows[] = $row;
            }
        } else {
            $errorMsg = $conn->error;
        }
    } else {
        $errorMsg = "Only SELECT statements are allowed in this console.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DB Inspector - Hostel Management</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        details { margin-bottom: 12px; background:#fff; border:1px solid #ccc; border-radius:6px; }
        summary { padding: 10px 14px; cursor:pointer; font-weight:600; background:#eceff3; }
        details table { margin: 0; }
        .console { margin-top:30px; background:#fff; padding:20px; border:1px solid #ccc; border-radius:6px; }
        textarea { width:100%; height:80px; font-family: monospace; margin-bottom:10px; }
        .error { color:#c00; margin:10px 0; }
        .resultTable { width:100%; border-collapse:collapse; background:#fff; margin-top:10px; }
        .resultTable th, .resultTable td { padding:8px 10px; border:1px solid #ccc; }
        .resultTable th { background:#eceff3; }
    </style>
</head>
<body>
    <h1>Database Inspector - Hostel Management</h1>
    <p><a href="index.php" class="btn">← Back to Student List</a></p>

    <!-- Schema overview -->
    <?php foreach ($schema as $tbl => $cols): ?>
        <details>
            <summary><?= $tbl ?></summary>
            <table>
                <thead><tr><th>Field</th><th>Type</th><th>Key</th><th>Null</th><th>Default</th><th>Extra</th></tr></thead>
                <tbody>
                <?php foreach ($cols as $c): ?>
                    <tr>
                        <td><?= $c['Field']   ?></td>
                        <td><?= $c['Type']    ?></td>
                        <td><?= $c['Key']     ?></td>
                        <td><?= $c['Null']    ?></td>
                        <td><?= $c['Default'] ?></td>
                        <td><?= $c['Extra']   ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </details>
    <?php endforeach; ?>

    <!-- SQL console -->
    <div class="console">
        <h2>Run a SELECT query</h2>
        <form method="POST">
            <textarea name="sql" placeholder="e.g. SELECT * FROM students LIMIT 5"><?= htmlspecialchars($querySQL) ?></textarea>
            <button type="submit" class="btn">Run</button>
        </form>

        <?php if ($errorMsg): ?>
            <p class="error">❌ <?= $errorMsg ?></p>
        <?php elseif ($querySQL): ?>
            <h3>Results (<?= count($resultRows) ?> rows)</h3>
            <?php if ($resultRows): ?>
                <table class="resultTable">
                    <thead>
                        <tr>
                            <?php foreach ($resultFields as $f): ?>
                                <th><?= $f->name ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultRows as $r): ?>
                            <tr>
                            <?php foreach ($r as $cell): ?>
                                <td><?= htmlspecialchars($cell) ?></td>
                            <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No rows returned.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <p style="margin-top:20px;"><a href="index.php">← Back to Student List</a></p>
</body>
</html>