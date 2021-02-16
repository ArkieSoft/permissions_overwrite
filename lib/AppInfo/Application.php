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

namespace OCA\PermissionsOverwrite\AppInfo;

use OC\Files\Filesystem;
use OC\Files\Storage\Storage;
use OCA\Files_External\Lib\PersonalMount;
use OCA\Files_External\Service\UserGlobalStoragesService;
use OCA\PermissionsOverwrite\OverwriteManager;
use OCA\PermissionsOverwrite\OverwriteSet;
use OCA\PermissionsOverwrite\OverwriteStorageWrapper;
use OCP\AppFramework\App;
use OCP\Files\Mount\IMountPoint;
use OCP\IUserSession;

class Application extends App {
	public const APP_ID = 'permissions_overwrite';

	public function __construct() {
		parent::__construct(self::APP_ID);

		$this->setup();
	}

	public function setup() {
		\OCP\Util::connectHook('OC_Filesystem', 'preSetup', $this, 'setupStorageWrapper');
	}

	private function getMountIdForMountpoint(string $mountPoint): ?int {
		/** @var UserGlobalStoragesService $storageService */
		$storageService = $this->getContainer()->query(UserGlobalStoragesService::class);
		/** @var IUserSession $userSession */
		$userSession = $this->getContainer()->query(IUserSession::class);
		$user = $userSession->getUser();
		if ($user === null) {
			return null;
		}
		foreach ($storageService->getAllStoragesForUser($user) as $storageConfig) {
			$storageMountPoint = rtrim('/' . $user->getUID() . '/files' . $storageConfig->getMountPoint()) . '/';
			if ($storageMountPoint === $mountPoint) {
				return $storageConfig->getId();
			}
		}

		return null;
	}

	public function setupStorageWrapper() {
		Filesystem::addStorageWrapper('permissions_overwrite', function (string $mountPoint, Storage $storage, IMountPoint $mount) {
			$mountId = $mount->getMountId();
			// work around mount id not being set on personal mounts
			if ($mountId === null && $mount instanceof PersonalMount) {
				$mountId = $this->getMountIdForMountpoint($mount->getMountPoint());
			}

			if ($mountId) {
				/** @var OverwriteManager $manager */
				$manager = $this->getContainer()->query(OverwriteManager::class);
				$overwrites = new OverwriteSet($manager->getOverwritesForMount($mountId));
				return new OverwriteStorageWrapper([
					'storage' => $storage,
					'overwrites' => $overwrites,
				]);
			} else {
				return $storage;
			}
		});
	}
}
