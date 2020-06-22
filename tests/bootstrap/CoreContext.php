<?php

namespace Elbucho\Library\Tests\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class CoreContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given /^I have these Authors$/
     */
    public function iHaveTheseAuthors(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given /^I have these Publishers$/
     */
    public function iHaveThesePublishers(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given /^I have these Categories$/
     */
    public function iHaveTheseCategories(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given /^I have these Books$/
     */
    public function iHaveTheseBooks(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given /^I have these BookAuthors$/
     */
    public function iHaveTheseBookAuthors(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given /^I have these BookCategories$/
     */
    public function iHaveTheseBookCategories(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Then /^I have (?P<count>\d+) (?P<table>\w+) records$/
     */
    public function iHaveTableRecords($count, $table)
    {
        throw new PendingException();
    }

    /**
     * @Then /^an Author with FirstName "([^"]*)" and LastName "([^"]*)" exists$/
     */
    public function anAuthorWithFirstNameAndLastNameExists($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then /^a Publisher with Name "([^"]*)" exists$/
     */
    public function aPublisherWithNameExists($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then /^a Category with Name "([^"]*)" exists$/
     */
    public function aCategoryWithNameExists($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then /^a Book with ISBN "([^"]*)" exists$/
     */
    public function aBookWithISBNExists($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When /^I create a new book with this data$/
     */
    public function iCreateANewBookWithThisData(TableNode $table)
    {
        throw new PendingException();
    }
}
