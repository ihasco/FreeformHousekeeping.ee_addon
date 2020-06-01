<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Get config file
require_once PATH_THIRD.'freeform_housekeeping/config.php';

class Freeform_housekeeping_upd {

	public $version = IHASCO_FREEFORM_HOUSEKEEPING_VER;
    public $package_name = IHASCO_FREEFORM_HOUSEKEEPING_PACKAGE;

    // ----------------------------------------------------------------

    /**
     * Installation Method
     *
     * @return  boolean     TRUE
     */
    public function install()
    {
		$mod_data = array(
            'module_name'           => $this->package_name,
            'module_version'        => $this->version,
            'has_cp_backend'        => "n",
            'has_publish_fields'    => 'n'
        );

        ee()->db->insert('modules', $mod_data);

        $actions_data_array = array(
            array(
                'class' => $this->package_name,
                'method' => 'clean_old_entries'
                )
            );
        foreach ($actions_data_array as $actions_data) {
            ee()->db->insert('actions', $actions_data);
        }

        return TRUE;
    }

    // ----------------------------------------------------------------

    /**
     * Uninstall
     *
     * @return  boolean     TRUE
     */
    public function uninstall()
    {
        ee()->db->select('module_id');
        $query = ee()->db->get_where('modules', array('module_name' => $this->package_name));

        ee()->db->where('module_id', $query->row('module_id'));
        ee()->db->delete('module_member_groups');

        ee()->db->where('module_name', $this->package_name);
        ee()->db->delete('modules');

        ee()->db->where('class', $this->package_name);
        ee()->db->delete('actions');

        return TRUE;
    }

    // ----------------------------------------------------------------

    /**
     * Module Updater
     *
     * @return  boolean     TRUE
     */
    public function update($current = '')
    {
        // If you have updates, drop 'em in here.
        return TRUE;
    }

}
/* End of file upd.freeform_housekeeping.php */
/* Location: /system/expressionengine/third_party/freeform_housekeeping/upd.freeform_housekeeping.php */
