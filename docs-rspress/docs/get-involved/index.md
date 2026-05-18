# Get Involved

Simple Invoices is **free and open-source** (GPLv3). We welcome contributions of all kinds: code, translations, documentation, testing, and community support.

## Where We Are

Simple Invoices is hosted on **four platforms**. You can browse the code, submit issues, open pull requests, and contribute on whichever platform you prefer: they all mirror the same codebase.

| Platform | URL |
|----------|-----|
| **GitHub** | [github.com/simpleinvoices/simpleinvoices](https://github.com/simpleinvoices/simpleinvoices) |
| **Codeberg** | [codeberg.org/simpleinvoices](https://codeberg.org/simpleinvoices) |
| **SourceHut** | [sr.ht/~simpleinvoices](https://sr.ht/~simpleinvoices) |
| **Self-hosted (Forgejo)** | [git.simpleinvoices.org](https://git.simpleinvoices.org) |

Issues, pull requests, and discussions are welcome on any platform. Use the one that aligns with your workflow and values.

## How to Contribute

### 🌐 Update Translations

Simple Invoices supports **41+ languages** and always needs help keeping translations up to date. No coding required: just your language skills.

1. Find the translation files in `lang/[language_code]/lang.php`
2. Look for untranslated strings (marked with `//0` comments)
3. Translate the English string to your language
4. Submit a pull request or send the updated file

### 🐛 Report & Fix Bugs

Found a bug? Here's how to help:

1. **Report a bug**: Open an issue on any of our platforms. Describe what happened, what you expected, and steps to reproduce.
2. **Fix a bug**: Look for issues labeled `bug` or `good first issue`
3. Fork the repo, create a branch, fix it, and submit a PR

### ✨ Submit PRs for Features

1. **Discuss first**: Open an issue to discuss your idea before coding
2. **Fork & branch**: `git checkout -b feature/my-feature`
3. **Code**: Follow the existing code style and conventions
4. **Test**: Make sure existing functionality still works
5. **Submit PR**: Include a clear description of what you changed and why

### 📖 Write Documentation, Guides & Help Pages

The documentation site is built with **Rspress**: a modern static site generator. All docs live as Markdown files in the `docs-rspress/docs/` directory.

**What you can contribute:**

- Fix typos, clarify instructions, add examples
- Write new guide pages, help topics, or feature pages
- Update outdated information
- Improve the sidebar structure (`_meta.json` files)
- Translate pages into new languages

**How the docs are structured:**

```
docs-rspress/docs/
├── index.md              # Homepage
├── _nav.json             # Top navigation bar
├── guide/                # Getting started & user guides
├── features/             # Feature highlight pages
├── help/                 # Context-sensitive help topics
├── blog/                 # Blog posts (with RSS)
└── get-involved/         # Contributing & license
```

**Option 1: Edit directly on the web (easiest)**

No setup required. Just edit Markdown files right in your browser:

1. Go to any of our repositories: [GitHub](https://github.com/simpleinvoices/simpleinvoices), [Codeberg](https://codeberg.org/simpleinvoices), [SourceHut](https://sr.ht/~simpleinvoices), or [Forgejo](https://git.simpleinvoices.org)
2. Navigate to `docs-rspress/docs/` and find the page you want to update
3. Click **Edit** (or the pencil icon), make your changes, and submit a pull request

**Option 2: Build locally (for bigger changes)**

Preview your changes before submitting:

```bash
# Clone the repo
git clone https://github.com/simpleinvoices/simpleinvoices.git
cd simpleinvoices/docs-rspress

# Install dependencies
npm install

# Start dev server (live preview at localhost:3000)
npm run dev

# Make your edits, see changes instantly, then submit a PR
```

**Adding a new page:**

1. Create a `.md` file in the right directory (`guide/`, `help/`, `features/`, etc.)
2. Add it to the `_meta.json` sidebar file in that directory to control ordering
3. For a new top-level page, add a nav entry in `_nav.json`

**Page format:**

Every page is standard Markdown with optional frontmatter:

```markdown
---
title: My Page Title
---

# My Page Title

Content goes here. Use **bold**, *italic*, `code`, links, tables, and more.

## Sub-heading

- Bullet lists
- With items
```

Changes to docs are reviewed and merged like code changes: submit a PR on whichever platform you prefer.

### 🧪 Test New Releases

Help make Simple Invoices more stable by testing on different setups:

- **Devices**: Desktop, tablet, mobile. Report layout or usability issues.
- **Browsers**: Chrome, Firefox, Safari, Edge.
- **PHP versions**: 8.1, 8.2, 8.3, 8.4.
- **Databases**: MySQL, MariaDB, PostgreSQL, SQLite.
- **Docker**: Pull the latest image and verify everything works.
- **Report what you find**: Open an issue with details about your setup and what went wrong.

## More

- **[Development Setup →](/get-involved/dev-setup)**: Project structure, prerequisites, conventions
- **[License (GPLv3) →](/get-involved/license)**: Full license details and what it means
