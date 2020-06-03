Feature: Book details
    In order to know do I really want to rent a book
    As a Reader
    I want to be able to check book details

    Background:
        Given there is a book "Harry Potter and The Goblet of Fire" by "J.K. Rowling"
        And its release date is 8.07.2000
        And its genre is "Fantasy"

    Scenario: Browsing book details
        When I check the details of "Harry Potter and The Goblet of Fire" book
        Then I should notice it's written by "J.K. Rowling"
        And it should be categories as "Fantasy"
        And it was released on 8.07.2000
