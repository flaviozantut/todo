<?php
use Flaviozantut\Todo\Todo;

Class TodoTest extends PHPUnit_Framework_TestCase {
    public function testMarkdownProvider()
    {
        $todos = new Todo('Markdown', array(__DIR__. '/fixtures/todo.md'));
        $todo = $todos->add('Task 1');
        $this->assertEquals(1, $todo['id']);
        $this->assertEquals('Task 1', $todo['todo']);
        unlink (__DIR__. '/fixtures/todo.md');
    }
}