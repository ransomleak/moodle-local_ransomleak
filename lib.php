<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Library hooks for local_ransomleak.
 *
 * @package    local_ransomleak
 * @copyright  2026 RansomLeak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Settings-change callback: (re)register the preconfigured LTI 1.3 tool whenever
 * the admin saves a new tenant URL. Surfaces a notification with the outcome.
 *
 * @return void
 */
function local_ransomleak_tenanturl_updated() {
    $tenanturl = get_config('local_ransomleak', 'tenanturl');
    if (empty($tenanturl)) {
        return;
    }

    $toolname = get_config('local_ransomleak', 'toolname') ?: get_string('toolname_default', 'local_ransomleak');

    try {
        \local_ransomleak\tool_registrar::register((string)$tenanturl, (string)$toolname);
        \core\notification::success(get_string('registered', 'local_ransomleak', s($tenanturl)));
    } catch (\Throwable $e) {
        \core\notification::error(get_string('registerfailed', 'local_ransomleak', $e->getMessage()));
    }
}
