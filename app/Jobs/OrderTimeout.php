<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderTimeout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;
    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->status = $order->status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->order->refresh();
        if ($this->order->status == $status) {
            if ($status == 'shipped') {
                $this->order->status = 'delivered';
                $this->order->delivered_at = now();
                $this->order->save();
			    $this->order->notifySeller();
            } else if ($status == 'delivered') {
                $this->order->status = 'completed';
                $this->order->completed_at = now();
                $this->order->save();
			    $this->order->notifySeller();
            } else if ($status == 'created' || $status == 'confirmed') {
                $this->order->status = 'closed';
                $this->order->reason = 'timeout';
                $this->order->closed_at = now();
                $this->order->save();
                $this->order->notifySeller();
                $this->order->notifyCustomer();
            }
        }
    }
}
