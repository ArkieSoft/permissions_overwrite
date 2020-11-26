<?php

declare(strict_types=1);

namespace OCA\PermissionsOverwrite\Migration;

use Closure;
use Doctrine\DBAL\Types\Types;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version001000Date20201112164405 extends SimpleMigrationStep {
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable('permissions_overwrite')) {
			$table = $schema->createTable('permissions_overwrite');
			$table->addColumn('id', 'bigint', [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('mount_id', 'integer', [
				'notnull' => true,
			]);
			$table->addColumn('path', 'text', [
				'notnull' => false,
				'length' => 64,
			]);
			$table->addColumn('path_hash', 'string', [
				'notnull' => true,
				'length' => 32,
			]);
			$table->addColumn('permissions', 'integer', [
				'notnull' => true,
			]);
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['mount_id', 'path_hash'], 'perm_overwrite_path');
		}

		return $schema;
	}
}
