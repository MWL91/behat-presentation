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
    When "Tabaluga Dragon", wants to rent a car
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
    When "Minion", wants to rent a car
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
    When "Tabaluga Dragon", wants to rent a car
    And "Minion", wants to rent a car
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
    Blimey! "Tabaluga Dragon", wants to rent a car
    Let go and haul "Tabaluga Dragon" will be able to rent a car
```

## Stage 3 - finally write some code

As you can see, we spend some time for writing scenarios, but we didn't write any code yet.
Yes, and it's good approach! First, you need to define what you want to achieve, then you should write real code.
This is like TDD but also you don't need to write test for code, that is not yet created.
You don't need to use non-existing classes, to define system expectations - you can use human language.
BDD makes you better developer. You have to first understood customer needs, then you are allowed to build solution.

Let's write some code. Using our feature file, we are able to generate code to test. 
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

We need them to describe steps in our process, otherwise Behat will tell us, that 
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

was changed to method `thereIsAThatWasBornIn`.

Behat automatically find that we have string parameter between `"..."` and found all numbers.
In practice, we will have call like:

```php
$featureContext = new FeatureContext();
$featureContext->thereIsAThatWasBornIn("Tabaluga Dragon", 1997, 10, 04);
```

Tests, whether or not created under BDD, should work under Arrange - Act - Assert.
Behat works the same way, and in next stage we implement that in code.

For current scenarios we used only one sentence for AAA, but often, you may need to use more. For that you may use `And` and `But` operators.
There is no difference between them in practice. Those operators may be used also for Acting, or Asserting.

```gherkin
  Scenario: I can't rent a car if i have 18yo
    Given there is a "Tabaluga Dragon", that was born in 1997-10-04
    And has 1 rented cars
    But dosn't have money
    When he wants to rent a car
    Then he will be not able to rent a car
```

## Stage 4  - *Arrange – Act – Assert* this is how the tests works

It's time to do implementation of our scenarios. Let's create Arrange step.
Fill thereIsAThatWasBornIn with user factory. We added to user model birthday field, and cast it as date.

```php
public function up()
{
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
}
```

```php
private User $user;

/**
 * @Given there is a :customer, that was born in :year-:month-:day
 */
public function thereIsAThatWasBornIn($customer, $year, $month, $day)
{
    $this->user = User::factory()->create(['name' => $customer, 'birthday' => Carbon::create($year, $month, $day)]);
}
```

As we going to use sqlite to our tests, we may use DatabaseTransaction trait.
Also, we should define user as class property.

```php
use \Laracasts\Behat\Context\DatabaseTransactions;

private User $user;
```

Now when we run `vendor/bin/behat` you can see that we have first step pass.

### Stage 4.1 - Act

Let's write implementation code for our test. We will do that by creating endpoint for car rent.

`php artisan make:controller RentACarController --api`

Then, add the endpoint to api routes:

```php
use App\Http\Controllers\RentACarController;

Route::post('/rent', [RentACarController::class, 'store']);
```

Now let's add FormRequest for validating our credentials.

`php artisan make:request RentACarRequest`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class RentACarRequest extends FormRequest
{

    const MATURE_YEARS = 18;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->birthday < Carbon::now()->subYears(self::MATURE_YEARS)->format('Y-m-d');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
```

And finally update `store` method in our controller:

```php
use App\Http\Requests\RentACarRequest;
use Illuminate\Http\JsonResponse;

/**
 * @param RentACarRequest $request
 * @return JsonResponse
 */
public function store(RentACarRequest $request): JsonResponse
{
    return new JsonResponse(['status' => true, 'message' => 'Booking has been created'], 201);
}
```

So we have simple API that dosn't do anything smart yet, but respose with success or not.
We can use it already test it with our scenario.

For that, we are going to use Laravel testing soultions, that are not prepared in the box like normal tests, 
so we will need to add some traits that laravel already has, and declare application.

