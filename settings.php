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
 * Admin settings for local_ransomleak.
 *
 * @package    local_ransomleak
 * @copyright  2026 RansomLeak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Load the settings-change callback so it is callable when Moodle writes settings
// (the settings tree only auto-loads settings.php, not lib.php).
require_once(__DIR__ . '/lib.php');

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_ransomleak', get_string('pluginname', 'local_ransomleak'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_heading(
        'local_ransomleak/heading',
        get_string('settingsheading', 'local_ransomleak'),
        get_string('settingsheading_desc', 'local_ransomleak')
    ));

    // Both settings (re)register the tool on change, via the same lib.php callback.
    $callback = 'local_ransomleak_settings_updated';

    // Tenant URL.
    $tenant = new admin_setting_configtext(
        'local_ransomleak/tenanturl',
        get_string('tenanturl', 'local_ransomleak'),
        get_string('tenanturl_desc', 'local_ransomleak'),
        '',
        PARAM_RAW_TRIMMED
    );
    $tenant->set_updatedcallback($callback);
    $settings->add($tenant);

    // Tool display name. Also (re)registers so a rename syncs to the LTI tool.
    $name = new admin_setting_configtext(
        'local_ransomleak/toolname',
        get_string('toolname', 'local_ransomleak'),
        get_string('toolname_desc', 'local_ransomleak'),
        get_string('toolname_default', 'local_ransomleak'),
        PARAM_TEXT
    );
    $name->set_updatedcallback($callback);
    $settings->add($name);
}
