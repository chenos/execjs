<?php

namespace Chenos\ExecJs\Tests;

use Chenos\ExecJs\Pool;
use Chenos\ExecJs\Engine;
use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    public function testPool()
    {
        $workers = [];
        $pool = new Pool();
        $this->assertEquals(0, $pool->count(Engine::class));

        $parser1 = $pool->get(Engine::class);
        $workers[Engine::class][spl_object_hash($parser1)] = $parser1;

        $this->assertAttributeEquals($workers, 'occupiedWorkers', $pool);
        $this->assertEquals(1, $pool->count(Engine::class));

        $parser2 = $pool->get(Engine::class);
        $workers[Engine::class][spl_object_hash($parser2)] = $parser2;

        $this->assertAttributeEquals($workers, 'occupiedWorkers', $pool);
        $this->assertEquals(2, $pool->count(Engine::class));

        $pool->dispose($parser1);
        $pool->dispose($parser2);

        $this->assertAttributeEquals($workers, 'freeWorkers', $pool);
        $this->assertEquals(2, $pool->count(Engine::class));

        $this->assertSame($parser2, $pool->get(Engine::class));
        $this->assertSame($parser1, $pool->get(Engine::class));
    }

    public function testVariable()
    {
        $pool = new Pool();
        $parser1 = $pool->get(Engine::class);
        $parser1->aa = 'aa';
        $pool->dispose($parser1);

        $this->assertNull($parser1->aa);
        $this->assertSame($parser1, $pool->get(Engine::class));
    }
}