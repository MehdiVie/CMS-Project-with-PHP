<?php
// Load bootstrap and EntityManager
require_once __DIR__ . '/bootstrap.php';

use Doctrine\ORM\Tools\SchemaTool;

try {
    $entityManager->getConnection()->getDatabasePlatform();
    echo "✅ Connected to database successfully!\n";
} catch (\Exception $e) {
    echo "❌ Failed to connect: " . $e->getMessage() . "\n";
    exit(1);
}

// --- Step 1: Get all metadata from your entities ---
$metadatas = $entityManager->getMetadataFactory()->getAllMetadata();

echo "Found " . count($metadatas) . " metadata classes.\n";

// --- Step 2: Check if there is any metadata ---
if (empty($metadatas)) {
    echo "No Metadata Classes to process. Make sure your entities are correctly configured.\n";
    exit(1);
}

// --- Step 3: Create SchemaTool ---
$schemaTool = new SchemaTool($entityManager);

// --- Step 4: Update database schema ---
// true = force update (apply changes)
$schemaTool->updateSchema($metadatas, true);

echo "✅ Database schema updated successfully!\n";
echo "Tables created/updated:\n";

foreach ($metadatas as $meta) {
    echo "- " . $meta->getTableName() . "\n";
}
