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
			$table->addColumn('id', Types::BIGINT, [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('mount_id', Types::INTEGER, [
				'notnull' => true,
			]);
			$table->addColumn('path', Types::TEXT, [
				'notnull' => false,
				'length' => 64,
			]);
			$table->addColumn('path_hash', Types::STRING, [
				'notnull' => true,
				'length' => 32,
			]);
			$table->addColumn('permissions', Types::INTEGER, [
				'notnull' => true,
			]);
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['mount_id', 'path_hash'], 'perm_overwrite_path');
		}

		return $schema;
	}
}
