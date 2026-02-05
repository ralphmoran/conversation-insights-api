# Conversation Insights API

A Laravel-based REST API that ingests conversation data and surfaces actionable conversion insights — similar to how platforms like Patient Prism transform call data into business metrics.

## Features

- **REST API** for conversation ingestion and retrieval
- **Conversion metrics** with source attribution
- **Missed opportunity tracking**
- **Clean service-based architecture**
- **Feature tests** for core logic
- **Filtering** by source, conversion status, and date range

## Why This Matters

Healthcare and service businesses rely on understanding why leads convert or fail. This API demonstrates how structured conversation data can be transformed into insights that support better training, coaching, and operational decisions.

## Tech Stack

- Laravel 12
- PHP 8.4
- SQLite (development) / MySQL (production)
- PHPUnit for testing

## Installation

```bash
# Clone the repository
git clone <repository-url>
cd conversation-insights-api

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# (Optional) Seed demo data
php artisan db:seed
```

## API Endpoints

### Conversations

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/conversations` | List all conversations |
| POST | `/api/conversations` | Create a conversation |
| GET | `/api/conversations/{id}` | Get a single conversation |
| DELETE | `/api/conversations/{id}` | Delete a conversation |

#### Query Parameters (GET /api/conversations)
- `source` - Filter by source (google_ads, website, referral, direct, social)
- `converted` - Filter by conversion status (true/false)
- `start_date` - Filter by start date
- `end_date` - Filter by end date
- `per_page` - Pagination size (default: 15)

#### Create Conversation Request Body
```json
{
  "caller_name": "John Doe",
  "phone_number": "555-1234",
  "source": "google_ads",
  "transcript": "Hi, I'm interested in scheduling an appointment...",
  "converted": true,
  "conversion_reason": "Scheduled appointment",
  "duration_seconds": 180
}
```

### Metrics

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/metrics` | Get conversion insights |

#### Query Parameters
- `start_date` - Filter metrics by start date
- `end_date` - Filter metrics by end date

#### Example Response
```json
{
  "data": {
    "total_calls": 100,
    "total_conversions": 40,
    "missed_opportunities": 60,
    "conversion_rate": 0.4,
    "avg_duration_seconds": 245,
    "by_source": {
      "google_ads": {
        "calls": 40,
        "conversions": 20,
        "rate": 0.5,
        "avg_duration_seconds": 230
      },
      "website": {
        "calls": 30,
        "conversions": 12,
        "rate": 0.4,
        "avg_duration_seconds": 260
      }
    }
  }
}
```

## Running Tests

```bash
php artisan test
```

## Project Structure

```
app/
├── Enums/
│   └── ConversationSource.php
├── Http/
│   ├── Controllers/Api/
│   │   ├── ConversationController.php
│   │   └── MetricsController.php
│   ├── Requests/
│   │   └── StoreConversationRequest.php
│   └── Resources/
│       └── ConversationResource.php
├── Models/
│   └── Conversation.php
└── Services/
    └── MetricsService.php
```

## Development

```bash
# Start the development server
php artisan serve

# Run tests with coverage
php artisan test --coverage

# Format code
./vendor/bin/pint
```

## License

MIT
