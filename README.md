# Freeform Housekeeping ExpressionEngine

The **Freeform Housekeeping** Add-on was written by @samc449 and comprises a Module which provide the tools necessary to automate the process of cleaning up old freeform entries from your [ExpressionEngine](http://www.ellislab.com/expressionengine) website. It has been designed as a companion to [Solspace Freeform](https://solspace.com/expressionengine/freeform) Module.

## Using this module

Install the Freeform Housekeeping Module and then take a look in the `exp_actions` table for your ExpressionEngine database to locate the action ID assigned to the module. You will then need to set up a CRON job to hit that 'action URL' on a regular basis. We recommend a daily CRON task. You would have this call the URL in question, i.e. http://example.com?ACT=123. This triggers the 'clean_old_entries' function in the module which deletes all entries older than 3 months.

You can adjust the interval for deletion by editing the value of the `$_housekeep_after` variable in `mod.freeform_housekeeping.php`.
