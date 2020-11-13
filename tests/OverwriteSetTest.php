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

namespace OCA\PermissionsOverwrite\Tests;

use OCA\PermissionsOverwrite\OverwriteSet;
use Test\TestCase;

class OverwriteSetTest extends TestCase {
	public function testSet() {
		$overwrites = [
			'test/bar/asd' => 5,
			'foo' => 1,
			'foobar' => 2,
			'test' => 3,
			'test/bar' => 4,
		];
		$set = new OverwriteSet($overwrites);

		$this->assertEquals(null, $set->getOverwriteForPath(''));
		$this->assertEquals(null, $set->getOverwriteForPath('nonmatching'));

		$this->assertEquals(1, $set->getOverwriteForPath('foo'));
		$this->assertEquals(1, $set->getOverwriteForPath('foo/sub'));

		$this->assertEquals(2, $set->getOverwriteForPath('foobar'));

		$this->assertEquals(5, $set->getOverwriteForPath('test/bar/asd'));
	}
}
