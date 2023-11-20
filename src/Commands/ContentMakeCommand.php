<?php

namespace Plank\Contentable\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:content')]
class ContentMakeCommand extends GeneratorCommand
{
    protected $signature = "make:content {name}";

    protected $description = "Create a new content model class to act as a receptacle for content";

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    public function handle()
    {
        if (!is_dir(app_path('Models/Content')) && $this->confirm('Would you like to use App\\Models\\Content as the name space for all generated content?')) {
            File::makeDirectory(app_path('Models/Content'));
        }

        parent::handle();
    }

    protected function getStub()
    {
        return $this->resolveStubPath("/stubs/content.php.stub");
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models/Content')) ? $rootNamespace.'\\Models\\Content' : $rootNamespace.'\\Models';
    }

}