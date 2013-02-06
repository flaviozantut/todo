<?php

use Flaviozantut\Todo\Providers\Markdown;

Class MarkdownTodoTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->todo = new Markdown(__DIR__. '/fixtures/todo.md');
    }

    public function tearDown()
    {
        unlink ( $this->todo->file);
    }

    public function testAddTodo()
    {
        $todo = $this->todo->add('Task 1');
        $this->assertEquals(1, $todo['id']);
        $this->assertEquals('Task 1', $todo['todo']);
    }

    public function testArrayOfAllTodos()
    {
        $this->todo->add('Task 1');
        $this->todo->add('Task 2');
        $todos = $this->todo->ArrayOfAll();
        $this->assertEquals('Task 1', $todos['1']);
        $this->assertEquals('Task 2', $todos['2']);
    }

    public function testLastTodo()
    {
        $this->todo->add('Task 1');
        $this->todo->add('Task 2');
        $last = $this->todo->last();
        $this->assertEquals(2, $last['id']);
        $this->assertEquals('Task 2', $last['todo']);
    }

    public function testGetTodo()
    {
        $this->todo->add('Task 1');
        $this->todo->add('Task 2');
        $this->assertEquals('Task 2', $this->todo->get(2));
    }

    public function testCompleteTodo()
    {
        $this->todo->add('Task');
        $this->assertEquals('<strike>Task</strike>', $this->todo->complete(1));
    }

    public function testStatusTodo()
    {
        $this->todo->add('Task 1');
        $this->todo->add('<strike>Task 2</strike>');
        $this->assertEquals('opening', $this->todo->status(1));
        $this->assertEquals('closed', $this->todo->status(2));
    }

    public function testReopenTodo()
    {

        $this->todo->add('<strike>Task</strike>');
        $this->todo->add('Task 2');
        $this->assertEquals('Task', $this->todo->reopen(1));
    }

    public function testListTodo()
    {

        $this->todo->add('<strike>Task</strike>');
        $this->todo->add('Task 2');
        $list = $this->todo->listing();
        $this->assertEquals($list[1]['todo'], '<strike>Task</strike>');
        $this->assertEquals('2', count($list));
    }
}