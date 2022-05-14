<?php

declare(strict_types=1);

class MakeCommand extends ConsoleCommand
{
    use MakeCommandTrait;

    protected const FILE_EXTENSION = '.php';

    /* @var array stubs */
    private const STUBS = [
        'controller' => 'App\Controller',
        'column' => 'App\DataColumns',
        'repository' => 'App\Repository',
        'fillable' => 'App\Database\Fillable',
        'schema' => 'App\Database\Schema',
        'form' => 'App\Forms',
        'entity' => 'App\Entity',
        'model' => 'App\Model',
        'validate' => 'App\Validate',
        'event' => 'App\Event',
        'listener' => 'App\EventListener',
        'subscriber' => 'App\EventSubscriber',
        'middleware' => 'App\Middleware',
    ];

    /* @var string comomand name */
    protected string $name = 'magma:make';
    /* @var string command description */
    protected string $description = 'Make command can make class controllers, models, entities, forms etc...';
    /* @var string command help */
    protected string $help = 'Command which can generate a class file from a set of predefined stub files';

    /* @var array command arguments */
    protected array $args = [
        [
            'resource',
            'required',
            'What do you want to make. You can make stuff like [controller, model, entity, form, schema etc..',
        ],
    ];

    /* @var array command options */
    protected array $options = [
        [
            'dir',
            null,
            'optional',
            'Specify where this controller class belongs main controller directory or within the admin directory or a completely new directory.',
            false,
        ],
    ];
    private array $errors = [];
    private array $comments = [];
    private array $info = [];

    /**
     * Dispatch the command.
     * @return int
     */
    public function dispatch(): int
    {
        $stub = $this->getArgument('resource');
        $option = $this->getOptions('dir');

        try {
            $this->resolveResource($stub, $option);
            $this->terminalQuestion('Your file was created successfully');
            return ConsoleCommand::SUCCESS;
        } catch (MakeCommandFileAlreadyExistException|BaseInvalidArgumentException|BaseRuntimeException $e) {
            $this->terminalError(sprintf('%s', $e->getMessage()));
        } finally {
            return ConsoleCommand::FAILURE;
        }
    }
}