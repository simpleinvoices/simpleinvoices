---
pageType: home
link-rss: blog
hero:
  name: Simple Invoices
  text: Free, open-source invoicing since 2005
  tagline: Create and manage invoices, clients, and payments on your own server
  image:
    src: /hero-invoice.svg
    alt: Simple Invoices interface
  actions:
    - theme: brand
      text: Quick Start
      link: /guide/installation
    - theme: alt
      text: Features
      link: /features/free-open-source
    - theme: alt
      text: GitHub
      link: https://github.com/simpleinvoices/simpleinvoices
features:
  - title: Multi-Language
    details: 41+ languages with per-invoice language settings. Your admin team works in English while customers see invoices in their native language.
    icon: 🌐
    link: /features/multi-language
  - title: Multi-Currency
    details: 40+ preset currencies plus custom currencies. Each invoice can use a different currency with locale-aware formatting.
    icon: 💱
    link: /features/multi-currency
  - title: Free and Open Source
    details: GPLv3 licensed. No license fees, no per-user charges. Full source code available. Your data stays in your database on your server.
    icon: 📖
    link: /features/free-open-source
  - title: Multi-Domain SaaS
    details: Run unlimited isolated invoice domains from one installation. Perfect for SaaS platforms, multi-brand orgs, and regional offices.
    icon: 🏢
    link: /features/multi-domain
  - title: Professional PDFs
    details: Generate beautiful PDF invoices with your logo. Multiple templates available. Email invoices with PDF attachments automatically.
    icon: 📄
    link: /features/pdf-invoices
  - title: Online Payments
    details: Accept payments via Stripe, PayPal, Mollie, Coinbase, and more. Nine integrated gateways with per-invoice configuration.
    icon: 💳
    link: /features/online-payments
  - title: Web-Based and Mobile
    details: Runs in any browser. Responsive design works on desktop, tablet, and phone. No app installation required.
    icon: 📱
    link: /features/web-based
  - title: Multi-Database
    details: MySQL, MariaDB, PostgreSQL, or SQLite. Switch between engines. SQLite needs zero setup — the entire database is one file.
    icon: 🗄️
    link: /features/multi-database
  - title: Recurring Invoices
    details: Automate repeat billing. Daily, weekly, monthly, or yearly recurrence. Auto-email to customers with PDF attachments.
    icon: 🔄
    link: /features/recurring-invoices
---

import { Tab, Tabs } from '@rspress/core/theme';

<div style={{ maxWidth: '600px', margin: '40px auto', padding: '0 20px' }}>
  <h3 style={{ textAlign: 'center', marginBottom: '20px' }}>Install the Package</h3>
  
  <Tabs groupId="package-manager">
    <Tab label="npm">
      ```bash
      npm install my-awesome-package
      ```
    </Tab>
    <Tab label="pnpm">
      ```bash
      pnpm add my-awesome-package
      ```
    </Tab>
    <Tab label="yarn">
      ```bash
      yarn add my-awesome-package
      ```
    </Tab>
    <Tab label="bun">
      ```bash
      bun add my-awesome-package
      ```
    </Tab>
  </Tabs>
</div>
