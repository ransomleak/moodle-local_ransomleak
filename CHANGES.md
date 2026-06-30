# Changelog

All notable changes to `local_ransomleak` are documented here.

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
