# Changelog

All notable changes to `local_ransomleak` are documented here.

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

> **MATURITY_ALPHA — scaffold.** The `tool_registrar` config keys (especially the
> Deep Linking key name) are **not yet verified against a live Moodle**. Test the
> full launch + grade writeback on Moodle 4.1 LTS, 4.5 LTS, and 5.x before the
> Plugins Directory submission.
