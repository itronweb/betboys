<?php

Class Migrations {

    /**
     * Creates a migration - for programiccal usage.
     * @param type $module module name
     * @param type $name file name
     */
    public function migration_create($module, $name) {
        $path = APPPATH . 'modules/' . $module;
        $repo = new Illuminate\Database\Migrations\MigrationCreator(new \Illuminate\Filesystem\Filesystem());
        $repo->create($name, $path . '/migrations');
    }

    /**
     * do all migrations - for programiccal usage.
     * @param string $module module name
     */
    public function migration_do($module) {
        $path = APPPATH . 'modules/' . $module;
        $file_system = new \Illuminate\Filesystem\Filesystem();
        $repo = new MirookMigrationRepository(Illuminate\Database\Eloquent\Model::getConnectionResolver(), 'migrations');
        $migrator = new MirookMigrator($repo, Illuminate\Database\Eloquent\Model::getConnectionResolver(), $file_system);
        $migrator->run($path . '/migrations');
    }

    /**
     * revert last migrate(includes all migration done in last order) - for programiccal usage.
     * @param string $module module name
     */
    public function migration_down_batch($module) {
        $path = APPPATH . 'modules/' . $module;
        $file_system = new \Illuminate\Filesystem\Filesystem();
        $repo = new MirookMigrationRepository(Illuminate\Database\Eloquent\Model::getConnectionResolver(), 'migrations');
        $migrator = new MirookMigrator($repo, Illuminate\Database\Eloquent\Model::getConnectionResolver(), $file_system);
        $migrator->mirook_back_by_batch($path . '/migrations');
    }

    /**
     * revert last migration - for programiccal usage.
     * @param string $module module name
     */
    public function migration_down_last($module) {
        $path = APPPATH . 'modules/' . $module;
        $file_system = new \Illuminate\Filesystem\Filesystem();
        $repo = new MirookMigrationRepository(Illuminate\Database\Eloquent\Model::getConnectionResolver(), 'migrations');
        $migrator = new MirookMigrator($repo, Illuminate\Database\Eloquent\Model::getConnectionResolver(), $file_system);
        $migrator->mirook_back_by_last($path . '/migrations');
    }

    /**
     * revert all migrations - for programiccal usage.
     * @param string $module module name
     */
    public function migration_reset($module) {
        $path = APPPATH . 'modules/' . $module;
        $file_system = new \Illuminate\Filesystem\Filesystem();
        $repo = new MirookMigrationRepository(Illuminate\Database\Eloquent\Model::getConnectionResolver(), 'migrations');
        $migrator = new MirookMigrator($repo, Illuminate\Database\Eloquent\Model::getConnectionResolver(), $file_system);
        $migrator->mirook_reset($path . '/migrations');
    }

}

class MirookMigrator extends \Illuminate\Database\Migrations\Migrator {

    /**
     * Run the outstanding migrations at a given path.
     *
     * @param  string  $path
     * @param  bool    $pretend
     * @return void
     */
    public function mirook_reset($path, $pretend = false) {
        $this->notes = array();

        $files = $this->getMigrationFiles($path);
        // Once we grab all of the migration files for the path, we will compare them
        // against the migrations that have already been run for this package then
        // run each of the outstanding migrations against a database connection.
        $ran = $this->repository->getRan();
        $files = array_intersect($files, $ran);
        $migrations = array();
        foreach ($files as $file) {
            $migrations[] = $this->repository->get_migration($file);
        }
        $this->requireFiles($path, $files);

        $this->downMigrationList($migrations, $pretend);
    }

    /**
     * Run the outstanding migrations at a given path.
     *
     * @param  string  $path
     * @param  bool    $pretend
     * @return void
     */
    public function mirook_back_by_batch($path, $pretend = false) {
        $this->notes = array();

        $files = $this->getMigrationFiles($path);
        // Once we grab all of the migration files for the path, we will compare them
        // against the migrations that have already been run for this package then
        // run each of the outstanding migrations against a database connection.
        $ran = $this->repository->getRan();
        $files = array_reverse(array_intersect($files, $ran));
        if (count($files) == 0) {
            $this->note('<info>Nothing to migrate down.</info>');
            return;
        }
        $migrations = array();
        foreach ($files as $key => $file) {
            $temp = (object) $this->repository->get_migration($file);
            if (!isset($batch_number)) {
                $batch_number = $temp->batch;
            }
            if ($batch_number == $temp->batch) {
                $migrations[] = $temp;
            } else {
                unset($files[$key]);
            }
        }
        $this->requireFiles($path, $files);
        $this->downMigrationList($migrations, $pretend);
    }

    /**
     * Run the outstanding migrations at a given path.
     *
     * @param  string  $path
     * @param  bool    $pretend
     * @return void
     */
    public function mirook_back_by_last($path, $pretend = false) {
        $this->notes = array();

        $files = $this->getMigrationFiles($path);
        // Once we grab all of the migration files for the path, we will compare them
        // against the migrations that have already been run for this package then
        // run each of the outstanding migrations against a database connection.
        $ran = $this->repository->getRan();
        $files = array_intersect($files, $ran);
        if (count($files) == 0) {
            $this->note('<info>Nothing to migrate down.</info>');
            return;
        }
        $last_file = array_pop($files);
        $last_migration = (object) $this->repository->get_migration($last_file);
        $this->requireFiles($path, [$last_file]);
        $this->downMigrationList([$last_migration], $pretend);
    }

    /**
     * Run an array of migrations.
     *
     * @param  array  $migrations
     * @param  bool   $pretend
     * @return void
     */
    public function downMigrationList($migrations, $pretend = false) {
        // First we will just make sure that there are any migrations to run. If there
        // aren't, we will just make a note of it to the developer so they're aware
        // that all of the migrations have been run against this database system.
        if (count($migrations) == 0) {
            $this->note('<info>Nothing to migrate down.</info>');

            return;
        }

        // Once we have the array of migrations, we will spin through them and run the
        // migrations "up" so the changes are made to the databases. We'll then log
        // that the migration was run so we don't repeat it next time we execute.
        foreach ($migrations as $migration) {
            $this->runDown((object) $migration, $pretend);
        }
    }

}

class MirookMigrationRepository extends Illuminate\Database\Migrations\DatabaseMigrationRepository {

    public function get_migration($file) {
        return $this->table()->where('migration', $file)->first();
    }

}
