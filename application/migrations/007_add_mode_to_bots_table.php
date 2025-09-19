<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_mode_to_bots_table extends CI_Migration {

    public function up()
    {
        $fields = array(
            'mode' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'webhook',
                'null' => FALSE,
            ),
        );
        $this->dbforge->add_column('bots', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('bots', 'mode');
    }
}
