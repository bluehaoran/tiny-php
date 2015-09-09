<?php namespace League\Tiny;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class TinyGenerateCommand extends Command
{

    protected $name = 'tiny:generate';

    protected $description = "Generate a valid key";

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function fire()
    {
        $key = Tiny::generate_set();
        
        $path = base_path('.env');

        if (file_exists($path) && getenv('LEAGUE_TINY_KEY') !== false) {
            //Already set, so replace it.
            file_put_contents($path, str_replace(
                'LEAGUE_TINY_KEY='.getenv('LEAGUE_TINY_KEY'), 'LEAGUE_TINY_KEY='.$key, file_get_contents($path)
            ));   
        } else {
            //Append it to the bottom
            $fp = fopen($path, 'a');
            fwrite($fp, "\nLEAGUE_TINY_KEY=$key\n");
            fclose($fp);
        }
        
        $this->info("Tiny key [$key] has been set.");
    }

    protected function getKeyFile()
    {
        $env = $this->option('env') ? $this->option('env') . '/' : '';

        $contents = $this->files->get($path = $this->laravel['path'] . "/config/packages/league/tiny/{$env}config.php");

        return array($path, $contents);
    }

}
