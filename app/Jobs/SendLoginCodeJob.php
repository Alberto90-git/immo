<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SendCodemail;

class SendLoginCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 3;
    public $backoff = 30;

    protected string $email;
    protected int    $code;

    public function __construct(string $email, int $code)
    {
        $this->email = $email;
        $this->code  = $code;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send(new SendCodemail([
            'code_login' => $this->code,
            'email'      => $this->email,
        ]));
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SendLoginCodeJob échoué', [
            'email' => $this->email,
            'error' => $e->getMessage(),
        ]);
    }
}
