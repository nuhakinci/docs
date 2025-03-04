---
title: Getting Started
---

# Getting Started

The Manager API allows you to gather common node information, access logs, control running processes and handle ledger snapshots on demand.
Requests should contain valid JSON RPC 2.0 data which are transformed over HTTP / HTTPS protocol using GET method.

<x-alert type="warning">
All HTTP/HTTPS requests have to be sent with the `Content-Type: application/vnd.api+json` header. If the header is not present, it will result in malformed responses or request rejections.
</x-alert>

## Alias

`manager`

## Installation

The Manager API is official Solar Network plugin, but it does not come with the default installation. You can install plugin manually by running:

```bash
solar plugin:install @arkecosystem/core-manager
```

Check if plugin is installed successfully, by using following command:

```bash
solar help
```

You should see `manager` section under available commands.

<x-alert type="info">
Core plugins are installed per network configuration, which allows installation of different plugins and plugin versions per network. Use `--network=mainnet/devnet/testnet` flag on each command if you are using more than one network configuration.
</x-alert>

## Configuration

The Manager API is not configured by default and you should configure it manually by customizing settings in `app.json` and `.env` file. To simplify this process you can perform basic configuration through the interactive process.

```bash
solar manager:config
```

<x-alert type="warning">
We strongly recommend setting basic authentication using **basic** Argon2Id hashing method with custom secret and using **HTTPS** access only.
</x-alert>

Additionally, you can customize API specific settings in .env or app.json file.

### Argon2Id Basic Authentication

It is recommended to make configuration changes for defining user access and IP whitelisting within your app.json file.

```javascript
{
    "package": "@arkecosystem/core-manager",
    "options": {
        "plugins": {
            "whitelist": ["127.0.0.1"],
            "basicAuthentication": {
                "enabled": true,
                "secret": "secret",
                "users": [
                    {
                        "username": "username",
                        "password": "$argon2id$v=19$m=4096,t=3,p=1$NiGA5Cy5vFWTxhBaZMG/3Q$TwEFlzTuIB0fDy+qozEas+GzEiBcLRkm5F+/ClVRCDY"
                    }
                ]
            }
        }
    }
}
```

The `whitelist` property is an `Array` consisting of IP addresses that you allow to make connections to the manager API.
By default, only local access to the webhook API is allowed. This means that if you want to expose your webhook API to the outside, you'll need to explicitly add the IP addresses that you will use to this list (recommended approach).
It is also possible to use wildcards to indicate a range of IPs (e.g. `"12.34.56.*"`) or even to allow everyone (e.g. `"*"`) (not recommended).

### Token authentication

Alternatively, instead of using basic authentication, token authentication can be enabled (not recommend).

```javascript
{
    "package": "@arkecosystem/core-manager",
    "options": {
        "plugins": {
            "whitelist": ["127.0.0.1"],
            "tokenAuthentication": {
                "enabled": true,
                "token": "secret_token"
            }
        }
    }
}
```

Include Authorization header into request with content: Bearer <secret_token>.

### Network related environment variables

It is recommended to make configuration changes to these options by working with your `.env` file and the corresponding variables:

| Variable | Description | Type | Default |
| :--- | :--- | :---: | :---: |
| CORE_MANAGER_PUBLIC_IP | Determines core managers public IP. | string | `undefined` |
| CORE_MANAGER_DISABLED | Enables or disables the manager API plugin | boolean | `false` |
| CORE_MANAGER_HOST | The host to expose the API on | string | `"0.0.0.0"` |
| CORE_MANAGER_PORT | The API port on which the plugin will listen | integer | `4005` |
| CORE_MANAGER_SSL | Enables or disables the manager API plugin using SSL. | boolean | `false` |
| CORE_MANAGER_SSL_HOST | The host to expose the HTTPS API on | string | `"0.0.0.0"` |
| CORE_MANAGER_SSL_PORT | The host to expose the HTTPS API on | port | `8445` |
| CORE_MANAGER_SSL_KEY | Determines where SSL key is located. | port | `8445` |
| CORE_MANAGER_SSL_CERT | Determines where SSL certificate is located. | port | `8445` |
| CORE_MANAGER_ARCHIVE_FORMAT | Determines which format is used for storing downloaded logs (`zip` or `gz`). | string | `zip` |

## Requirements

Manager API obtains some of the required data from running either a `core` **or** `relay` & `forger` process.
<x-alert type="info">
Be aware that the HTTP server is running on a node instance and that all processes are run with `solar [process_name]:start` option.
Using this method, the process is run as a PM2 process, which is necessary because PM2 IPC is used for getting some data required by manager API. Read more about starting processes in our [Core CLI Documentation](/docs/core/deployment/cli).
</x-alert>

## Store events and logs

When package is used on `core` **or** `relay` & `forger` processes it can provide additional functionality for storing logs and events in a local sqlite database.
Stored data can be queried from dedicated JsonRPC calls. Additionally logs can be achieved and downloaded using customized filtering.
This functionality is enabled only when package is added into `app.json` settings file in desired process plugins.

```json
"core": {
    "plugins": [
        {
            "package": "@arkecosystem/core-logger-pino"
        },
        {
            "package": "@arkecosystem/core-manager"
        },
        {
            "package": "@arkecosystem/core-state"
        },
        ...
    ]
},
```

<x-alert type="warning">
@arkecosystem/core-manager package should be included right after @arkecosystem/core-logger-pino package for optimal performance.
</x-alert>

### Options

Core manager offers variety of options from filtering stored event types, enabling and disabling logs and event storing to defining length of logs history in days.
Options can be set via ENV variables or under package options in `app.json` file.

<x-alert type="warning">
Don't use event watching (`CORE_WATCHER_ENABLED` flag) in production or mainnet network. This functionality is added only for debugging purposes.
</x-alert>

### Watcher related environment variables

It is recommended to make configuration changes to these options by working with your `.env` file and the corresponding variables:

| Variable | Description | Type | Default |
| :--- | :--- | :---: | :---: |
| CORE_WATCH_LOGS_DISABLED | Disable storing logs. | boolean | `false` |
| CORE_WATCHER_ENABLED | Enables or disables the event watcher | boolean | `false` |
| CORE_WATCH_BLOCKS_DISABLED | Disable storing block related events. | boolean | `false` |
| CORE_WATCH_ERRORS_DISABLED | Disable storing error related events. | boolean | `false` |
| CORE_WATCH_QUERIES_DISABLED | Disable storing query related events. | boolean | `false` |
| CORE_WATCH_QUEUES_DISABLED | Disable storing queue related events. | boolean | `false` |
| CORE_WATCH_ROUNDS_DISABLED | Disable storing rounds related events. | boolean | `false` |
| CORE_WATCH_SCHEDULES_DISABLED | Disable storing schedule related events. | boolean | `false` |
| CORE_WATCH_TRANSACTIONS_DISABLED | Disable storing transaction related events. | boolean | `false` |
| CORE_WATCH_WALLETS_DISABLED | Disable storing wallet related events. | boolean | `false` |
| CORE_WATCH_WEBHOOKS_DISABLED | Disable storing webhooks related events. | boolean | `false` |

## Final checks

After making changes to the manager API configuration, you will need to restart your manager process for the changes to take effect.
If you want to check whether your manager API is running, you should pay attention to the startup messages in the logs of your relay.
It will print a line similar to `INFO : Public JSON-RPC API (HTTP) Server started at http://0.0.0.0:4005` when it has successfully started the manager API.

## Security

If you discover a security vulnerability within this package, please send an e-mail to [security@solar.io](mailto:security@solar.io). All security vulnerabilities will be promptly addressed.
