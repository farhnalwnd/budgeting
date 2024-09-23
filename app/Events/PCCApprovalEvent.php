<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\PCR\PCR;

class PCCApprovalEvent
{
    use Dispatchable, SerializesModels;

    public $pcr;

    /**
     * Create a new event instance.
     *
     * @param PCR $pcr
     * @return void
     */
    public function __construct(PCR $pcr)
    {
        $this->pcr = $pcr;
    }
}
