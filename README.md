# Investors API

Laravel REST API for importing investor data and reporting investor statistics.

## API

| Method | Endpoint                                     | Description                                  |
|--------|----------------------------------------------|----------------------------------------------|
| `POST` | `/api/investors/import`                      | Import investor CSV data                     |
| `GET`  | `/api/investors`                             | Stream all unique investor summaries as JSON |
| `GET`  | `/api/investors/exports/csv`                 | Stream investor summaries as CSV             |
| `GET`  | `/api/investors/statistics/average-age`      | Average investor age                         |
| `GET`  | `/api/investments/statistics/average-amount` | Average investment amount                    |
| `GET`  | `/api/investments/statistics/total`          | Total investment count                       |

### Import CSV

Upload a CSV using the `file` multipart field:

```bash
curl -X POST http://localhost:8000/api/investors/import \
  -H 'Accept: application/json' \
  -F 'file=@investors_with_dates.csv'
```

Required header for csv:

```csv
investor_id,name,age,investment_amount,investment_date
```

Example row:

```csv
1001,Daniel Nelson,28,328085.43,13-11-2024
```

Successful response:

```json
{
    "imported_rows": 1
}
```

Import behavior:

- Upload, headers, fields, numeric values, and dates are validated.
- Files are streamed and written in configurable batches.
- Investors are upserted by `investor_id`.
- Investments are upserted by `(investor_id, investment_date)`.
- CSV data is the source of truth and updates matching database records.
- Duplicate `(investor_id, investment_date)` rows inside one CSV are rejected.
- Any validation failure rolls back the whole import.

## Architecture

- Form Requests handle HTTP upload validation.
- Import readers map source records into typed DTOs.
- Import contracts allow future import types i.e. Excel.
- Bulk writers perform chunked database upserts inside a transaction.
- Query contracts isolate reporting and investor summary access.
- API Resources and exporters share investor summary DTOs.
- JSON and CSV responses stream records to keep memory usage bounded.

## Database design

`investors.investor_id` is the primary key, matching the task specification, however I would prefer an internal auto
incrementing id and keep this as a unique "external" key.
`investments.investor_id` references it. A unique index on
`(investor_id, investment_date)` enforces one investment per investor per date.

## Future improvements

- Queue large imports and expose progress.
- Improved validation, currently throws on failure but for 10k rows and 7k being invalid that's 7k fixes then attempts
  to re-import.
- Filtering and pagination on the investors endpoint. Ran import for 100k rows and the full fetch took ~5s to stream
- Minor units on monetary values for better accuracy and allows for future expansion to other countries which do not have 2 decimal places