```php
// ...
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Tests\CreatesApplication;
// ...

class FeatureContext extends MinkContext implements Context
{
    use InteractsWithAuthentication;
    use MakesHttpRequests;
    use CreatesApplication;
    
    private User $user;
    private Application $app;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->app = $this->createApplication();
    }

    /**
     * @When :customer, wants to rent a car
     */
    public function wantsToRentACar($customer)
    {
        $this->response = $this->actingAs($this->user, 'api')->json('POST', '/api/rent');
    }
}
```

Now when we run `vendor/bin/behat` we will have 2 steps passed. Now we need to write the last one, asserting.

### Stage 4.2 - Assert

Now, final step - we need to check if our code works like expected.

```php
/**
 * @Then :customer will be able to rent a car
 */
public function willBeAbleToRentACar($customer)
{
    $this->response->assertCreated();
}

/**
 * @Then :customer will be not able to rent a car
 */
public function willBeNotAbleToRentACar($customer)
{
    $this->response->assertStatus(403);
}
```

After running behat we will receive:

```
Feature: Rent a car
  In order to rent a car
  As a customer
  I need to be able to order a car
  
  Rule:
  - Customer have to have at least 18yo
  - Customer may rent one car at a time
  - There are limited numbers of cars, customer may not rent reserved car

  Scenario: I can rent a car if i have 18yo                         # features/rentacar.feature:11
    Given there is a "Tabaluga Dragon", that was born in 1997-10-04 # FeatureContext::thereIsAThatWasBornIn()
    When "Tabaluga Dragon", wants to rent a car                     # FeatureContext::wantsToRentACar()
    Then "Tabaluga Dragon" will be able to rent a car               # FeatureContext::willBeAbleToRentACar()

  Scenario: I can't rent a car if i don't have 18yo        # features/rentacar.feature:16
    Given there is a "Minion", that was born in 2015-06-26 # FeatureContext::thereIsAThatWasBornIn()
    When "Minion", wants to rent a car                     # FeatureContext::wantsToRentACar()
    Then "Minion" will be not able to rent a car           # FeatureContext::willBeNotAbleToRentACar()

2 scenarios (2 passed)
6 steps (6 passed)
0m0.29s (30.14Mb)
```

So our both tests passed! As you see, we used the same methods for arrange, and act, but different for asserts.
Now if you would like to implement more features, you can use the same syntax, and check other feature, without writing new pending definitions.

## Stage 5 - second scenario

Let's write a new scenario for our second rule: Customer may rent one car at a time.

```gherkin
Scenario: I can rent one car at a time
    Given there is a "Tabaluga Dragon", that was born in 1997-10-04
    And "Tabaluga Dragon" has already rented "Jeep" car
    When "Tabaluga Dragon", wants to rent a car
    Then "Tabaluga Dragon" will be not able to rent a car
```

As you see, we need to write only one new definition to make it works, all others are already covered.

Now when we run `vendor/bin/behat` we see that our new scenario dosn't have step. 
When we select `FeatureContext` class, we will generate new definition, but as you see it'ss just one method.

```php
/**
 * @Given :arg1 has already rented :arg2 car
 */
public function hasAlreadyRentedCar($arg1, $arg2)
{
    throw new PendingException();
}
```

Now let's update this, by adding user car property. 
We can use here database relations, but as it's just an example code, let's add just the name.

```php
/**
 * @Given :customer has already rented :carName car$/
 */
public function hasAlreadyRentedCar($customer, $carName)
{
    $this->user->car = $carName;
}
```

Now again let's run `vendor/bin/behat`. Our test of course didn't pass, because we didn't make required changes in code.

Let's fix that by changing `RentACarRequest` class.

```php
public function authorize()
{
    return $this->canUserRentCar();
}

private function canUserRentCar(): bool
{
    return $this->isMature() && !$this->hasRentedCar();
}

private function isMature(): bool
{
    return $this->user()->birthday < Carbon::now()->subYears(self::MATURE_YEARS)->format('Y-m-d');
}

private function hasRentedCar(): bool
{
    return $this->user()->car !== null;
}
```

We make some small refactor in our FormRequest. 
In real life, you probably don't want to check this in `authorize` method, but for our example this will be good enough.

Let's run behat `vendor/bin/behat`. All that works! Quietly fast like for a new feature in system, isn't it?