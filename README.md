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

It's also not a problem to change arguments names.
Now let's update our FeatureContext class with real code:

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

We will use database for our tests, so for that we will use DatabaseTransaction trait.
Also, we should define users property.

```php
use \Laracasts\Behat\Context\DatabaseTransactions;

private User $user;
```

Now when we run `vendor/bin/behat` you can see that we have first step pass, and for `When` we have to write definition.