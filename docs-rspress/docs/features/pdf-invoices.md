# Professional PDF Invoices

Generate beautiful, professional PDF invoices with one click. Every invoice can be downloaded, printed, or emailed as a PDF.

## PDF Templates

Choose from multiple built-in templates:

| Template | Style |
|----------|-------|
| **Default** | Clean, classic invoice layout |
| **Modern** | Contemporary design with colour accents |
| **Tabler** | Modern UI-inspired layout matching the admin theme |

Custom templates can be added to `templates/invoices/`.

## PDF Features

- **Company logo**: Upload your logo per biller (supports S3 storage)
- **Invoice details**: All fields: items, quantities, prices, tax, totals
- **Payment instructions**: Bank details, payment methods, online payment links
- **Multi-language**: Invoices render in the preference language
- **Multi-currency**: Correct currency symbols and locale formatting
- **Page sizes**: Configurable margins, orientation, and paper size

## PDF Engine

Uses HTML2PDF (based on TCPDF) which converts HTML/CSS to PDF. Templates are Blade views rendered to HTML, then converted to PDF. This means:

- Templates use standard HTML/CSS
- Easy to customise without learning a PDF-specific API
- Consistent rendering between web view and PDF

## S3 Logo Storage

Biller logos can be stored on S3-compatible object storage:

- AWS S3
- MinIO (self-hosted)
- Garage (self-hosted)
- Any S3-compatible service

Configure under **Settings → System Preferences**.
