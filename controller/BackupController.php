<?php


class BackupController
{
    /**
     *  @var String
     * Defines the backup path (e.g. directory on nas)
     */
    const BACKUP_PATH = '/Users/userName/Desktop/';

    /**
     * @var String
     * The selected components ('all', 'crm' or 'cms') for the backup
     */
    private $selectedComponents;

    /**
     * @var array[array]
     * Contains db access data for the individual components (crm, cms)
     */
    private $config;

    /**
     * @var String
     * The name of the backup directory
     */
    private $backupDirectoryName;

    /**
     * @var String
     * The name of the cms dump (.sql)
     */
    private $cmsFileName;

    /**
     * @var String
     * The name of the crm dump (.sql)
     */
    private $crmFileName;

    /**
     * @var String
     * The name of the backup directory
     */
    private $zipDirectory;


    function __construct($components)
    {
       $this->selectedComponents = $components;
       $this->config = $this->getConfig();
       $this->initDirectoryAndFileNames();
    }

    function initDirectoryAndFileNames()
    {
        $this->backupDirectoryName = $this->getBackupDirectory();
        $this->cmsFileName = $this->getCmsFileName();
        $this->crmFileName = $this->getCrmFileName();
        $this->zipDirectory = $this->getZipDirectory();
    }

    /**
     * @return  array[array]
     * Returns the db access data from the config file
     */
    public function getConfig()
    {
        require_once 'conf/dbconf.php';
        return getDbConf();
    }

    /**
     * @return String
     * Returns the name for the cms backup file
     */
    public function getCmsFileName()
    {
        $filename = 'filename_'.$this->getDate().'.sql';
        return $filename;
    }

    /**
     * @return String
     * Returns the name for the crm backup file
     */
    public function getCrmFileName()
    {
        $filename = 'filename_'.$this->getDate().'.sql';
        return $filename;
    }

    /**
     * @return String
     * Returns the name of the backup directory
     */
    public function getBackupDirectory()
    {
        $directoryname = 'filename_web_crm_'.$this->getDate();
        return $directoryname;
    }

    /**
     * @return String
     * Returns the name of the backup directory
     */
    public function getZipDirectory()
    {
        $zipDirectory = self::BACKUP_PATH.$this->getBackupDirectory();
        return $zipDirectory;
    }

    /**
     * @return void
     * Creates a backup of the selected component
     */
    public function createMysqlDump()
    {
        $this->createBackupDirectory();

        switch ($this->selectedComponents) {

            case 'all':
                $this->startCrmDump();
                $this->startCmsDump();
                break;

            case 'crm':
                $this->startCrmDump();
                break;

            case 'cms':
                $this->startCmsDump();
                break;
        }
        $this->zipFolder();
    }

    private function startCrmDump()
    {
        $data = $this->getConfig()['crm'];
        $dump = new Mysqldump('mysql:host='.$data['host'].';dbname='.$data['db'], $data['user'], $data['pwd']);
        $dump->start(self::BACKUP_PATH.$this->backupDirectoryName.'/'.$this->crmFileName);
    }

    private function startCmsDump()
    {
        $data = $this->getConfig()['cms'];
        $dump = new Mysqldump('mysql:host='.$data['host'].';dbname='.$data['db'], $data['user'], $data['pwd']);
        $dump->start(self::BACKUP_PATH.$this->backupDirectoryName.'/'.$this->cmsFileName);
    }

    private function createBackupDirectory()
    {
        $directory = self::BACKUP_PATH.DIRECTORY_SEPARATOR.$this->backupDirectoryName;

        if(!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    private function zipFolder()
    {
        require_once 'lib/Zip.php';

        $zip_name = $this->backupDirectoryName;
        $zip_directory = self::BACKUP_PATH;
        $zip = new zip( $zip_name, $zip_directory );
        $zip->add_directory( self::BACKUP_PATH.$this->backupDirectoryName );
        $zip->save();
    }

    private function getDate()
    {
        return date('y-m-j');
    }
}
