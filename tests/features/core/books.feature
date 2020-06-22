Feature: Adding a book to the library
  When a book is added to the library, add a record into the books table
  For each book author:
    If the book's author is not already in the authors table, add a record into the authors table
    Add a record into the book_authors table for the book and author
  If the book is part of a series:
    If the series is not already in the series table, add a record into the series table
    If the series already exists, add the series_id for that series to the book record
  If the book's publisher is not already in the publishers table, add a record into the publishers table
  For each book category:
    If the book's category is not already in the categories table, add a record into the categories table
    Add a record into the book_categories table for the book and category
  If the book has a cover photo:
    Upload the cover photo to the data store
    Add a record into the covers table referencing the data store path and book_id

Background:
  Given I have these Authors
    | id | first_name | last_name |
    | 1  | Douglas    | Adams     |
    | 2  | Terry      | Pratchett |
    | 3  | Neil       | Gaiman    |
  And I have these Publishers
    | id | name                   |
    | 1  | Ace Books              |
    | 2  | Del Rey                |
  And I have these Categories
    | id | name                   |
    | 1  | Science Fiction        |
    | 2  | Fiction                |
    | 3  | Comedy                 |
  And I have these Books
    | id | isbn          | publisher_id | published_year | pages | title                                         |
    | 1  | 9780441003259 | 1            | 1996           | 366   | Good Omens                                    |
    | 2  | 9780345453747 | 2            | 2002           | 832   | The Ultimate Hitchhiker's Guide to the Galaxy |
  And I have these BookAuthors
    | id | book_id | author_id |
    | 1  | 1       | 2         |
    | 2  | 1       | 3         |
    | 3  | 2       | 1         |
  And I have these BookCategories
    | id | book_id | category_id |
    | 1  | 1       | 2           |
    | 2  | 1       | 3           |
    | 3  | 2       | 1           |
    | 4  | 2       | 2           |
    | 5  | 2       | 3           |

Scenario: Author, Publisher, Category, and Book do not exist in database
  When I create a new book with this data
    | title                | authors        | publisher        | published_year | pages | isbn          | categories          |
    | The Third Chimpanzee | Diamond, Jared | Harper Perennial | 1993           | 407   | 9780060845506 | Nonfiction, Biology |
  Then I have 4 Authors records
  And an Author with FirstName "Jared" and LastName "Diamond" exists
  And I have 3 Publishers records
  And a Publisher with Name "Harper Perennial" exists
  And I have 5 Categories records
  And a Category with Name "Nonfiction" exists
  And a Category with Name "Biology" exists
  And I have 3 Books records
  And a Book with ISBN "9780060845506" exists
  And I have 4 BookAuthors records
  And I have 7 BookCategories records