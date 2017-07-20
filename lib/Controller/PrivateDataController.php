<?php

namespace OCA\PrivateData\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IDBConnection;
use OCP\IRequest;

class PrivateDataController extends OCSController {

	/** @var IDBConnection */
	private $db;

	/** @var string */
	private $userId;

	public function __construct($appName,
								IRequest $request,
								IDBConnection $db,
								$userId) {
		parent::__construct($appName, $request);

		$this->db = $db;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param string|null $app
	 * @param string|null $key
	 * @return DataResponse
	 */
	public function get($app, $key) {

		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from('privatedata')
			->where($qb->expr()->eq('user', $qb->createNamedParameter($this->userId)));

		if ($app !== null) {
			$qb->andWhere(
				$qb->expr()->eq('app', $qb->createNamedParameter($app))
			);
		}

		if ($key !== null) {
			$qb->andWhere(
				$qb->expr()->eq('key', $qb->createNamedParameter($key))
			);
		}

		$cursor = $qb->execute();
		$result = [];

		while($row = $cursor->fetch()) {
			$result[] = [
				'key' => $row['key'],
				'app' => $row['app'],
				'value' => $row['value'],
			];
		}

		$cursor->closeCursor();

		return new DataResponse($result);
	}


	/**
	 * @NoAdminRequired
	 *
	 * @param string $app
	 * @param string $key
	 * @param string $value
	 * @return DataResponse
	 */
	public function set($app, $key, $value) {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from('privatedata')
			->where($qb->expr()->eq('user', $qb->createNamedParameter($this->userId)))
			->andWhere($qb->expr()->eq('app', $qb->createNamedParameter($app)))
			->andWhere($qb->expr()->eq('key', $qb->createNamedParameter($key)));

		$cursor = $qb->execute();
		$data = $cursor->fetch();
		$cursor->closeCursor();

		if (!$data) {
			//Insert
			$qb = $this->db->getQueryBuilder();
			$qb->insert('privatedata')
				->values([
					'user' => $qb->createNamedParameter($this->userId),
					'app' => $qb->createNamedParameter($app),
					'key' => $qb->createNamedParameter($key),
					'value' => $qb->createNamedParameter($value),
				]);
			$qb->execute();
		} else {
			//Update
			$qb = $this->db->getQueryBuilder();
			$qb->update('privatedata')
				->set('value', $qb->createNamedParameter($value))
				->where($qb->expr()->eq('user', $qb->createNamedParameter($this->userId)))
				->andWhere($qb->expr()->eq('app', $qb->createNamedParameter($app)))
				->andWhere($qb->expr()->eq('key', $qb->createNamedParameter($key)));
			$qb->execute();
		}

		return new DataResponse();
	}


	/**
	 * @NoAdminRequired
	 *
	 * @param string $app
	 * @param string $key
	 * @return DataResponse
	 */
	public function delete($app, $key) {
		$qb = $this->db->getQueryBuilder();

		$qb->delete('privatedata')
			->where($qb->expr()->eq('user', $qb->createNamedParameter($this->userId)))
			->andWhere($qb->expr()->eq('app', $qb->createNamedParameter($app)))
			->andWhere($qb->expr()->eq('key', $qb->createNamedParameter($key)));
		$qb->execute();

		return new DataResponse();
	}
}
