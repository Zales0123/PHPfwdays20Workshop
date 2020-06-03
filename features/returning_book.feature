Feature: Returning a book
    In order to allow other readers renting a book I've already read
    As a Reader
    I want to be able to return a rented book

    Background:
        Given there is a book "Harry Potter and the Goblet of Fire" by "J.K. Rowling"
        And I already rented it

    Scenario: Returning a book
        When I return a "Harry Potter and the Goblet of Fire" book
        Then I should be notified that the book has been successfully returned
        And this book should be available for renting
        And my rented books list should be empty
