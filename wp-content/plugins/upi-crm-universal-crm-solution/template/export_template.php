<script type="text/javascript">

    $j(document).ready(function () {

        $j( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );

        $j( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );



        $j("#all_field_checkbox").click(function () {

            if ($j("#all_field_checkbox").is(':checked')) {

                $j("#checkbox_list input[type=checkbox]").each(function () {

                    $j(this).prop("checked", true);

                });



            } else {

                $j("#checkbox_list input[type=checkbox]").each(function () {

                    $j(this).prop("checked", false);

                });

            }

        });

    } );

</script>

<style>

    .ui-tabs-vertical { width: 55em; }

    .ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em; }

    .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }

    .ui-tabs-vertical .ui-tabs-nav li a { display:block; }

    .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }

    .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 40em;}

    .ui-tabs-vertical .ui-tabs-panel{width:85%;}

    .ui-tabs-vertical .ui-tabs-nav{width:15%;}

    .ui-tabs-vertical{width:100%;}

    #tabs{ letter-spacing:0;}

</style>

<?php

if(isset($_POST['filteredExport']) && $_POST['filteredExport'] <> ''){

    if($_POST['fields'] == ''){

        echo '<script type="text/javascript">alert("Please choose at least one field to apply filters on export!");</script>';

    }

    else

        upicrm_filtered_excel_output();

}



global $wpdb;

$table_prefix = $wpdb->prefix;

?>


        <h2>Export</h2>

        <form name="export_leads" method="post" action="" >

            <strong>Select Date range</strong><br>

            <input type="text" name="date_start" class="datepicker" placeholder="Start">

            &nbsp;

            <input type="text" name="date_end" class="datepicker" placeholder="End">

            <br><br>

            <strong>Select Fields</strong><br><br />

            <?php
            //fields from Name to Received From
            $fields = $wpdb->get_results("Select * from ".$table_prefix."upicrm_fields order by field_id ASC");

            ?>

            <input type="checkbox" name="all_fields" id="all_field_checkbox" value="all">&nbsp;Select All

            <br><br />

            <ul id="checkbox_list">
                <?php for($i=0;$i<count($fields);$i++){?>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="<?php echo ($fields[$i]->field_id);?>">&nbsp;<?php echo ($fields[$i]->field_name);?></li>
                <?php }?>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="lead_id">&nbsp;ID</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="time">&nbsp;Timestamp</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="user_referer">&nbsp;Referer</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="source_id">&nbsp;Form Name</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="user_id">&nbsp;Assigned To</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="lead_status_id">&nbsp;Lead Status</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="lead_management_comment">&nbsp;Lead Managment comment</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="utm_source">&nbsp;UTM Source</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="utm_medium">&nbsp;UTM Medium</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="utm_term">&nbsp;UTM Term</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="utm_content">&nbsp;UTM Content</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="utm_campaign">&nbsp;UTM Campaign</li>
                <li style="list-style:none;"><input type="checkbox" name="fields[]" class="" value="integration_domain">&nbsp;Remote server domain</li>
            </ul>

            <br><br>

            <strong>Status</strong>&nbsp;

            <?php

            $statuses = $wpdb->get_results("Select * from ".$table_prefix."upicrm_leads_status order by lead_status_name ASC");

            ?>

            <select name="status">

                <option value="">Please choose</option>

                <?php for($i=0;$i<count($statuses);$i++){?>

                    <option value="<?php echo ($statuses[$i]->lead_status_id);?>"><?php echo ($statuses[$i]->lead_status_name);?></option>

                <?php }?>

            </select>

            <br><br>

            <input type="submit" name="filteredExport" value="Export filtered leads data to Excel">

        </form>

        <br /><br />

        OR

        <br /><br />

        <a href="admin.php?page=upicrm_export&action=excel_output" class="btn btn-default">

            <i class="glyphicon glyphicon-export"></i> <?php _e('Export all leads data to Excel ','upicrm'); ?>

        </a>

        <br /><br />

        <a href="admin.php?page=upicrm_export&action=export_csv" class="btn btn-default">

            <i class="glyphicon glyphicon-export"></i> <?php _e('Export all leads data to CSV ','upicrm'); ?>

           </a>


<!-- widget grid -->
