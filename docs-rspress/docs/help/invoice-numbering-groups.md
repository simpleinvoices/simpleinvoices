# Invoice Numbering Groups

Numbering groups control how auto-incrementing invoice IDs are shared (or separated) across different Invoice Preferences.

## The Concept

Every Invoice Preference belongs to a **Numbering Group** (`index_group` in the database). The counter that generates the next Invoice ID is tracked **per group**, not per preference. This lets you decide which preferences share a numbering sequence and which have their own.

## How Groups Work

- **Preferences in the SAME group** → share one sequential counter. Invoices created with any preference in that group will increment the same counter.
- **Preferences in DIFFERENT groups** → each group has its own independent counter, starting from 1.
- **Preferences with NO group** (left blank) → automatically get their own unique group (equal to their `pref_id`), so they have an independent counter.

## Example: Separate Groups (Independent Counters)

You create 3 preferences, each with a different numbering group:

| Preference | Group | Prefix | IDs Generated |
|:---|:---|:---|:---|
| Invoice | Group 1 | `INV-` | INV-1, INV-2, INV-3, INV-4... |
| Quote | Group 2 | `QTE-` | QTE-1, QTE-2, QTE-3... |
| Receipt | Group 3 | `REC-` | REC-1, REC-2, REC-3... |

Each group has its own counter. Invoice #1, Quote #1, and Receipt #1 all exist independently.

## Example: Shared Group (Single Counter)

You create 2 preferences in the SAME numbering group:

| Preference | Group | Prefix | IDs Generated (interleaved) |
|:---|:---|:---|:---|
| Invoice | Group 1 | `INV-` | INV-1, REC-2, INV-3, REC-4, INV-5... |
| Receipt | Group 1 | `REC-` | |

Since both preferences use Group 1, they share one counter. The first invoice gets ID 1, the first receipt gets ID 2, the next invoice gets ID 3, and so on. The IDs are interleaved based on creation order.

## Example: Combined with Biller Prefix

Billers can also add a prefix, which is prepended before everything else:

| Biller | Biller Prefix | Preference | Group | IDs Generated |
|:---|:---|:---|:---|:---|
| NY Office | `NY-` | Invoice (Group 1) | 1 | NY-INV-0001, NY-INV-0002... |
| CA Office | `CA-` | Invoice (Group 1) | 1 | CA-INV-0003, CA-INV-0004... |

Even with different biller prefixes, both billers using the same preference group share the counter.

| Biller | Biller Prefix | Preference | Group | IDs Generated |
|:---|:---|:---|:---|:---|
| NY Office | `NY-` | Invoice (Group 1) | 1 | NY-INV-0001, NY-INV-0002... |
| NY Office | `NY-` | Quote (Group 2) | 2 | NY-QTE-0001, NY-QTE-0002... |

Different groups = independent counters, even for the same biller.

## When to Use Shared vs Separate Groups

### Use Separate Groups When
- You want Invoices, Quotes, and Receipts to each have their own sequential numbering (gaps don't matter).
- You need to meet legal/accounting requirements where each document type must have a distinct, unbroken sequence.
- Different document types serve completely different purposes.

### Use Shared Groups When
- You want all document types to share a single chronological numbering sequence.
- Simpler bookkeeping: you just care about the order documents were created, not which type.
- You want to avoid duplicate IDs across document types.

## Managing Numbering Groups

1. Go to **Settings → Invoice Preferences**
2. Edit any preference
3. Click the **Invoice Numbering** tab
4. Select a **Numbering Group** from the dropdown:
   - Choose another preference to share its group
   - Choose the current preference to keep its own group
   - Leave the preference's group unchanged to retain a unique group

The **Next Invoice Number** display shows what the next ID would be for the currently selected group.

## Changing Groups on Existing Preferences

When you change a preference's numbering group, be aware:
- Future invoices created with that preference will use the new group's counter.
- Existing invoices are NOT renumbered: their original `index_id` values remain unchanged.
- The old group's counter is NOT reset or decremented.

## Technical Details

- The counter is stored in the `si_index` table with `node = 'invoice'` and `sub_node = <index_group>`.
- Each domain can have its own separate counters (per `domain_id`).
- The counter is only incremented when an invoice is actually saved: not during preview.
