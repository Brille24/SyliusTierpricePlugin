@tierprices
Feature: Unit price changes based on tier price
        In order to use tierprices and get a discount
        As a customer
        The cart has to update the unit prices

  Background:
    Given the store operates on a single channel in "United States"

  @ui
  Scenario: User buys a product that has no tier price
    Given the store has a product "The Pug Mug" priced at "$7.00"
    When I add this product to the cart
    Then I should be on my cart summary page
    And I should see "The Pug Mug" with unit price "$7.00" in my cart

  @ui
  Scenario: User buys a product that has a tierprice but that does not apply
    Given the store has a product "Cool Product" priced at "$11.32"
    And "this product" has a tier price at 5 with "$10.00"
    When I add this product to the cart
    Then I should be on my cart summary page
    And I should see "Cool Product" with unit price "$11.32" in my cart
