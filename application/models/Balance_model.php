<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Gets the balance for a specific user. If no balance record exists, it creates one.
     * @param int $user_id The user's ID.
     * @return array The user's balance record.
     */
    public function get_user_balance($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('balance');
        $balance_record = $query->row_array();

        if (!$balance_record) {
            // Create a new balance record for the user if it doesn't exist
            $initial_data = ['user_id' => $user_id, 'balance' => 0.00];
            $this->db->insert('balance', $initial_data);
            // Return the newly created record
            return $this->get_user_balance($user_id);
        }

        return $balance_record;
    }

    /**
     * Adds a new transaction and updates the user's balance accordingly.
     * Uses a database transaction to ensure atomicity.
     *
     * @param int    $user_id The user's ID.
     * @param string $type The type of transaction ('deposit', 'purchase', 'refund', 'withdrawal', 'admin_adjustment').
     * @param float  $amount The transaction amount (always a positive value).
     * @param int|null $related_entity_id Optional ID of a related entity (e.g., order_id).
     * @param string|null $description Optional description for the transaction.
     * @return bool True on success, false on failure.
     */
    public function add_transaction($user_id, $type, $amount, $related_entity_id = null, $description = null)
    {
        // Ensure amount is a positive value
        $amount = abs($amount);

        $this->db->trans_start();

        // 1. Get current balance and lock the row for update
        $current_balance_data = $this->db->query("SELECT * FROM balance WHERE user_id = ? FOR UPDATE", [$user_id])->row_array();
        
        // Create balance if it doesn't exist
        if (!$current_balance_data) {
            $this->get_user_balance($user_id);
            $current_balance_data = $this->db->query("SELECT * FROM balance WHERE user_id = ? FOR UPDATE", [$user_id])->row_array();
        }
        
        $current_balance = (float) $current_balance_data['balance'];

        // 2. Check for sufficient funds on debits
        $is_debit = in_array($type, ['purchase', 'withdrawal']);
        if ($is_debit && $current_balance < $amount) {
            $this->db->trans_rollback();
            return false; // Insufficient funds
        }

        // 3. Insert the transaction record
        $transaction_data = [
            'user_id' => $user_id,
            'type' => $type,
            'amount' => $amount,
            'related_entity_id' => $related_entity_id,
            'description' => $description
        ];
        $this->db->insert('balance_transactions', $transaction_data);

        // 4. Calculate new balance and update the balance table
        $new_balance = $is_debit ? ($current_balance - $amount) : ($current_balance + $amount);
        $this->db->where('user_id', $user_id);
        $this->db->update('balance', ['balance' => $new_balance]);

        // 5. Complete the transaction
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
