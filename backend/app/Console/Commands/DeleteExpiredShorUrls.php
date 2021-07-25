<?php

namespace App\Console\Commands;

use App\Models\ShortUrl;
use Illuminate\Console\Command;

class DeleteExpiredShorUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shorturl:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired short urls from database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Deleta as urls vencidas.
     *
     * @return int
     */
    public function handle()
    {
        $expiredUrls = ShortUrl::hasExpired()->get();

        foreach ($expiredUrls as $expiredUrl) {
            $expiredUrl->delete();
        }

        return 0;
    }
}
