<?php use Cake\Routing\Router ?>
<div class="row">
  <div class="col-md-12">
    <h1>Reports</h1>
    <form class="form-inline" id="state-form"
        action=" <?php echo Router::url('/reports/mass_action/'); ?> "
        method="post">
      <div class="form-group table-responsive">
        <table id="reports_table"
            class="table table-hover table-condensed table-striped"
            data-ajax-url="<?php
            echo Router::url([
              'controller' => 'reports',
              'action' => 'data_tables']);
          ?>">
          <thead>
            <tr>
              <th>Select</th>
              <th>ID</th>
              <th>Exception Name</th>
              <th>Message</th>
              <th>PMA Version</th>
              <th>Status</th>
              <th>Exception Type</th>
              <th>Incident count</th>
            </tr>
            <tr>
              <th></th>
              <th><input class="form-control" id="id_filter" style="width: 100%;margin-left:-10px" type="number"/></th>
              <th>
                <select class="form-control" id="error_name_filter" style="width: 100%">
                  <option></option>
                  <?php
                    foreach ($distinct_error_names as $id => $name) {
                      echo "<option value='$name'>$name</option>";
                    }
                  ?>
                </select>
              </th>
              <th></th>
              <th>
                <select class="form-control" id="pma_version_filter" style="width: 100%">
                  <option></option>
                  <?php
                    foreach ($distinct_versions as $id => $version) {
                      echo "<option value='$version'>$version</option>";
                    }
                  ?>
                </select>
              </th>
              <th>
                <select class="form-control" id="status_filter" style="width: 100%">
                  <option></option>
                  <?php
                    foreach ($distinct_statuses as $id => $status) { 
                      echo "<option value='$status'>$statuses[$status]</option>";
                    }
                  ?>
                </select>
              </th>
              <th>
                <select class="form-control" id="exception_type_filter" style="width: 100%">
                  <option></option>
                  <option value='0'>js</option>
                  <option value='1'>php</option>
                </select>
              </th>
              <th>
              </th>
            </tr>
          </thead>

          <tbody>
          </tbody>
          <tfoot>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
      <div style="margin:10px; clear:both;">
        <input class="form-control" type="checkbox" id="resultsForm_checkall" class="checkall_box" title="Check All" style="display: inline-block; margin:0;">
        <label for="resultsForm_checkall" style="pointer:cursor; display: inline-block;">Check All</label>
        <span style="margin-left: 2em">With <i>selected </i>Change state to:</span>
        <?php echo $this->Form->select('state', $statuses, array('empty' => false)); ?>
        <input class="form-control" type="submit" value="Change" class="btn btn-primary" />
      </div>
    </form>
  </div>
</div>