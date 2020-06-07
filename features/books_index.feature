Feature: Books index
    In order to be aware about the books offered by the library
    As a Reader
    I want to be able to browse books index

    Background:
        Given there is a book "Harry Potter and the Goblet of Fire" by "J.K. Rowling"
        And its release date is "08.07.2000"
        And its genre is "Fantasy"
        And there is a book "The Lord of the Rings: The Two Towers" by "J.R.R. Tolkien"
        And its release date is "11.11.1954"
        And its genre is "Fantasy"

    Scenario: Browsing books index
        When I browse books index
        Then I should have information about 2 books
        And one of them should be written by "J.R.R. Tolkien"

    Scenario: Browsing limited number of books on the index
        Given there are 10 more books
        When I browse books index
        Then I should have information about 10 books
