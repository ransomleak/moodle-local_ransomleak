# RansomLeak for Moodle (`local_ransomleak`)

A Moodle **local plugin** that registers [RansomLeak](https://ransomleak.com) as a
preconfigured **LTI 1.3 external tool**, so site admins can add RansomLeak
security-awareness content to courses without hand-entering URLs. Intended for the
[Moodle Plugins directory](https://moodle.org/plugins/).

> **Status: v1.0.1, stable.** The registrar is verified against a live Moodle 5.2.1
> (install → register → re-register → URL change), and `moodle-plugin-ci` is green on
> Moodle 4.1 LTS, 4.5 LTS and 5.0 across PHP 8.1–8.3. Submitted to the
> [Moodle Plugins directory](https://moodle.org/plugins/) and awaiting moderator
> approval.

## Why a plugin (vs. just docs)

RansomLeak is the **tool**; Moodle is the **platform**. Moodle has no app-store for
external tools — admins normally register them by hand under *Site admin → Plugins →
External tool → Manage tools*, pasting login/launch/JWKS URLs and exchanging platform
details. This plugin removes that friction: the admin enters **one value — their
RansomLeak tenant URL** — and the plugin creates the preconfigured LTI 1.3 tool. The
directory listing also makes RansomLeak discoverable to the ~250k Moodle sites.

## Multi-tenancy

RansomLeak is multi-tenant: every customer has their own host
(`https://<tenant>.ransomleak.com` or a custom domain) and their LTI endpoints live
under it. The plugin can't hardcode one tenant — the admin supplies their tenant base
URL in plugin settings, and the plugin derives:

| Tool setting (Moodle side) | Value |
|---|---|
| Tool URL (launch) | `<tenant>/api/lti/launch` |
| Initiate login URL | `<tenant>/api/lti/login` |
| Redirection URI(s) | `<tenant>/api/lti/launch` |
| Public keyset URL | `<tenant>/api/lti/.well-known/jwks.json` |
| LTI version | LTI 1.3 |

## LTI Advantage services advertised

The registrar enables the services RansomLeak supports, so teachers and admins get the
full experience without extra configuration:

- **AGS (grades)** — RansomLeak writes completion scores back to the Moodle gradebook.
- **NRPS (membership)** — RansomLeak can sync the course roster (zero seat consumption)
  to pre-provision learners.
- **Deep Linking (content-item)** — teachers pick a specific exercise, course, or
  learning path from the activity chooser, instead of launching the whole catalog.

## The two-sided handshake (LTI 1.3, manual)

RansomLeak does **not** yet expose OIDC **Dynamic Registration**, so registration is a
one-time manual exchange (the plugin automates the Moodle half):

1. **Install + configure** this plugin → it creates the preconfigured tool from your
   tenant URL.
2. Moodle shows its **platform details** under *Manage tools → (tool) → View
   configuration details*: Platform ID (issuer), Client ID, Deployment ID, Public
   keyset URL, Access token URL, Authentication request URL.
3. In **RansomLeak → Admin → Integrations → LTI → Register a platform**, paste those
   Moodle values. Done — teachers can now add the tool via *Add an activity → External
   tool → (preconfigured) RansomLeak*.

> A future RansomLeak **Dynamic Registration** endpoint would collapse steps 2–3 into
> pasting a single URL. Recommended as a fast-follow.

## Repository layout

```
.
├── version.php                      # component, version, requires, maturity
├── settings.php                     # admin settings (tenant URL, tool name)
├── lib.php                          # settings-change callback → (re)register
├── lang/en/local_ransomleak.php     # English strings
├── classes/
│   ├── tool_registrar.php           # builds the LTI 1.3 config + lti_add_type()
│   └── privacy/provider.php         # null provider (stores no personal data)
├── .github/workflows/moodle-ci.yml  # moodle-plugin-ci (phplint, phpcs, phpdoc, phpunit)
├── CHANGES.md
└── LICENSE                          # GNU GPL v3
```

## Install

Install from the Moodle Plugins directory, or clone this repo into your site's
`local/` directory. **On Moodle 5.x the site root moved to `public/`**, so the target
path differs:

```bash
# Moodle 4.1 – 4.5
git clone https://github.com/ransomleak/moodle-local_ransomleak.git \
  /path/to/moodle/local/ransomleak

# Moodle 5.0+
git clone https://github.com/ransomleak/moodle-local_ransomleak.git \
  /path/to/moodle/public/local/ransomleak
```

Then visit *Site administration → Notifications* to run the install, and set your
tenant URL under *Site administration → Plugins → Local plugins → RansomLeak*.

## Compatibility

| | |
|---|---|
| Moodle | 4.1 LTS – 5.2 |
| PHP | 8.1 – 8.3 |
| Maturity | Stable |
| License | GNU GPL v3 or later |

Verified end-to-end on a live Moodle 5.2.1: install, tool registration, re-registration
after a tenant-URL change, and Deep Linking / AGS / NRPS enabled on the created tool.

## License

GNU GPL v3 or later — required for Moodle plugins. See [LICENSE](LICENSE).
