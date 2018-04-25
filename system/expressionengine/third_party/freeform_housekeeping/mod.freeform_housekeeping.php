<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Freeform_housekeeping {

    public $_housekeep_after = 3; // 3 months
    public $_housekeep_exclude_form_ids = []; // An array of form ids to exclude from housekeeping

	public function __construct() {
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}

    function clean_old_entries()
    {

        // Select Form ID's so we can loop through each Freeform table
        $get_form_ids = "SELECT form_id FROM exp_freeform_forms";
        $form_ids = ee()->db->query($get_form_ids);

        // Loop through each Freeform table
        foreach ($form_ids->result_array() AS $freeform) {

            // Select all rows where entry_date is greater than the deletion period
            $get_entries = "SELECT * FROM exp_freeform_form_entries_" . $freeform['form_id'] . " WHERE FROM_UNIXTIME(entry_date) + INTERVAL " . $this->_housekeep_after . " MONTH < NOW()";
            $entry_result = ee()->db->query($get_entries);

            $entries = $entry_result->result_array();

            // Loop through each entry
            foreach ($entries AS $key => $entry) {

                // Search for related attachments
                $get_attachments = "SELECT * FROM exp_freeform_file_uploads WHERE form_id = " . $freeform['form_id'] . " AND entry_id = " . $entry['entry_id'];
                $attachment_result = ee()->db->query($get_attachments);

                // If attachments, delete the file from the uploads folder and delete the reference from the DB
                if ($attachment_result) {

                    // Delete the file (Ampersand prevents output of errors if file is already gone)
                    foreach ($attachment_result->result_array() AS $attachment) {
                        @unlink($attachment['server_path'] . $attachment['filename']);
                        $delete_attachment_reference = "DELETE FROM exp_freeform_file_uploads WHERE form_id = " . $freeform['form_id'] . " AND entry_id = " . $entry['entry_id'];
                        $attachment_reference_result = ee()->db->query($delete_attachment_reference);
                    }

                }

                $delete_entry = "DELETE FROM exp_freeform_form_entries_" . $freeform['form_id'] . " WHERE entry_id = " . $entry['entry_id'];
                $delete_entry_result = ee()->db->query($delete_entry);

            }

        }

    }

}

/* End of file mod.freeform_housekeeping.php */
/* Location: ./system/expressionengine/third_party/freeform_housekeeping/mod.freeform_housekeeping.php */
