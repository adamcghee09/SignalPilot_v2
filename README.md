# SignalPilot

Production-ready PHP 8.2 + MySQL paper-trading application with interchangeable broker, market-data, and AI providers.

## Upload install
1. Upload repository contents to `public_html/signalpilot/`.
2. Visit `https://adam.mcghee.llc/signalpilot/install.php`.
3. Enter MySQL host, database, user, password, and admin credentials.
4. The installer creates schema, storage folders, default settings, strategies, and watchlist, writes `config/config.php`, then disables itself.

## Cron
Configure:
```bash
php public_html/signalpilot/cron/run_engine.php
php public_html/signalpilot/cron/process_orders.php
php public_html/signalpilot/cron/daily_report.php
php public_html/signalpilot/cron/weekly_report.php
```

## Architecture
Trading code depends only on interfaces: `BrokerInterface`, `MarketDataProviderInterface`, and `AIProviderInterface`. Change providers in Admin > Settings without code changes. Paper trading is complete; real brokers are guarded adapters ready for API integration.
