Feature: Renting a book
    In order to fulfill my reading desires
    As a Reader
    I want to be able to rent a book

    Background:
        Given there is a book "Harry Potter and the Goblet of Fire" by "J.K. Rowling"
        And there is a book "The Lord of the Rings: The Two Towers" by "J.R.R. Tolkien"

    Scenario: Renting a book
        When I rent a book "The Lord of the Rings: The Two Towers"
        Then I should be notified that the book is successfully rented
        And this book should be marked as "rented"
        And I should have the "The Lord of the Rings: The Two Towers" on my rented books list

    Scenario: Book rental validation
        Given a book "The Lord of the Rings: The Two Towers" is already rented
        When I try to rent this book
        Then I should not be able to do that
