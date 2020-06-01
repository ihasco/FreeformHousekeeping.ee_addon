<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Get config file
require_once PATH_THIRD.'freeform_housekeeping/config.php';

class Freeform_housekeeping {

    public $housekeep_after = 12; // i.e. 12 months

	public function __construct() {

	}

	// --------------------------------------------------------------------

	/* http://www.ihasco.co.uk?ACT=171 */
    function clean_old_entries()
    {

        // Select Form ID's so we can loop through each Freeform table
        $get_form_ids = "SELECT form_id FROM exp_freeform_forms";
        $form_ids = ee()->db->query($get_form_ids);

        // Loop through each Freeform table
        foreach ($form_ids->result_array() AS $freeform) {

            // Select all rows where entry_date is greater than the deletion period
            $get_entries = "SELECT * FROM exp_freeform_form_entries_" . $freeform['form_id'] . " WHERE FROM_UNIXTIME(entry_date) + INTERVAL " . $this->housekeep_after . " MONTH < NOW()";
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
