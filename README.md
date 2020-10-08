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

After installation, we also need to configure our behat with behat.yml file. Create `behat.yml` file with contents below:

```
default:
  extensions:
    Laracasts\Behat:
      env_path: .env.testing
    Behat\MinkExtension:
      default_session: laravel
      laravel: ~
```

Our testing env will be .env.testing. We also will use laravel session, in case of using `Mink Extention` that allows us to do browser tests.

```
cp .env.example .env.test
```

Also in our test we want to use sqlite database, so we will change database config to:

```
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
#DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

And create a new database.sqlite in database directory.

```
touch database/database.sqlite
```

Now we are ready to go with our environment using behat.

## Stage 2 - first scenario