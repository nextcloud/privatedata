<?php

namespace OCA\PrivateData\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version10000Date201707202147 extends SimpleMigrationStep {
	/**
	 * @param IOutput $output
	 * @param \Closure $schemaClosure The `\Closure` returns a `Schema`
	 * @param array $options
	 * @return null|Schema
	 * @since 13.0.0
	 */
	public function changeSchema(IOutput $output, \Closure $schemaClosure, array $options) {
		/** @var Schema $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable('privatedata')) {
			$table = $schema->createTable('privatedata');

			$table->addColumn('keyid',
				Type::INTEGER,
				[
					'notnull' => true,
					'length' => 4,
					'unsigned' => true,
					'autoincrement' => true,
				]
			);
			$table->addColumn('user',
				Type::STRING,
				[
					'notnull' => true,
					'length' => 64,
				]
			);
			$table->addColumn('app',
				Type::STRING,
				[
					'notnull' => true,
					'length' => 255,
				]
			);
			$table->addColumn('key',
				Type::STRING,
				[
					'notnull' => true,
					'length' => 255,
				]
			);
			$table->addColumn('value',
				Type::STRING,
				[
					'notnull' => true,
					'length' => 255,
				]
			);

			$table->setPrimaryKey(['keyid']);
		}

		return $schema;
	}
}
