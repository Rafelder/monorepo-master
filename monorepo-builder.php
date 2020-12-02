<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $services = $containerConfigurator->services();

    // where are the packages located?
    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        __DIR__ . '/packages',
    ]);

    
    $parameters->set(Option::DIRECTORIES_TO_REPOSITORIES, [
        'packages/first-package' => 'git@github.com:Rafelder/monorepo-first.git',
        'packages/second-package' => 'git@github.com:Rafelder/monorepo-second.git',
    ]);

    // how skip packages in loaded direectories?
    // $parameters->set(Option::PACKAGE_DIRECTORIES_EXCLUDES, [__DIR__ . '/packages/secret-package']);

    // "merge" command related

    // what extra parts to add after merge?
    $parameters->set(Option::DATA_TO_APPEND, [
        'require-dev' => [
            'phpstan/phpstan' => '^0.12',
            'symplify/monorepo-builder' => '^8.3',
        ],
    ]);

    // $parameters->set(Option::DATA_TO_REMOVE, [
    //     'require' => [
    //         // the line is removed by key, so version is irrelevant, thus *
    //         'phpunit/phpunit' => '*',
    //     ],
    // ]);

    // release workers - in order to execute
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker::class);
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker::class);
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker::class);
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker::class);
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker::class);
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker::class);
    $services->set(Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker::class);
};