<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_webhook_url_to_bots_table extends CI_Migration {

    public function up()
    {
        $fields = array(
            'webhook_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
                'after' => 'api_key'
            )
        );
        $this->dbforge->add_column('bots', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('bots', 'webhook_url');
    }
}
