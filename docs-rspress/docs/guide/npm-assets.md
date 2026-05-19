# NPM & Frontend Assets

Simple Invoices uses **npm** (Node Package Manager) to manage frontend vendor libraries: CSS frameworks, JavaScript components, icons, and fonts. This replaces the previous approach of loading these assets from external CDNs (jsDelivr, Google Fonts).

## What npm manages

| Library | Purpose |
|---------|---------|
| `@tabler/core` | UI framework (CSS + JS) |
| `@tabler/icons-webfont` | Icon font (2600+ icons) |
| `tom-select` | Searchable dropdowns |
| `hugerte` | Rich text editor (WYSIWYG) |
| `litepicker` | Date picker |
| `apexcharts` | Interactive charts |
| `@fontsource/inter` | Inter font (self-hosted) |

## What npm does

1. **`npm install`**: reads `package.json`, downloads all libraries into `node_modules/`, and generates `package-lock.json` which locks exact versions for reproducible builds
2. **`npm run copy-assets`**: runs `scripts/copy-assets.js`, a custom script that copies only the needed distribution files from `node_modules/` into `templates/default/vendor/`. The app's HTML templates reference these local files instead of CDN URLs

```
node_modules/                          templates/default/vendor/
├── @tabler/core/dist/                 ├── tabler-core/
│   ├── css/tabler.min.css  ──copy──→  │   ├── tabler.min.css
│   └── js/tabler.min.js    ──copy──→  │   └── tabler.min.js
├── @tabler/icons-webfont/dist/        ├── tabler-icons/
│   ├── tabler-icons.min.css ──copy──→ │   ├── tabler-icons.min.css
│   └── fonts/               ──copy──→ │   └── fonts/
├── apexcharts/dist/                   ├── apexcharts/
│   └── apexcharts.min.js   ──copy──→  │   └── apexcharts.min.js
... etc ...
```

## When to run npm

### During initial setup

```bash
npm install
```

This is required once when you first clone or download the project. It creates `node_modules/` and populates `templates/default/vendor/`.

### After pulling code changes

```bash
git pull
npm install      # if package.json changed
```

Run `npm install` if you see `package.json` was modified in the pull. It will only install new or updated packages.

### After editing package.json

If you manually bump a version in `package.json`:

```bash
npm install      # picks up the changed version
```

### To update all libraries

```bash
npm update
npm run copy-assets
```

This updates all packages to the latest versions within their semver ranges specified in `package.json`.

### To update a single library

```bash
npm install @tabler/core@latest
npm run copy-assets
```

Or specify an exact version:

```bash
npm install apexcharts@5.12.0
npm run copy-assets
```

### When switching branches

```bash
git checkout other-branch
npm install      # if package.json differs between branches
```

---

## With Docker vs Without Docker

### Without Docker (manual / LAMP stack)

You need **Node.js** installed on your development machine or server. Run:

```bash
# Initial setup
npm install

# After any package.json changes
npm install
```

The `templates/default/vendor/` directory is gitignored and built on deploy. It must be regenerated whenever `package.json` changes.

### With Docker

Node.js is **not** required on your host machine. The Dockerfile includes a multi-stage build:

```dockerfile
# Stage 1: Node.js builds frontend assets (throwaway)
FROM node:22-alpine AS asset-builder
RUN npm ci && node scripts/copy-assets.js

# Stage 2: PHP application (no Node.js)
FROM php:8.2-fpm-alpine
COPY --from=asset-builder /build/templates/default/vendor ...
```

This means:

- **You don't need Node.js installed** to build or run the Docker image
- **You don't need to run `npm install` manually**: `docker compose build` handles it
- The final Docker image contains **no Node.js**: only the copied asset files
- When `package.json` changes, rebuild the image:

```bash
docker compose build --no-cache simpleinvoices
docker compose up -d
```

### Comparison

| | Without Docker | With Docker |
|---|---|---|
| Node.js required on host | Yes | No |
| Run `npm install` | Manually | Automatically during `docker compose build` |
| Asset location | `templates/default/vendor/` | Same, inside the container |
| Rebuild after dependency change | `npm install` | `docker compose build` |
| Vendor dir in git | No (gitignored) | No (gitignored) |

---

## Files involved

| File | Purpose |
|------|---------|
| `package.json` | Declares which packages and versions are needed |
| `package-lock.json` | Locks exact versions for reproducible installs (auto-generated, commit this) |
| `scripts/copy-assets.js` | Copies dist files from `node_modules/` to `templates/default/vendor/` |
| `templates/default/vendor/` | Destination for copied assets (gitignored, built on deploy) |
| `node_modules/` | npm download cache (gitignored, ephemeral) |
| `.dockerignore` | Excludes `node_modules/` from Docker build context |

## Troubleshooting

### "Command not found: npm"

Node.js is not installed. Install it from [nodejs.org](https://nodejs.org) or use Docker instead.

### "Cannot find module" or missing assets

Run `npm install`: the `node_modules/` directory may be missing or outdated.

### Assets load but look wrong (old styles)

Run `npm run copy-assets`: the vendor directory may have stale files.

### Docker build fails at npm stage

Clear Docker's build cache:
```bash
docker compose build --no-cache simpleinvoices
```
