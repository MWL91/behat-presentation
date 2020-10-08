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

## Stage 2 - Writing first scenario

Now, when we have our environment ready to go, we can start with initialize Behat.

```
vendor/bin/behat --init
```

This will create out first FeatureContext class, that we will use, to test our code.

We will start with extend FeatureContext class with Mink extension.

```php
use Behat\MinkExtension\Context;

class FeatureContext extends MinkContext implements Context
```

Now, when we run `vendor/bin/behat`, Bahat will tell us, that we have No scenarios and No stepss - time to create one.

### *Stage 2.1* - Writing first feature

In directory `/features` create file called `rentacar.feature` with contents below:

```gherkin
Feature: Rent a car
  In order to rent a car
  As a customer
  I need to be able to order a car
```

This describes what we are going to do. We will write simple solution for rent cars.

Now set some rules:

```gherkin
  Rule:
  - Customer have to have at least 18yo
  - Customer may rent one car at a time
  - Customer may rent another car if return current
  - There are limited numbers of cars, when it's rented customer may not rent it
```

As you can see - it doesn't look like programming yet. It's more like a talk with customer - and this is why BDD it's so great! Let's try to write first story.

### *Stage 2.2* - Writing first story

Let's add to our file following scenario:

```gherkin
  Scenario: I can rent a car if i have 18yo
    Given there is a "Tabaluga Dragon", that was born in 1997-10-04
    And there is a "Minion", that was born in 2015-06-26
    When "Tabaluga Dragon", wants to rent a car
    And "Minion", wants to rent a car
    Then "Tabaluga Dragon" will be able to rent a car
    But "Minion" will be not able to rent a car
```

Now we have some business logic in our feature. Important part here, is to write the same sentences, for example:

```
there is a "Tabaluga Dragon", that was born in 1997-10-04
there is a "Minion", that was born in 2015-06-26
```

will be transformed into the same test method, but if we would write

```
there is a "Tabaluga Dragon", that was born in 1997-10-04
there born in 2015-06-26 "Minion" 
```

then, we will have 2 separate methods - we don't want that.

### *Stage 2.3* - gherkin language

Language that we use is called `"Gherkin"`. You can see the syntax using `vendor/bin/behat --story-syntax`.

We don't have to use even English for that.
Behat allows us to use for example polish syntax, you can view it using `vendor/bin/behat --story-syntax --lang=pl`.

What's funny, you can event write in Pirate English `vendor/bin/behat --story-syntax --lang=en-pirate`

```
Ahoy matey!: Rent a car
  In order to rent a car
  As a customer
  I need to be able to order a car

  Yo-ho-ho:
  - Customer have to have at least 18yo
  - Customer may rent one car at a time
  - Customer may rent another car if return current
  - There are limited numbers of cars, when it's rented customer may not rent it

  Shiver me timbers: I can rent a car if i have 18yo
    Gangway! there is a "Tabaluga Dragon", that was born in 1997-10-04
    Aye there is a "Minion", that was born in 2015-06-26
    Blimey! "Tabaluga Dragon", wants to rent a car
    Aye "Minion", wants to rent a car
    Let go and haul "Tabaluga Dragon" will be able to rent a car
    Avast! "Minion" will be not able to rent a car
```

Now we have our first scenario, so it's time to write some code!