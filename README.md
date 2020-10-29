# Behavior Driven Development in practice - Behat in Laravel
Behat on Laravel playground.

## Stage 1 - BDD Setup

Install laravel like usually, for example using:

```
composer create-project --prefer-dist laravel/laravel behat-presentation
```

Behat, as you will see soon is great test framework, but unfortunately poor supported by Laravel.
There is only one library, that supports Behat on Laravel from laracast, but... 
It dosn't work with new Laravel version, and seems to be not supported anymore.

We can fix that by updating some code, so I've created repository fork for that.
First, let's add repository to our composer file:

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
cp .env.example .env.testing
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

Now, when we run `vendor/bin/behat`, Bahat will tell us, that we don't have any scenarios and steps - time to create one.

### *Stage 2.1* - Writing first feature

In directory `/features` create file called `rentacar.feature` with contents below:

```gherkin
Feature: Rent a car
  In order to rent a car
  As a customer
  I need to be able to order a car
```

This describes what we are going to do. We will write simple solution for car rentals.

Now set some rules:

```gherkin
  Rule:
  - Customer have to have at least 18yo
  - Customer may rent one car at a time
  - There are limited numbers of cars, customer may not rent reserved car
```

As you can see - it doesn't look like programming yet. It's more like a talk with customer or product owner.
This makes Behat and BDD such a great tool! You have to first exactly know what you are going to do, and then you are allowed to start!

However, rules are only information for background, and are not interpreted by tests, but scenarios are.

### *Stage 2.2* - Writing first story

Let's try to create our first scenario to check first rule:

```gherkin
  Scenario: I can rent a car if i have 18yo
    Given there is a "Tabaluga Dragon", that was born in 1997-10-04
    When "Tabaluga Dragon", wants to rent "Jeep" car
    Then "Tabaluga Dragon" will be able to rent a car
```

Now we have some business logic in our feature. Behat will recognize strings between `""` and numbers.
We may use also text string between `""" ... """` tables, and `<placeholders>`.

So our scenario will be transformed with those parameters:

```
there is a "Tabaluga Dragon", that was born in 1997-10-04
```

- "Tabaluga Dragon"
- 1997
- 10
- 04

Now we have our first scenario. It was simple, isn't it?
But I supposed that our testers, like Ania, will be not happy with that.

We also should test some failure scenarios.

```gherkin
  Scenario: I can't rent a car if i don't have 18yo
    Given there is a "Minion", that was born in 2015-06-26
    When "Minion", wants to rent "Jeep" car
    Then "Minion" will be not able to rent a car
```

As you can see, both scenarios are quite similar. We have another actor, another birth date, and negative as result.
Behat, when we run tests, will do exactly the same methods that we used in success scenario.

Now, it's important, that you may not mix scenarios together. 
You have to think what exactly should be done in specific process.

For example

```gherkin
  Scenario: I can rent a car if i have 18yo
    Given there is a "Tabaluga Dragon", that was born in 1997-10-04
    And there is a "Minion", that was born in 2015-06-26
    When "Tabaluga Dragon", wants to rent "Toyota" car
    And "Minion", wants to rent "Jeep" car
    Then "Tabaluga Dragon" will be able to rent a car
    But "Minion" will be not able to rent a car
```

This scenario is valid, and may be processed but process is messy. In real check we don't want to have 2 actors.
You may often have wrong scenarios for the first time, but BDD force you to do things right.

### *Stage 2.3* - gherkin language

Language that we are using is called `"Gherkin"`. You can see the syntax using `vendor/bin/behat --story-syntax`.

If it's an issue, we don't have to even use English for that.
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
    Blimey! "Tabaluga Dragon", wants to rent "Jeep" car
    Let go and haul "Tabaluga Dragon" will be able to rent a car
```