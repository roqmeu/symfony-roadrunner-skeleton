# Symfony RoadRunner skeleton

Features:
- Uses Angie (Nginx) to distribute static content;
- Uses the Symfony backend via the Angie -> RoadRunner -> Symfony chain;
- Uses the Symfony backend via the RoadRunner -> Symfony chain;
- Sends Symfony logs to RoadRunner;
- Works with RoadRunner KV via Symfony Cache, which lets you work with a pool of cache connections;
- Works with RoadRunner Jobs via Symfony Messenger, which lets you work with a pool of queues connections;
- Works with Symfony Scheduler instead of cron.
