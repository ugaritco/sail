<?php

namespace Ugarit\Sail\Console;

use Heritage\Console\Command;
use Ugarit\Sail\Console\Concerns\InteractsWithDockerComposeServices;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'sail:publish')]
class PublishCommand extends Command
{
    use InteractsWithDockerComposeServices;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sail:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the Ugarit Sail Docker files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'sail-docker']);
        $this->call('vendor:publish', ['--tag' => 'sail-database']);

        $composePath = $this->composePath();

        file_put_contents(
            $composePath,
            str_replace(
                [
                    './vendor/ugarit/sail/runtimes/8.5',
                    './vendor/ugarit/sail/runtimes/8.4',
                    './vendor/ugarit/sail/runtimes/8.3',
                    './vendor/ugarit/sail/runtimes/8.2',
                    './vendor/ugarit/sail/runtimes/8.1',
                    './vendor/ugarit/sail/runtimes/8.0',
                    './vendor/ugarit/sail/database/mariadb',
                    './vendor/ugarit/sail/database/mysql',
                    './vendor/ugarit/sail/database/pgsql'
                ],
                [
                    './docker/8.5',
                    './docker/8.4',
                    './docker/8.3',
                    './docker/8.2',
                    './docker/8.1',
                    './docker/8.0',
                    './docker/mariadb',
                    './docker/mysql',
                    './docker/pgsql'
                ],
                file_get_contents($composePath)
            )
        );
    }
}
