<?php

namespace Chenos\ExecJs\Tests;

use Chenos\ExecJs\Pool;
use Chenos\ExecJs\Context;
use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    public function testPool()
    {
        $workers = [];
        $pool = new Pool();
        $this->assertEquals(0, $pool->count(Context::class));

        $context1 = $pool->get(Context::class);
        $workers[Context::class][spl_object_hash($context1)] = $context1;

        $this->assertAttributeEquals($workers, 'occupiedWorkers', $pool);
        $this->assertEquals(1, $pool->count(Context::class));

        $context2 = $pool->get(Context::class);
        $workers[Context::class][spl_object_hash($context2)] = $context2;

        $this->assertAttributeEquals($workers, 'occupiedWorkers', $pool);
        $this->assertEquals(2, $pool->count(Context::class));

        $pool->dispose($context1);
        $pool->dispose($context2);

        $this->assertAttributeEquals($workers, 'freeWorkers', $pool);
        $this->assertEquals(2, $pool->count(Context::class));

        $this->assertSame($context2, $pool->get(Context::class));
        $this->assertSame($context1, $pool->get(Context::class));
    }

    public function testVariable()
    {
        $pool = new Pool();
        $context1 = $pool->get(Context::class);
        $context1->aa = 'aa';
        $pool->dispose($context1);

        $this->assertNull($context1->aa);
        $this->assertSame($context1, $pool->get(Context::class));
    }
}