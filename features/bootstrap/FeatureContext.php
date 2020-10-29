<?php

use Behat\Behat\Tester\Exception\PendingException;
use App\Models\User;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\Concerns\InteractsWithTime;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\Concerns\MocksApplicationServices;
use Tests\CreatesApplication;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    use MakesHttpRequests,
        InteractsWithAuthentication,
        CreatesApplication;

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
     * @Given there is a :customer, that was born in :year-:month-:day
     */
    public function thereIsAThatWasBornIn($customer, $year, $month, $day)
    {
        $this->user = User::factory()->create(['name' => $customer, 'birthday' => Carbon::create($year, $month, $day)]);
    }

    /**
     * @Given :arg1 has already rented :arg2 car
     */
    public function hasAlreadyRentedCar($customer, $carName)
    {
        $this->user->car = $carName;
    }

    /**
     * @When :customer, wants to rent a car
     */
    public function wantsToRentACar($customer)
    {
        $this->response = $this->actingAs($this->user, 'api')->json('POST', '/api/rent');
    }

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

    /**
     * @Given :customer has already rented :carName car
     */
    public function hasAlreadyRentedCar($customer, $carName)
    {
        $this->user->car = $carName;
    }
}
