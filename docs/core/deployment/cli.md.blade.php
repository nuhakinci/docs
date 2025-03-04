---
title: Deployment - Core CLI Commands
---

# Core CLI Commands

<x-alert type="success">
View all available SXP CLI commands **[here](/docs/core/development/cli/available)** or learn how to [create your own](/docs/core/development/cli/create).
</x-alert>

Solar Core comes packaged with a robust command line interface (CLI) which is an essential tool that enables any node operator to update, manage, or monitor their node installation without the need for external programs.

View the installed version of Core and a list of available CLI commands by typing the following in your terminal:

```shell
solar
```

## Core

This series of tests will test the `core` process commands. The 'core' process will run both your relay and your forger in a single process. You can start the core process with the following command:

```shell
solar core:start
```

- Run `solar core:status` to ensure that the core to check the status of your process.

<x-alert type="info">
If your `delegate.json` file doesn't contain a BIP38 or BIP39 passphrase, then an error should be displayed.
</x-alert>

You can view your `core` process logs with the following command:

```shell
solar core:log
```

- Check the logs to ensure that the process has started correctly

<x-alert type="info">
If your `core` process isn't running, then an error should be displayed. You can check the status of your `core` process with `solar core:status`
</x-alert>

You can restart your `core` process with the following command:

```shell
solar core:restart
```

- Check your `core` logs to ensure that the process has restarted successfully

<x-alert type="info">
If your `core` process isn't running, then an error should be displayed. You can check the status of your `core` process with `solar core:status`
</x-alert>

You can stop your `core` process with the following command.

```shell
solar core:stop
```

<x-alert type="info">
If your `core` process isn't running, then an error should be displayed. You can check the status of your `core` process with `solar core:status`
</x-alert>

You can run the `core` process in foreground with the following command

```shell
solar core:run
```

- Exit the process and ensure that it has stopped from running with `solar core:status`

## Relay

This series of tests will test the `relay' process commands. Solar Core allows you to run a relay as a single process without the forger enabled with the following command:

```shell
solar relay:start
```

- Check that the process is running with `solar relay:status`
- Check the `relay` logs with `solar relay:log` to ensure the process has started correctly.

You can restart your relay process with the following command

```shell
solar relay:restart
```

- Check that the process is running with `solar relay:status`
- Check the `relay` logs with `solar relay:log` to ensure the process has started correctly.

<x-alert type="info">
If your `relay` process isn't running, then an error should be displayed when running the `solar relay:restart` command.
</x-alert>

With 3.0, you can now share your relay process via [ngrok](https://ngrok.com/). You can do this with the following command:

```shell
solar relay:share
```

- Check that you can successfully resolve to an API endpoint with the ngrok URL provided.

You can stop your `relay` process with the following command.

```shell
solar relay:stop
```

<x-alert type="info">
If your `relay` process isn't running, then an error should be displayed. You can check the status of your `relay` process with `solar relay:status`
</x-alert>

You can run the `relay` process in foreground with the following command

```shell
solar relay:run
```

- Exit the process and ensure that it has stopped from running with `solar relay:status`

## Forger

This series of tests will test the `forger` process commands. The 'forger' process will run your forger in a single process independently of the relay. You can start the core process with the following command:

```shell
solar forger:start
```

- Check the status of your forger with `solar forger:status`
- Check the logs to ensure your forger has started correctly and loaded the correct delegate with `solar forger:log`

<x-alert type="info">
If your `delegate.json` file doesn't contain a BIP38 or BIP39 passphrase, then an error should be displayed.
</x-alert>

You can restart your forger with the following command:

```shell
solar forger:restart
```

- Check the status of your forger with `solar forger:status`
- Check the logs to ensure your forger has restarted correctly and loaded the correct delegate with `solar forger:log`

You can stop your forger process with the following command:

```shell
solar forger:stop
```

- Check the status of your forger with `solar forger:status`

You can run the `forger` process in foreground with the following command

```shell
solar forger:run
```

- Exit the process and ensure that it has stopped from running with `solar forger:status`

## Config

Solar Core CLI allows you to configure your Core environment (CLI, database & forger) by using the `config` commands. For the purpose of this testing, we will just test the database and forger `config` commands.
<x-alert type="warning">
Completing the following CLI configuration commands will overwrite parameters in your `.env` and `delegates.json` file located in `~/.config/solar-core/devnet/`. **Please backup your current files before progressing if required.**
</x-alert>

To configure your database, run the following command:

```shell
solar config:database
```

- You should now be prompted to configure a database.
- Configure up your database with your own custom values
- Once completed, run `less .config/solar-core/devnet/.env` and check your database parameters have now updated correctly.

To configure your forger, run the following command:

```shell
solar config:forger
```

- Follow the onscreen prompts to complete a BIP39 configuration
- Once complete, run `less .config/solar-core/devnet/delegates.json` and check your delegate passphrase is updated correctly.
- Run the command again and complete a BIP38 configuration
- Once complete, start your forger process and ensure the correct delegate is loaded after entering password.

## Snapshots

Core CLI allows you to dump, restore, rollback, truncate and verify your database. This series of tests will utilize the CLI to perform actions with your database.

To create a full snapshot of your database, you can run the following command:

```shell
solar snapshot:dump
```

- Ensure the process completes without errors.
- Make a note of the filename of your snapshot. This will look like `1-5235743`

Once you have taken a snapshot of your database, you can restore the snapshot with the following command:

```shell
solar snapshot:restore --blocks snapshot

// example:
solar snapshot:restore --blocks 1-5235743
```

- Ensure the snapshot restores without errors
- Start your relay and ensure your node begins to sync to the network correctly

To rollback your node to a specific block height you can use the following command

```shell
solar snapshot:rollback --height=blockHeight

// example
solar snapshot:rollback --height=5641500
```

- Rollback your node to a block height of your choosing and ensure the process completes successfully
- Start your relay and check that your node will now begin syncing from the specified block height that you rolled back to
