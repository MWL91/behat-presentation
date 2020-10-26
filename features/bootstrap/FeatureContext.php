<?php

use App\Models\User;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Carbon\Carbon;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    private User $user;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given there is a :customer, that was born in :year-:month-:day
     */
    public function thereIsAThatWasBornIn($customer, $year, $month, $day)
    {
        $this->user = User::factory()->create(['name' => $customer, 'birthday' => Carbon::create($year, $month, $day)]);
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
}
