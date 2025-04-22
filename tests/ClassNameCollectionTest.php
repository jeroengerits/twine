<?php

namespace Tests;

use JeroenGerits\Twine\Twine\ClassNameCollection;
use PHPUnit\Framework\TestCase;

class ClassNameCollectionTest extends TestCase
{
    public function test_it_can_be_created()
    {
        $collection = new ClassNameCollection(['btn', 'btn-primary']);

        $this->assertInstanceOf(ClassNameCollection::class, $collection);
        $this->assertEquals(['btn', 'btn-primary'], $collection->toArray());
    }

    public function test_it_can_convert_to_string()
    {
        $collection = new ClassNameCollection(['btn', ['btn-primary']]);

        $this->assertEquals('btn btn-primary', $collection->toString());
    }
}
