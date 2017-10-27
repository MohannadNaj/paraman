<?php

namespace Parameter\Tests;

trait TestHelper
{
	public function assertArrayContains($needles, $haystack, $message = '') {
		$intersect = array_intersect($haystack, $needles);
		$needles = (array) $needles;

		asort($intersect);
		asort($needles);

		$this->assertEquals(array_values($intersect), array_values($needles), $message );
	}
}
