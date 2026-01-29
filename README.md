# local_slow_queries — Slow Queries Viewer for Moodle

`local_slow_queries` is an admin-only plugin that turns your `mdl_log_queries` table into a practical UI to **find, triage and analyze slow SQL statements** executed by Moodle.

It focuses on three daily workflows:

- **List & filter** slow queries quickly (default `exectime > 3s`).
- **Open details** with SQL + parameters and a ready-to-use **ChatGPT prompt** including table schemas.
- **Use dashboards** to see patterns: top slowest, recurrence, distribution, CRON vs WEB, by qtype, and errors.

> Access is restricted to: `require_capability("moodle/site:config", context_system::instance())`

## What this plugin assumes

This plugin **does not capture queries by itself**. It assumes your environment already logs queries into:

- `mdl_log_queries` (or `{log_queries}` with Moodle prefix)

So the plugin is a **viewer + analysis helper** for an existing slow-query logging mechanism.

## Key screens

### 1) `index.php` — Slow Queries List

The main list is designed for fast triage:

- **Search** within SQL text
- Filter by:
    - **Min exec time** (`exectime >= X`, default `X=3`)
    - `qtype` (optional)
    - error-only mode (optional)
- Compact table shows:
    - **SQL preview**
    - **Backtrace caller**: the first “user-land” caller right after the `/lib/dml/` frame
    - **Execution time** (seconds)
    - **CRON flag** ✅ when `admin/cli/cron.php` is detected in backtrace
    - Timestamp (timelogged)

Clicking a row opens the detail view.

### 2) `detail.php` — Query Details (2-column view)

The detail screen is optimized to help you **reproduce and reason** about a slow query:

**Top section (row / col-6):**
- Left card:
    - Full SQL text
    - Raw `sqlparams` text as stored
- Right card:
    - SQL with parameters applied (best-effort replacement for positional `?` placeholders)

**Below:**
- A large textarea containing a **ChatGPT prompt** that includes:
    - SQL already “materialized” with parameters
    - Detected table list (FROM/JOIN/UPDATE/INTO)
    - A schema snapshot for those tables using Moodle metadata (`$DB->get_columns()`)

This prompt is intended for:
- index suggestions
- query rewrites
- hypothesis of root causes
- expected impact & risks

## Data source

The plugin reads from:

### Table: `mdl_log_queries`

Fields used by the UI:

- `id` — unique identifier
- `qtype` — query type (stored by your logger)
- `sqltext` — SQL statement text
- `sqlparams` — parameters (string representation)
- `error` — non-zero marks error
- `info` — optional extra data
- `backtrace` — string backtrace
- `exectime` — decimal seconds
- `timelogged` — unix timestamp

Index suggestions in the `install.xml` are focused on viewer performance:
- `timelogged`, `exectime`, `qtype`, `error`

## Backtrace “caller” logic

The list view shows a simplified “caller” derived from backtrace:

1. Find the first frame containing `/lib/dml/`
2. Show the **next frame** (usually the Moodle component that invoked the DB layer)
3. Shorten long absolute paths to keep it readable in a table

This is intentionally pragmatic: it helps you quickly identify whether the query comes from
completion, search indexing, forum reports, etc.

## CRON detection

A query is considered “CRON” when its backtrace contains:

- `/admin/cli/cron.php`

The UI then:
- sets the CRON column ✅
- adds a CRON badge

## Parameter handling

`sqlparams` in production can vary depending on the logger implementation.
This plugin attempts to parse common formats:

- JSON arrays/objects
- PHP `serialize()` format
- “var_export-like” output (example: `array ( 0 => 4, 1 => 50, )`)

Then it applies parameters to SQL by replacing positional `?` placeholders sequentially.

Notes:
- This is a **best-effort representation** for analysis and reproduction.
- It is not intended to be a perfect SQL reconstitution for every edge case.

## Settings

The plugin provides lightweight settings to tune the UI:

- **Default minimum execution time** (`minexectime`)
- **Records per page** (`perpage`)
- **SQL preview length** (`previewlen`)

These affect list/report defaults and usability, not the underlying data.

## UX & UI choices

This plugin intentionally keeps a **light and readable look**:

- Cards with rounded corners
- Soft shadows
- Badges for quick categorization (CRON / WEB / ERROR)
- Progress bars for distribution/share reports

It relies on Moodle’s loaded Bootstrap styles and adds minimal SCSS.

## Security model

- Pages are accessible only to users with:
    - `moodle/site:config` in `context_system`

This is critical because the plugin can reveal:
- raw SQL
- parameters (potentially containing sensitive values)
- file paths in backtraces

## Typical usage patterns

### Ops triage
- Open `index.php`
- Filter `exectime >= 10`
- Identify CRON-heavy spikes or web-timeouts
- Open details, extract candidate indexes

### Regression monitoring
- Check last 7 days volume
- Compare max/avg trends weekly
- Identify new callers (components) from backtrace changes
