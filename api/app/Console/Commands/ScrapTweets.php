<?php

namespace App\Console\Commands;

use App\Http\Helpers\TwitterScraper;
use App\Models\SearchValue;
use Illuminate\Console\Command;

class ScrapTweets extends Command
{
    use TwitterScraper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:scrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraping tweets';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        // Prevent duplicate job
        $flag = __DIR__ . '/ScrapTweets.flag';
        if (file_exists($flag)) {
            echo("Process already running \n");
            return false;
        }
        touch($flag);

        while (true) {
            /** @var SearchValue $search_values */
            $search_values = SearchValue::query()->select(['id', 'name', 'last_max'])->get();

            foreach ($search_values as $search_value) {
                $this->searchAndScrap($search_value);
            }

            sleep(get_twitter_interval());
        }

        if (file_exists($flag)) unlink($flag);

        return true;
    }
}
