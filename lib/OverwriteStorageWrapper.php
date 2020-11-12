<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2020 Robin Appelman <robin@icewind.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\PermissionsOverwrite;

use OC\Files\Storage\Wrapper\Wrapper;
use OCP\Constants;

class OverwriteStorageWrapper extends Wrapper {
	/** @var OverwriteManager */
	private $manager;
	/** @var int */
	private $mountId;

	public function __construct($parameters) {
		parent::__construct($parameters);
		$this->manager = $parameters['manager'];
		$this->mountId = $parameters['mount_id'];
	}

	public function getPermissions($path) {
		$overwrite = $this->manager->getOverwrite($this->mountId, $path);
		if ($overwrite !== null) {
			return $overwrite;
		}

		return parent::getPermissions($path);
	}

	public function isReadable($path) {
		return ($this->getPermissions($path) & Constants::PERMISSION_READ) > 0;
	}

	public function isCreatable($path) {
		return ($this->getPermissions($path) & Constants::PERMISSION_CREATE) > 0;
	}

	public function isUpdatable($path) {
		return ($this->getPermissions($path) & Constants::PERMISSION_UPDATE) > 0;
	}

	public function isDeletable($path) {
		return ($this->getPermissions($path) & Constants::PERMISSION_DELETE) > 0;
	}

	public function isSharable($path) {
		return ($this->getPermissions($path) & Constants::PERMISSION_SHARE) > 0;
	}

	public function getMetaData($path) {
		$data = parent::getMetaData($path);

		if ($data && isset($data['permissions'])) {
			$overwrite = $this->manager->getOverwrite($this->mountId, $path);
			if ($overwrite !== null) {
				$data['scan_permissions'] = isset($data['scan_permissions']) ? $data['scan_permissions'] : $data['permissions'];
				$data['permissions'] = $overwrite;
			}
		}
		return $data;
	}

	public function getCache($path = '', $storage = null) {
		if (!$storage) {
			$storage = $this;
		}
		$sourceCache = parent::getCache($path, $storage);
		return new OverwriteCacheWrapper($sourceCache, $this->manager, $this->mountId);
	}
}
