<?php
if (!isset($pageDepth)) $pageDepth = 1;
if (!isset($currentPage)) $currentPage = '';
if (!isset($pageTitle)) $pageTitle = 'KitchenFlow';

$prefix = str_repeat('../', $pageDepth);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - KitchenFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link rel="stylesheet" href="<?= $prefix ?>assets/css/style.css?v=<?= @filemtime(__DIR__ . '/../../assets/css/style.css') ?>">
    <script src="<?= $prefix ?>jquery/dist/jquery.min.js"></script>
</head>
<body class="min-h-screen bg-surface font-body-md text-on-surface">

    <div class="flex min-h-screen">

        <?php include __DIR__ . '/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 ml-[280px] min-h-screen flex flex-col">

            <?php include __DIR__ . '/navbar.php'; ?>

            <!-- Page Content -->
            <div class="flex-1 px-6 py-6">
