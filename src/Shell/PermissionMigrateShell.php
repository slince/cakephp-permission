<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Shell;

use Cake\Console\Shell;
use Slince\CakePermission\Exception\RuntimeException;

class PermissionMigrateShell extends Shell
{

    protected $migrationFilesPath;

    /**
     * {@inheritdoc}
     */
    public function main()
    {
        $this->migrationFilesPath = CONFIG . 'Migrations/';
        //Creates the directory if it does not exists
        file_exists($this->migrationFilesPath) || mkdir($this->migrationFilesPath, 0777, true);
        //All migration files
        $srcMigrationFiles = $this->findMigrationFiles(__DIR__ . '/../Migration/');
        foreach ($srcMigrationFiles as $file) {
            $result = $this->processMigrationFile($file);
            if (!$result) {
                throw new RuntimeException(sprintf('Fail to copy migration file "%s"', $file));
            }
        }
        $this->out('Migrate ok, please execute command "./cake migrations migrate"');
    }

    /**
     * Find array of migration file
     * @param string $path
     * @return array
     */
    protected function findMigrationFiles($path)
    {
        return glob("{$path}/*");
    }

    protected function processMigrationFile($originFile)
    {
        $format = 'YmdHis';
        $newFileName = $this->migrationFilesPath
            . date($format) . '_' . basename($originFile);
        return copy($originFile, $newFileName);
    }
}