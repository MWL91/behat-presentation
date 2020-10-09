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

## Stage 3 - finally write some code

This may be a little boring, because we didn't write even line of code yet.

Yes, and it's good approach! First, you need to define what you want to achieve, then you should write real code.

Behat works great, when you don't feel the reason for using TDD. 
Now you don't need to use non-existing classes, to define system expectations - you can use human language.

Using our feature file, we are able now to generate code to test. 
Type `vendor/bin/behat`, then select FeatureContext, and you will see generated test methods:

```
    /**
     * @Given there is a :arg1, that was born in :arg2-:arg3-:arg4
     */
    public function thereIsAThatWasBornIn($arg1, $arg2, $arg3, $arg4)
    {
        throw new PendingException();
    }

    /**
     * @When :arg1, wants to rent a car
     */
    public function wantsToRentACar($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then :arg1 will be able to rent a car
     */
    public function willBeAbleToRentACar($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then :arg1 will be not able to rent a car
     */
    public function willBeNotAbleToRentACar($arg1)
    {
        throw new PendingException();
    }
```

We need them, otherwise Behat will tell us, that 
> FeatureContext has missing steps

You don't have to copy and paste them, you can automatically add them to FeatureContext, using

```
vendor/bin/behat --dry-run --append-snippets
```

### *Stage 3.1* - first given handle

As you can see, now when you run `vendor/bin/behat`, Behat will tell you, that you need to write pending definition.

All your methods throws now `PendingException` - it's time to change that, let's start with first method.

```php
/**
 * @Given there is a :arg1, that was born in :arg2-:arg3-:arg4
 */
public function thereIsAThatWasBornIn($arg1, $arg2, $arg3, $arg4)
{
    throw new PendingException();
}
```

As you can see, our sentence

> there is a "Tabaluga Dragon", that was born in 1997-10-04

was changed to comment and method `thereIsAThatWasBornIn`. 

Behat automatically find that we have string parameter between `"..."` and found numbers.
In practice, we will have call like:

```php
$featureContext = new FeatureContext();
$featureContext->thereIsAThatWasBornIn("Tabaluga Dragon", 1997, 10, 04);
```

It's also not a problem to change arguments name.

Let's write our method then:

```php
/**
 * @Given there is a :arg1, that was born in :arg2-:arg3-:arg4
 */
public function thereIsAThatWasBornIn($arg1, $arg2, $arg3, $arg4)
{
    throw new PendingException();
}
```

Also, we need to update our user migration, and add user birthday.

```php
// update migration
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->date('birthday');
    $table->timestamps();
});

// also add cast to User model
protected $casts = [
    'email_verified_at' => 'datetime',
    'birthday' => 'date'
];
```