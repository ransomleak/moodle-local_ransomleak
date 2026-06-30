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

namespace local_ransomleak;

/**
 * Creates / updates the preconfigured RansomLeak LTI 1.3 tool type.
 *
 * The config keys below follow Moodle's mod_lti type-config form
 * (mod/lti/edit_form.php) and are consumed by lti_add_type(). Verified on
 * Moodle 5.2.1 (CI-tested on 4.1 LTS / 4.5 LTS / 5.0).
 *
 * @package    local_ransomleak
 * @copyright  2026 RansomLeak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_registrar {
    /** @var string Path suffix for the LTI 1.3 OIDC login endpoint. */
    private const PATH_LOGIN = '/api/lti/login';

    /** @var string Path suffix for the LTI 1.3 launch / deep-link endpoint. */
    private const PATH_LAUNCH = '/api/lti/launch';

    /** @var string Path suffix for the public JWKS endpoint. */
    private const PATH_JWKS = '/api/lti/.well-known/jwks.json';

    /**
     * Register (or update) the preconfigured tool for the given tenant.
     *
     * @param string $tenanturl Base tenant URL, e.g. https://acme.ransomleak.com
     * @param string $toolname  Display name shown in the activity chooser.
     * @return int The lti type id.
     * @throws \moodle_exception on invalid input or registration failure.
     */
    public static function register(string $tenanturl, string $toolname): int {
        global $CFG, $SITE;
        require_once($CFG->dirroot . '/mod/lti/locallib.php');

        $base = self::normalise_tenant_url($tenanturl);

        $launchurl = $base . self::PATH_LAUNCH;

        // Build the LTI 1.3 type-config. Moodle generates the platform-side
        // client id / deployment id on save; the admin reads them back from
        // "Manage tools" and registers them in RansomLeak.
        $config = (object) [
            'lti_typename'        => $toolname,
            'lti_toolurl'         => $launchurl,
            'lti_ltiversion'      => LTI_VERSION_1P3,
            'lti_clientid'        => '', // Moodle assigns one.
            'lti_keytype'         => 'JWK_KEYSET',
            'lti_publickeyset'    => $base . self::PATH_JWKS,
            'lti_initiatelogin'   => $base . self::PATH_LOGIN,
            'lti_redirectionuris' => $launchurl,
            'lti_coursevisible'   => LTI_COURSEVISIBLE_ACTIVITYCHOOSER,
            'lti_launchcontainer' => LTI_LAUNCH_CONTAINER_DEFAULT,
            // Deep Linking (Content-Item): RansomLeak's deep-link picker lets the
            // teacher choose a specific exercise / course / learning path from the
            // activity chooser instead of launching the whole catalog. The DL request
            // is routed through the same OIDC login + launch endpoint (the launch
            // validator branches on message_type). Verified on Moodle 5.2.1: these
            // keys are stored as `contentitem` / `contentitem_url` in lti_types_config.
            'lti_contentitem'     => 1,
            'lti_contentitem_url' => $launchurl,
            // Privacy: RansomLeak identifies learners by the LTI `sub` claim and
            // provisions just-in-time — never by email. Send the display name so
            // launches read nicely; leave email to the teacher's discretion.
            'lti_sendname'        => LTI_SETTING_ALWAYS,
            'lti_sendemailaddr'   => LTI_SETTING_DELEGATE,
            // Assignment & Grade Services — RansomLeak writes completion scores back.
            'ltiservice_gradesynchronization' => 1,
            // Names & Role Provisioning — RansomLeak's NRPS roster sync pulls course
            // membership (zero seat consumption) so admins can pre-provision learners.
            'ltiservice_memberships'          => 1,
            'ltiservice_toolsettings'         => 0,
        ];

        $type = (object) [
            'name'         => $toolname,
            'baseurl'      => $launchurl,
            'course'       => $SITE->id, // Site-level tool.
            'state'        => LTI_TOOL_STATE_CONFIGURED,
            'coursevisible' => LTI_COURSEVISIBLE_ACTIVITYCHOOSER,
            'description'  => 'Security-awareness training and phishing drills (RansomLeak).',
        ];

        if ($existing = self::find_existing_type($launchurl)) {
            lti_update_type($existing, $config);
            return (int) $existing->id;
        }

        return (int) lti_add_type($type, $config);
    }

    /**
     * Normalise and validate the tenant URL: require https, strip any trailing slash/path noise.
     *
     * @param string $tenanturl
     * @return string
     * @throws \moodle_exception
     */
    private static function normalise_tenant_url(string $tenanturl): string {
        $tenanturl = trim($tenanturl);
        $parts = parse_url($tenanturl);
        if (empty($parts['scheme']) || $parts['scheme'] !== 'https' || empty($parts['host'])) {
            throw new \moodle_exception('invalidtenanturl', 'local_ransomleak');
        }
        return 'https://' . $parts['host'] . (isset($parts['port']) ? ':' . $parts['port'] : '');
    }

    /**
     * Find an existing lti type previously created for this launch URL, if any.
     *
     * @param string $launchurl
     * @return \stdClass|null
     */
    private static function find_existing_type(string $launchurl): ?\stdClass {
        global $DB;
        $record = $DB->get_record('lti_types', ['baseurl' => $launchurl], '*', IGNORE_MULTIPLE);
        return $record ?: null;
    }
}
