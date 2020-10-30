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

## Mink 1 - configure behat

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

## Mink 2 - met Mink browser tests

So, when we have configured our env, let's look at Mink tests.
Now, Mink allows you to test application like it would be browser tests.

Thanks to `MinkContext` we already can try to write first scenario, and even test it!
Let's create `mink.feature` file in `features` directory.
Now we can check what we already have in definition list `vendor/bin/behat -dl`.

```gherkin
Given /^(?:|I )am on (?:|the )homepage$/
When /^(?:|I )go to (?:|the )homepage$/
Given /^(?:|I )am on "(?P<page>[^"]+)"$/
When /^(?:|I )go to "(?P<page>[^"]+)"$/
When /^(?:|I )reload the page$/
When /^(?:|I )move backward one page$/
When /^(?:|I )move forward one page$/
When /^(?:|I )press "(?P<button>(?:[^"]|\\")*)"$/
When /^(?:|I )follow "(?P<link>(?:[^"]|\\")*)"$/
When /^(?:|I )fill in "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
When /^(?:|I )fill in "(?P<field>(?:[^"]|\\")*)" with:$/
When /^(?:|I )fill in "(?P<value>(?:[^"]|\\")*)" for "(?P<field>(?:[^"]|\\")*)"$/
When /^(?:|I )fill in the following:$/
When /^(?:|I )select "(?P<option>(?:[^"]|\\")*)" from "(?P<select>(?:[^"]|\\")*)"$/
When /^(?:|I )additionally select "(?P<option>(?:[^"]|\\")*)" from "(?P<select>(?:[^"]|\\")*)"$/
When /^(?:|I )check "(?P<option>(?:[^"]|\\")*)"$/
When /^(?:|I )uncheck "(?P<option>(?:[^"]|\\")*)"$/
When /^(?:|I )attach the file "(?P<path>[^"]*)" to "(?P<field>(?:[^"]|\\")*)"$/
Then /^(?:|I )should be on "(?P<page>[^"]+)"$/
Then /^(?:|I )should be on (?:|the )homepage$/
Then /^the (?i)url(?-i) should match (?P<pattern>"(?:[^"]|\\")*")$/
Then /^the response status code should be (?P<code>\d+)$/
Then /^the response status code should not be (?P<code>\d+)$/
Then /^(?:|I )should see "(?P<text>(?:[^"]|\\")*)"$/
Then /^(?:|I )should not see "(?P<text>(?:[^"]|\\")*)"$/
Then /^(?:|I )should see text matching (?P<pattern>"(?:[^"]|\\")*")$/
Then /^(?:|I )should not see text matching (?P<pattern>"(?:[^"]|\\")*")$/
Then /^the response should contain "(?P<text>(?:[^"]|\\")*)"$/
Then /^the response should not contain "(?P<text>(?:[^"]|\\")*)"$/
Then /^(?:|I )should see "(?P<text>(?:[^"]|\\")*)" in the "(?P<element>[^"]*)" element$/
Then /^(?:|I )should not see "(?P<text>(?:[^"]|\\")*)" in the "(?P<element>[^"]*)" element$/
Then /^the "(?P<element>[^"]*)" element should contain "(?P<value>(?:[^"]|\\")*)"$/
Then /^the "(?P<element>[^"]*)" element should not contain "(?P<value>(?:[^"]|\\")*)"$/
Then /^(?:|I )should see an? "(?P<element>[^"]*)" element$/
Then /^(?:|I )should not see an? "(?P<element>[^"]*)" element$/
Then /^the "(?P<field>(?:[^"]|\\")*)" field should contain "(?P<value>(?:[^"]|\\")*)"$/
Then /^the "(?P<field>(?:[^"]|\\")*)" field should not contain "(?P<value>(?:[^"]|\\")*)"$/
Then /^(?:|I )should see (?P<num>\d+) "(?P<element>[^"]*)" elements?$/
Then /^the "(?P<checkbox>(?:[^"]|\\")*)" checkbox should be checked$/
Then /^the "(?P<checkbox>(?:[^"]|\\")*)" checkbox is checked$/
Then /^the checkbox "(?P<checkbox>(?:[^"]|\\")*)" (?:is|should be) checked$/
Then /^the "(?P<checkbox>(?:[^"]|\\")*)" checkbox should (?:be unchecked|not be checked)$/
Then /^the "(?P<checkbox>(?:[^"]|\\")*)" checkbox is (?:unchecked|not checked)$/
Then /^the checkbox "(?P<checkbox>(?:[^"]|\\")*)" should (?:be unchecked|not be checked)$/
Then /^the checkbox "(?P<checkbox>(?:[^"]|\\")*)" is (?:unchecked|not checked)$/
Then /^print current URL$/
Then /^print last response$/
Then /^show last response$/
```

You should see list like that. Let's try to use some prepared definition to create own scenario.

```gherkin
Feature: Website display content
  In order to display content
  As a user
  I want to see content on pages

  Scenario: Display Home Page
    Given I am on the homepage
    Then I should see "Laravel"
```