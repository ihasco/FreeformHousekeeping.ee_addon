<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Freeform_housekeeping_upd {

	private $_module_name = 'Freeform Housekeeping';
    private $_module_version = '1.0';
    private $_module_package_name = 'Freeform_housekeeping';
	private $_cp_backend = 'n';
	private $_publish_fields = 'n';

	public function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();

		$this->EE->load->dbforge();
	}


    // ----------------------------------------------------------------

    /**
     * Installation Method
     *
     * @return  boolean     TRUE
     */
    public function install()
    {
        $mod_data = array(
            'module_name'        => $this->_module_package_name,
			'module_version'     => $this->_module_version,
			'has_cp_backend'     => $this->_cp_backend,
			'has_publish_fields' => $this->_publish_fields
        );

        ee()->db->insert('modules', $mod_data);

        $actions_data_array = array(
            array(
                'class' => $this->_module_package_name,
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
        $query = ee()->db->get_where('modules', array('module_name' => $this->_module_package_name));

        ee()->db->where('module_id', $query->row('module_id'));
        ee()->db->delete('module_member_groups');

        ee()->db->where('module_name', $this->_module_package_name);
        ee()->db->delete('modules');

        ee()->db->where('class', $this->_module_package_name);
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
