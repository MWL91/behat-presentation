# Behavior Driven Development in practice - Behat in Laravel
Behat on Laravel playground.

## Stage 1 - BDD Setup

Install laravel like usually, for example using:

```
composer create-project --prefer-dist laravel/laravel behat-presentation
```

There is one library, that supports Behat on Laravel, and it's not supported anymore.

For start working with behat, you need to first fix `laracasts/behat-laravel-extension`. 

To do that, add to composer file following repository, that contains fix fork.

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/MWL91/Behat-Laravel-Extension"
    }
]
```

Now you are able to add required dev dependencies using:

```
composer require behat/behat behat/mink friends-of-behat/mink-extension laracasts/behat-laravel-extension:"dev-master as 1.1" --dev
```

## Stage 2 - first scenario