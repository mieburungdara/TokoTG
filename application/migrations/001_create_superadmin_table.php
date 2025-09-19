<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_superadmin_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => TRUE,
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('superadmin');

        // Insert default superadmin user
        $this->db->insert('superadmin', [
            'username' => 'superadmin',
            'password' => password_hash('sup3r4dmin', PASSWORD_DEFAULT)
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_table('superadmin');
    }
}
