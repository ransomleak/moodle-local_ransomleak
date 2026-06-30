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
 * English strings for local_ransomleak.
 *
 * @package    local_ransomleak
 * @copyright  2026 RansomLeak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'RansomLeak security awareness (LTI)';

$string['settingsheading'] = 'RansomLeak LTI tool';
$string['settingsheading_desc'] =
    'Register RansomLeak as a preconfigured LTI 1.3 external tool. Enter your '
    . 'RansomLeak tenant URL below and save — the tool is created automatically. '
    . 'Then open <em>Site administration → Plugins → External tool → Manage tools</em>, '
    . 'view the new tool\'s configuration details, and paste Moodle\'s Platform ID, '
    . 'Client ID and Deployment ID into RansomLeak under '
    . '<em>Admin → Integrations → LTI → Register a platform</em>.';

$string['tenanturl'] = 'RansomLeak tenant URL';
$string['tenanturl_desc'] =
    'The base URL of your RansomLeak workspace, with no trailing path — for '
    . 'example <code>https://acme.ransomleak.com</code> or your custom domain. '
    . 'The tool\'s login, launch and JWKS URLs are derived from this.';

$string['toolname'] = 'Tool display name';
$string['toolname_desc'] = 'The name teachers see in the activity chooser.';
$string['toolname_default'] = 'RansomLeak security awareness';

$string['registered'] = 'RansomLeak tool registered (or updated) from {$a}.';
$string['registerfailed'] = 'Could not register the RansomLeak tool: {$a}';
$string['invalidtenanturl'] = 'Enter a valid https:// tenant URL before saving.';

// Privacy — the plugin stores no personal data of its own.
$string['privacy:metadata'] =
    'The RansomLeak LTI plugin stores no personal data. It only writes an '
    . 'external tool configuration to the site. Personal data exchanged during an '
    . 'LTI launch is governed by Moodle\'s External tool (mod_lti) and the '
    . 'RansomLeak service.';
