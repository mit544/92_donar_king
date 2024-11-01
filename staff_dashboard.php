<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-5">

<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-5">Available Stock</h1>

    <?php if ($success): ?>
        <div class="p-3 bg-green-200 text-green-800 rounded mb-4"><?= htmlspecialchars($success) ?></div>
    <?php elseif (!empty($errors_log_in)): ?>
        <div class="p-3 bg-red-200 text-red-800 rounded mb-4"><?= htmlspecialchars(implode("<br>", $errors_log_in)) ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($stockItems as $item): ?>
            <div class="bg-white p-4 rounded shadow-md">
                <h2 class="font-semibold text-lg"><?= htmlspecialchars($item['item_name']) ?></h2>
                <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                <p>Added Date: <?= htmlspecialchars($item['product_added_date']) ?></p>
                <p class="text-sm text-gray-600">Last Updated: <?= htmlspecialchars($item['updated_at']) ?></p>

                <!-- Form to add a note for each stock item -->
                <form action="get_stock.php" method="POST" class="mt-3 space-y-2">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
                    <textarea name="note" rows="2" placeholder="Add a note..." required
                              class="block w-full rounded border-gray-300 p-2 text-sm"></textarea>
                    <button type="submit"
                            class="w-full bg-blue-500 text-white font-semibold py-1 rounded hover:bg-blue-600">
                        Add Note
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
