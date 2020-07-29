# Challenge: The library design pattern challenge

## Required features
Let us make us software to manage books in a Library.
This software will be used in several libraries, but the problem is that those libraries are using different software to currently manage their inventory.
The result of this is that they cannot deliver their stock in the same format, some of them use a JSON format, others an CSV format.
Make it so changing a constant at the top of your code switches the imported format.

Once the inventory is loaded you can store it in a session objects for the duration of that user session.

- Provide an input field where they can enter the (partial) name of the books and they get a table with their results.
- Provide a dropdown where they can select between all possible genres
- Provide a dropdown where they can select between the different publishers.
- Display at the top of the page the total pages of your current search, or the current genre, or the current publisher. If nothing was selected, show the total number of of pages in the library.

### Workflow
A book has the following statuses: open, lended, overtime, lost, sold

- Open -> can go to -> lended or Sold (delete the book from the inventory*)
- Lended -> can go to overtime (Count the number of books overtime in the library object) OR go to open OR go to Lost (delete the book from the inventory*)
- Overtime -> can go Open or Lost (see above)
- Lost & Sold -> Final state

_*To simulate the deletion of the book you can just save the name of the book in a session variable, and let the library not show it anymore_

Provide, depending on the status of each book, the matching buttons in the interface.

## Suggested classed
You are not required to create the following classes, but the following is a list of suggestions to split up your code in smaller parts.
When you use the design patterns suggested below you might even end up with more classes!

- BookImporter
- BookImporterCsv
- BookImporterJson
- Library
- Genre
- Publisher
- Book
- PartialBookSearch (when the user searches for a word in a title)
- OpenState, LendedState, OvertimeState, LostState, SoldState

## Suggested design patterns
- Use either a strategy design pattern (easy) or a factory design pattern (more flexible, but more difficult) to handle the different formats.
- Use the state design pattern to handle the different states of your book.
- Use a composite to count the number of pages of the current selections
- (Optional) Use an MVC structure, you could use the [MVC Boilerplate](https://github.com/becodeorg/php-mvc-boilerplate) provided by your coach.
