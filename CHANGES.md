# Changelog

All notable changes to `local_ransomleak` are documented here.

## v1.0.0 — 2026-06-30

First stable release. The verified v0.2.x codebase is promoted to a 1.0 stable
line — no functional changes since v0.2.2; maturity raised Beta → Stable.

## v0.2.2 — 2026-06-30

Correctness fixes from a code review (all verified live on Moodle 5.2.1):

- **Critical — stop rotating the client_id on every save.** The update path passed
  an empty `lti_clientid`, so Moodle minted a fresh client_id on each settings save,
  breaking every launch the admin had already registered in RansomLeak. The existing
  client_id is now preserved on update.
- **Preserve admin edits on update** — the update modifies the existing tool row in
  place (only name/URLs/visibility), so a description edited in *Manage tools* is no
  longer overwritten.
- **Backfill the type id on update**, not only on create, so the URL-change
  protection also covers installs upgraded from an earlier version.
- **Correct Deep Linking key** — `lti_toolurl_ContentItemSelectionRequest` instead of
  the dead `lti_contentitem_url`.
- **Normalise the tenant URL** — accept uppercase schemes (`HTTPS://`), lowercase the
  host, and drop the default `:443` port for stable matching.
- **Don't silently drop a pasted URL** with surrounding whitespace
  (`PARAM_RAW_TRIMMED` instead of `PARAM_URL`, which blanked such input with no error).
- **Sync a rename** — the tool-name setting now re-registers too.
- Load `lib.php` from `settings.php` so the settings-change callback is always
  callable on save; HTML-escape the error notification.

## v0.2.1 — 2026-06-30

- **Robust to tenant-URL changes.** The registrar now records the Moodle-assigned
  lti type id on first creation and updates that same tool on later saves, so a
  tenant-URL change (e.g. subdomain → custom domain) updates the existing tool
  instead of orphaning it and minting a duplicate. Verified on Moodle 5.2.1
  (register → change URL → re-register updates the same tool in place).
- Minor code cleanups: collapsed a redundant scheme check, inlined single-use
  vars, honest CI wording.

## v0.2.0 — 2026-06-30

- **Verified on Moodle 5.2.1.** The plugin installs cleanly and the `tool_registrar`
  auto-creates a correct LTI 1.3 tool: `contentitem` (Deep Linking),
  `ltiservice_memberships` (NRPS), and `ltiservice_gradesynchronization` (AGS) are
  all accepted and enabled, with the right login / launch / JWKS URLs and
  `coursevisible` = activity chooser. Confirmed `lti_contentitem` /
  `lti_contentitem_url` are the correct Moodle config keys (no
  `lti_toolurl_ContentItemSelectionRequest` needed).
- Maturity raised ALPHA → BETA.

> Still pending: a full real-LMS **launch + grade writeback** end-to-end against a
> running RansomLeak tool. The registrar config itself is now verified.

## v0.1.0 — 2026-06-30

Initial standalone repository, extracted from the RansomLeak monorepo scaffold.

- Preconfigured **LTI 1.3** external-tool registration from a single *tenant URL*
  admin setting; login / launch / JWKS URLs are derived from it.
- Re-registers automatically when the tenant URL changes (settings callback).
- Registrar advertises the RansomLeak LTI Advantage services:
  - **AGS** grade synchronization (`ltiservice_gradesynchronization`),
  - **NRPS** course membership (`ltiservice_memberships`),
  - **Deep Linking / Content-Item** selection (`lti_contentitem`) so teachers can
    pick a specific exercise, course, or learning path from the activity chooser.
- Null privacy provider (the plugin stores no personal data of its own).
- GPLv3; `moodle-plugin-ci` workflow (phplint, phpcs, phpdoc, validate, phpunit).
