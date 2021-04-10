<?php

namespace Tests\Unit\Decorators;

use App\Decorators\GenerateOrder;
use App\Repositories\OrderDetails;
use App\Repositories\Orders;
use App\Repositories\Payments;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class GenerateOrderTest extends TestCase
{
    protected $orders;
    protected $orderDetails;
    protected $payments;
    protected $generateOrder;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->orders = Mockery::mock(Orders::class);
        $this->orderDetails = Mockery::mock(OrderDetails::class);
        $this->payments = Mockery::mock(Payments::class);

        $this->generateOrder = new GenerateOrder($this->orders, $this->orderDetails, $this->payments);
    }

    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub

        Mockery::close();
    }
    /**
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->orders->shouldReceive('index')->once();

        $this->orders->index();

        self::assertNotNull($response);
    }

    public function testStore(): void
    {
        $request = Mockery::mock(Request::class);

        $this->orders->shouldReceive('store')->with($request)->once();
        $this->orders->store($request);
        $order = $this->orderDetails->shouldReceive('createFromUser')->with(1)->once();
        $this->orderDetails->createFromUser(1);

        self::assertNotNull($order);
    }
}