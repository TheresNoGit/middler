# middler
middler is a quick and dumb bit of middleware so that GitHub Webhooks can talk to Discord Webhooks without me turning to alcohol again.

## Config
```json
{
    "allowed_regex": "regex_for_repo_names",
    "discord_webhooks": {
        "feed_name": "webhook_url"
    },
    "coded_page": {
        "pager_name": "role_id"
    },
    "repos": {
        "repo_name": {
            "pager": "pager_name",
            "feeds": ["array", "of", "feed_name"]
        }
    }
}
```
### allowed_regex
The `allowed_regex` ensures your middler doesn't respond to any webhook events where the repo *doesn't match the regex*. The regex entry here **must** include the delimiting `/`s.

#### Example
```json
"allowed_regex": "/yuuuup/"
```

### discord_webhooks
These are where you define the webhook URLs for your discord channels. The `feed_name` doesn't need to match the channel name - it's just an identifier.

#### Example
```json
"discord_webhooks": {
    "best_team": "https://discord.com/api/webhooks/etcetc",
    "not_so_good": "https://discord.com/api/webhooks/etcetc"
}
```

### coded_page
These are discord role IDs which can be set (below) to be pinged every time a certain repo is updated.

#### Example
```json
"coded_page": {
    "deployment": "123123123123"
}
```

### repos
And finally, this is where you configure the repos. The `repo_name` must match the {org}/{repo}.

#### Example
```json
"repos": {
    "TheresNoGit/middler": {
        "pager": "deployment",
        "feeds": ["best_team", "not_so_good"]
    }
}
```