<?php

namespace Renatio\Image\Tests;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery;
use October\Rain\Database\Model;
use October\Rain\Database\Pivot;
use October\Tests\Concerns\InteractsWithAuthentication;
use October\Tests\Concerns\PerformsMigrations;
use October\Tests\Concerns\PerformsRegistrations;
use PDO;
use ReflectionClass;
use ReflectionException;
use TestCase;
use Throwable;

abstract class OctoberPestTestCase extends TestCase
{
    use InteractsWithAuthentication;
    use PerformsMigrations;
    use PerformsRegistrations;

    /** @var array<class-string>|null */
    protected static ?array $cachedModelClasses = null;

    protected static bool $databaseMigrated = false;

    protected static ?PDO $inMemoryConnection = null;

    protected static bool $pluginsLoaded = false;

    public function setUpOctoberPlugin(): void
    {
        $this->loadAllPlugins();
        $this->restoreInMemoryConnection();
        $this->runMigrationsOnce();
        $this->beginDatabaseTransaction();

        Model::unguard();
        Mail::pretend();
    }

    public function tearDownOctoberPlugin(): void
    {
        if (class_exists(Mockery::class)) {
            Mockery::close();
        }

        $this->rollbackDatabaseTransaction();
        $this->flushModelEventListeners();
    }

    protected function restoreInMemoryConnection(): void
    {
        if (self::$inMemoryConnection !== null) {
            DB::connection()->setPdo(self::$inMemoryConnection);
        }
    }

    protected function runMigrationsOnce(): void
    {
        if (self::$databaseMigrated) {
            return;
        }

        $this->migrateDatabase();
        self::$databaseMigrated = true;

        if ($this->usingInMemoryDatabase()) {
            self::$inMemoryConnection = DB::connection()->getPdo();
        }
    }

    protected function usingInMemoryDatabase(): bool
    {
        $default = config('database.default');

        return config("database.connections.{$default}.database") === ':memory:';
    }

    protected function beginDatabaseTransaction(): void
    {
        $connection = DB::connection();

        if ($connection->getPdo()->inTransaction()) {
            return;
        }

        $this->withoutEventDispatcher($connection, fn () => $connection->beginTransaction());
    }

    protected function rollbackDatabaseTransaction(): void
    {
        $connection = DB::connection();

        $this->withoutEventDispatcher($connection, function () use ($connection): void {
            try {
                if ($connection->getPdo()?->inTransaction()) {
                    $connection->rollBack();
                }
            } catch (Throwable) {
                self::$databaseMigrated = false;
            }
        });
    }

    protected function withoutEventDispatcher(Connection $connection, callable $callback): void
    {
        $dispatcher = $connection->getEventDispatcher();
        $connection->unsetEventDispatcher();

        $callback();

        $connection->setEventDispatcher($dispatcher);
    }

    protected function flushModelEventListeners(): void
    {
        self::$cachedModelClasses ??= $this->discoverModelClasses();

        foreach (self::$cachedModelClasses as $class) {
            try {
                $class::flushEventListeners();
            } catch (Throwable) {
                // Ignore classes that fail to flush
            }
        }

        Model::flushEventListeners();
    }

    /**
     * @return array<class-string>
     */
    protected function discoverModelClasses(): array
    {
        return array_filter(
            get_declared_classes(),
            fn (string $class): bool => $class !== Pivot::class
                && ! str_starts_with($class, 'Mockery')
                && $this->isFlushableModelClass($class),
        );
    }

    protected function isFlushableModelClass(string $class): bool
    {
        try {
            $reflectClass = new ReflectionClass($class);

            return $reflectClass->isInstantiable()
                && $reflectClass->isSubclassOf(Model::class)
                && ! $reflectClass->isSubclassOf(Pivot::class)
                && method_exists($class, 'flushEventListeners');
        } catch (ReflectionException) {
            return false;
        }
    }

    protected function guessPluginCodeFromTest(): string
    {
        return 'Renatio.Image';
    }
}
