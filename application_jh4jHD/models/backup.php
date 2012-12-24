<?php

class Backup extends CI_Model
{


    /**
     * create a zipped backup of the database in the APPLICATION/sql folder
     */
    public function database()
    {
        // Load the DB utility class
        $CI =& get_instance();
        $CI->load->dbutil();
        
        // Backup your entire database and assign it to a variable
        $backup =& $this->dbutil->backup(array('format' => 'zip'));
        
        // Load the file helper and write the file to your server
        $CI->load->helper('file');
        $filename = APPPATH.'sql/backup_'.date('Y-m-d_H-i').'.zip';
        write_file($filename, $backup);
        
    }
}


