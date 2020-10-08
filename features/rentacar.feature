Feature: Rent a car
  In order to rent a car
  As a customer
  I need to be able to order a car

  Rule:
  - Customer have to have at least 18yo
  - Customer may rent one car at a time
  - Customer may rent another car if return current
  - There are limited numbers of cars, when it's rented customer may not rent it

  Scenario: I can rent a car if i have 18yo
    Given there is a "Tabaluga Dragon", that was born in 1997-10-04
    And there is a "Minion", that was born in 2015-06-26
    When "Tabaluga Dragon", wants to rent a car
    And "Minion", wants to rent a car
    Then "Tabaluga Dragon" will be able to rent a car
    But "Minion" will be not able to rent a car