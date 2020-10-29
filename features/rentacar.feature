Feature: Rent a car
  In order to rent a car
  As a customer
  I need to be able to order a car

  Rule:
  - Customer have to have at least 18yo
  - Customer may rent one car at a time
  - There are limited numbers of cars, customer may not rent reserved car

  Scenario: I can rent a car if i have 18yo
    Given there is a "Tabaluga Dragon", that was born in 1997-10-04
    When "Tabaluga Dragon", wants to rent "Jeep" car
    Then "Tabaluga Dragon" will be able to rent a car

  Scenario: I can't rent a car if i don't have 18yo
    Given there is a "Minion", that was born in 2015-06-26
    When "Minion", wants to rent "Jeep" car
    Then "Minion" will be not able to rent a car