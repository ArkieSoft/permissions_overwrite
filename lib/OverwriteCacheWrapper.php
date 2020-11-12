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

use OC\Files\Cache\Wrapper\CacheWrapper;
use OCP\Files\Cache\ICache;

class OverwriteCacheWrapper extends CacheWrapper {
	protected $manager;
	protected $mountId;

	public function __construct(ICache $cache, OverwriteManager $manager, int $mountId) {
		parent::__construct($cache);
		$this->manager = $manager;
		$this->mountId = $mountId;
	}

	protected function formatCacheEntry($entry) {
		if (isset($entry['permissions'])) {
			$overwrite = $this->manager->getOverwrite($this->mountId, $entry['path']);
			if ($overwrite !== null) {
				$entry['scan_permissions'] = $entry['permissions'];
				$entry['permissions'] = $overwrite;
			}
		}
		return $entry;
	}
}
