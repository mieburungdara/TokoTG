<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rename_users_to_user extends CI_Migration {

    public function up()
    {
        $this->dbforge->rename_table('users', 'user');
    }

    public function down()
    {
        $this->dbforge->rename_table('user', 'users');
    }
}
