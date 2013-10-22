<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
	<div id="tabs" class="htabs">
      <a href="#tab-module"><?php echo $tab_modules; ?></a>
      <a href="#tab-mailchimp"><?php echo $tab_mailchimp; ?></a>
      <a href="#tab-mailcampaign"><?php echo $tab_mailcampaign; ?></a>
      <a href="#tab-email"><?php echo $tab_email; ?></a>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab-module">
	  <table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><span class="required">*</span><?php echo $entry_title; ?></td>
            <td class="left"><?php echo $entry_name; ?></td>
            <td class="left"><?php echo $entry_store; ?></td>
            <td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_position; ?></td>
            <td class="left"><?php echo $entry_status; ?></td>
            <td class="right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
          	<td class="left">
                <?php foreach ($languages as $language) { ?>
                <img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" />
                <input type="text" name="<?php echo $classname; ?>_module[<?php echo $module_row; ?>][language_id][<?php echo $language['language_id']; ?>]" value="<?php if (!empty($module['language_id'][$language['language_id']])) { echo $module['language_id'][$language['language_id']];} ?>">
                <br />
                <?php } ?>
                <span class="error"><?php echo $error_title; ?></span>
            </td>
            <td class="left">
              <select name="<?php echo $classname; ?>_module[<?php echo $module_row; ?>][name]">
	 		    <option value="off" <?php if ($module['name'] == 'off') { echo 'selected="selected"'; }?>><?php echo $text_off; ?></option>
 			    <option value="optional" <?php if ($module['name'] == 'optional') { echo 'selected="selected"'; }?>><?php echo $text_optional; ?></option>
			    <option value="required" <?php if ($module['name'] == 'required') { echo 'selected="selected"'; }?>><?php echo $text_required; ?></option>
	  		  </select>
	  		</td>
            <td class="left"><select name="<?php echo $classname; ?>_module[<?php echo $module_row; ?>][store_id]">
                <option value="0"><?php echo $text_default; ?></option>
                <?php foreach ($stores as $store) { ?>
                <?php if ($store['store_id'] == $module['store_id']) { ?>
                <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
			</td>
            <td class="left"><select name="<?php echo $classname; ?>_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
			</td>
            <td class="left"><select name="<?php echo $classname; ?>_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>
            <td class="left"><select name="<?php echo $classname; ?>_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="<?php echo $classname; ?>_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td id="button-col"></td>
            <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
	  </div>

	  <div id="tab-email">
          
    <?php if ($error_newsletter_override_yes) { ?>
    <div class="warning"><?php echo $error_newsletter_override; ?></div>
    <?php } ?>
	  <table class="form">
  	    <tr>
          <td><?php echo $entry_filter; ?></td>
          <td><input type="text" id="filter" value="@" size="50" onkeyup="$('#emails').html('<p><img src=&quot;view/image/newsletter_loading.gif&quot; /></p>');$('#emails').load('index.php?route=module/newsletter/autocomplete&token=<?php echo $token;?>&filter=' + escape(this.value));" /></td>
        </tr>
        <tr>
          <td width="25%"><?php echo $entry_emails; ?></td>
          <td><div id="emails" style="border: 1px solid #CCC; width: 350px; height: 100px; background: white; overflow-y: scroll; margin-bottom: 5px; height: 380px;"></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_add; ?></td>
          <td>
            <input type="text" id="newsletter_add_email" value="<?php echo $text_email; ?>" size="50" /><br/>
            <input type="text" id="newsletter_add_name" value="<?php echo $text_name; ?>" size="50" /><br/>
            <input type="text" id="newsletter_add_name2" value="<?php echo $text_name2; ?>" size="50" />
            <a onclick="$('#emails').html('<p><img src=&quot;view/image/newsletter_loading.gif&quot; /></p>');$('#emails').load('index.php?route=module/newsletter/insert&token=<?php echo $token;?>&email='+ escape($('#newsletter_add_email').val()) +'&name='+ escape($('#newsletter_add_name').val()) +'&name2='+ escape($('#newsletter_add_name2').val()) +'&filter=' + escape($('#filter').val())); $('#newsletter_add_email').val(''); $('#newsletter_add_name').val(''); $('#newsletter_add_name2').val('');" class="button"><span><?php echo $button_add; ?></span></a>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_delete; ?></td>
          <td>
            <input type="text" id="newsletter_delete_email" value="<?php echo $text_email; ?>" size="50" />
            <a onclick="$('#emails').html('<p><img src=&quot;view/image/newsletter_loading.gif&quot; /></p>'); $('#emails').load('index.php?route=module/newsletter/delete&token=<?php echo $token;?>&email='+ escape($('#newsletter_delete_email').val()) + '&filter=' + escape($('#filter').val())); $('#newsletter_delete_email').val('');" class="button"><span><?php echo $button_delete; ?></span></a>
          </td>
        </tr>
      </table>
	  </div>

	  <div id="tab-mailchimp">
	  <table class="form">
  	    <tr>
          <td><?php echo $mailchimp_enabled; ?></td>
          <td><?php if ($newsletter_mailchimp_enabled) { ?>
                <input type="radio" name="newsletter_mailchimp_enabled" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_enabled" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailchimp_enabled" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_enabled" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $mailchimp_apikey; ?></td>
          <td>
            <input type="text" name="newsletter_mailchimp_apikey" value="<?php echo $newsletter_mailchimp_apikey; ?>" size="50" />
          </td>
        </tr>
        <?php
        if (is_array($newsletter_mailchimp_lists))
        {
        ?>
        <tr>
          <td><?php echo $mailchimp_lists; ?></td>
          <td>
            <select name="newsletter_mailchimp_listid">
            <?php
            foreach ($newsletter_mailchimp_lists as $listid => $listname)
            {
              echo '<option value="'.$listid.'"'.($listid == $newsletter_mailchimp_listid ? ' selected="selected"' : '').'>'.$listname.'</option>';
            }
            ?>
            </select>
          </td>
        </tr>
        <?php
        } else {
        ?>
        <tr>
          <td><?php echo $mailchimp_listid; ?></td>
          <td><input type="text" name="newsletter_mailchimp_listid" value="<?php echo $newsletter_mailchimp_listid; ?>" size="10" /></td>
        </tr>
        <?php
        } // end if
        ?>
<!------------------------------- Added AV 12/04/2013 ------------------------------------------------------->
    <?php
        if (is_array($newsletter_mailchimp_lists))
        {
        ?>
        <tr>
          <td><?php echo $mailchimp_account_lists; ?></td>
          <td>
            <select name="newsletter_mailchimp_account_listid">
                <option><?php echo $text_none; ?></option>
            <?php
            foreach ($newsletter_mailchimp_lists as $listid => $listname)
            {
              echo '<option value="'.$listid.'"'.($listid == $newsletter_mailchimp_account_listid ? ' selected="selected"' : '').'>'.$listname.'</option>';
            }
            ?>
            </select>
          </td>
        </tr>
        <?php
        } else {
        ?>
        <tr>
          <td><?php echo $mailchimp_account_listid; ?></td>
          <td><input type="text" name="newsletter_mailchimp_account_listid" value="<?php echo $newsletter_mailchimp_account_listid; ?>" size="10" /></td>
        </tr>
        <?php
        } // end if
        ?>
        
        <tr>
          <td><?php echo $mailchimp_account_optin; ?></td>
          <td><?php if ($newsletter_mailchimp_account_optin) { ?>
                <input type="radio" name="newsletter_mailchimp_account_optin" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_account_optin" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailchimp_account_optin" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_account_optin" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
        
        <?php
        if (is_array($newsletter_mailchimp_lists))
        {
        ?>
        <tr>
          <td><?php echo $mailchimp_checkout_lists; ?></td>
          <td>
            <select name="newsletter_mailchimp_checkout_listid">
                <option><?php echo $text_none; ?></option>
            <?php
            foreach ($newsletter_mailchimp_lists as $listid => $listname)
            {
              echo '<option value="'.$listid.'"'.($listid == $newsletter_mailchimp_checkout_listid ? ' selected="selected"' : '').'>'.$listname.'</option>';
            }
            ?>
            </select>
          </td>
        </tr>
        <?php
        } else {
        ?>
        <tr>
          <td><?php echo $mailchimp_checkout_listid; ?></td>
          <td><input type="text" name="newsletter_mailchimp_checkout_listid" value="<?php echo $newsletter_mailchimp_checkout_listid; ?>" size="10" /></td>
        </tr>
        <?php
        } // end if
        ?>
        
        <tr>
          <td><?php echo $mailchimp_checkout_optin; ?></td>
          <td><?php if ($newsletter_mailchimp_checkout_optin) { ?>
                <input type="radio" name="newsletter_mailchimp_checkout_optin" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_checkout_optin" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailchimp_checkout_optin" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_checkout_optin" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
<!------------------------------------- Added AV 12/04/2013 ------------------------------------------------->
        <tr>
          <td><?php echo $mailchimp_double_optin; ?></td>
          <td><?php if ($newsletter_mailchimp_double_optin) { ?>
                <input type="radio" name="newsletter_mailchimp_double_optin" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_double_optin" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailchimp_double_optin" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_double_optin" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $mailchimp_update_existing; ?></td>
          <td><?php if ($newsletter_mailchimp_update_existing) { ?>
                <input type="radio" name="newsletter_mailchimp_update_existing" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_update_existing" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailchimp_update_existing" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_update_existing" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $mailchimp_send_welcome; ?></td>
          <td><?php if ($newsletter_mailchimp_send_welcome) { ?>
                <input type="radio" name="newsletter_mailchimp_send_welcome" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_send_welcome" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailchimp_send_welcome" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailchimp_send_welcome" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $mailchimp_custom_fields; ?></td>
          <td><input type="text" name="newsletter_mailchimp_custom_fields" value="<?php echo $newsletter_mailchimp_custom_fields ?>" size="50" /></td>
        </tr>
      </table>
	  </div>
        <!----------------- Start tab Campaign ----------------------->
        <div id="tab-mailcampaign">
	  <table class="form">
  	    <tr>
          <td><?php echo $mailcampaign_enabled; ?></td>
          <td><?php if ($newsletter_mailcampaign_enabled) { ?>
                <input type="radio" name="newsletter_mailcampaign_enabled" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailcampaign_enabled" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailcampaign_enabled" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailcampaign_enabled" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $mailcampaign_apikey; ?></td>
          <td>
            <input type="text" name="newsletter_mailcampaign_apikey" value="<?php echo $newsletter_mailcampaign_apikey; ?>" size="50" />
          </td>
        </tr>
        <?php 
        if (is_array($newsletter_mailcampaign_clients) && $this->user->isSuperuser())
        {
        ?>
        <tr>
          <td><?php echo $mailcampaign_client_id; ?></td>
          <td>
            <select name="newsletter_mailcampaign_client_id">
                <option></option>
            <?php
            foreach ($newsletter_mailcampaign_clients as $ctlistid => $listname)
            {
              echo '<option value="'.$ctlistid.'"'.($ctlistid == $newsletter_mailcampaign_client_id ? ' selected="selected"' : '').'>'.$listname.'</option>';
            }
            ?>
            </select>
          </td>
        </tr>
        <?php
        } else {
        ?>
        <tr>
          <td><?php echo $mailcampaign_client_id; ?></td>
          <td><input type="text" name="newsletter_mailcampaign_client_id" value="<?php echo $newsletter_mailcampaign_client_id; ?>" size="10" /></td>
        </tr>
        <?php
        } // end if
        ?>
        <?php 
        if (is_array($newsletter_mailcampaign_lists))
        {
        ?>
        <tr>
          <td><?php echo $mailcampaign_lists; ?></td>
          <td>
            <select name="newsletter_mailcampaign_listid">
            <?php
            foreach ($newsletter_mailcampaign_lists as $listid => $listname)
            {
              echo '<option value="'.$listid.'"'.($listid == $newsletter_mailcampaign_listid ? ' selected="selected"' : '').'>'.$listname.'</option>';
            }
            ?>
            </select>
          </td>
        </tr>
        <?php
        } else {
        ?>
        <tr>
          <td><?php echo $mailcampaign_listid; ?></td>
          <td><input type="text" name="newsletter_mailcampaign_listid" value="<?php echo $newsletter_mailcampaign_listid; ?>" size="10" /></td>
        </tr>
        <?php
        } // end if
        ?>
        <?php 
        if (is_array($newsletter_mailcampaign_lists))
        {
        ?>
        <tr>
          <td><?php echo $mailcampaign_account_lists; ?></td>
          <td>
            <select name="newsletter_mailcampaign_account_listid">
                <option><?php echo $text_none; ?></option>
            <?php
            foreach ($newsletter_mailcampaign_lists as $listid => $listname)
            {
              echo '<option value="'.$listid.'"'.($listid == $newsletter_mailcampaign_account_listid ? ' selected="selected"' : '').'>'.$listname.'</option>';
            }
            ?>
            </select>
          </td>
        </tr>
        <?php
        } else {
        ?>
        <tr>
          <td><?php echo $mailcampaign_account_listid; ?></td>
          <td><input type="text" name="newsletter_mailcampaign_account_listid" value="<?php echo $newsletter_mailcampaign_account_listid; ?>" size="10" /></td>
        </tr>
        <?php
        } // end if
        ?>

        <tr>
          <td><?php echo $mailcampaign_account_optin; ?></td>
          <td><?php if ($newsletter_mailcampaign_account_optin) { ?>
                <input type="radio" name="newsletter_mailcampaign_account_optin" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailcampaign_account_optin" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailcampaign_account_optin" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailcampaign_account_optin" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
        
        <?php 
        if (is_array($newsletter_mailcampaign_lists))
        {
        ?>
        <tr>
          <td><?php echo $mailcampaign_checkout_lists; ?></td>
          <td>
            <select name="newsletter_mailcampaign_checkout_listid">
                <option><?php echo $text_none; ?></option>
            <?php
            foreach ($newsletter_mailcampaign_lists as $listid => $listname)
            {
              echo '<option value="'.$listid.'"'.($listid == $newsletter_mailcampaign_checkout_listid ? ' selected="selected"' : '').'>'.$listname.'</option>';
            }
            ?>
            </select>
          </td>
        </tr>
        <?php
        } else {
        ?>
        <tr>
          <td><?php echo $mailcampaign_checkout_listid; ?></td>
          <td><input type="text" name="newsletter_mailcampaign_checkout_listid" value="<?php echo $newsletter_mailcampaign_checkout_listid; ?>" size="10" /></td>
        </tr>
        <?php
        } // end if
        ?>
        
        <tr>
          <td><?php echo $mailcampaign_checkout_optin; ?></td>
          <td><?php if ($newsletter_mailcampaign_checkout_optin) { ?>
                <input type="radio" name="newsletter_mailcampaign_checkout_optin" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailcampaign_checkout_optin" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="newsletter_mailcampaign_checkout_optin" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="newsletter_mailcampaign_checkout_optin" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $mailcampaign_custom_fields; ?></td>
          <td><input type="text" name="newsletter_mailcampaign_custom_fields" value="<?php echo $newsletter_mailcampaign_custom_fields ?>" size="50" /></td>
        </tr>
<!---------------------------------------- End AV 12/04/2013 --------------------------------------------------->
        
      </table>
	  </div>
        <!------------------- End tab Campaign ------------------------>
    </form>
  </div>
</div>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left">';
    <?php foreach ($languages as $language) { ?>
    html += '      <img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" />';
    html += '      <input type="text" name="<?php echo $classname; ?>_module[' + module_row + '][language_id][<?php echo $language['language_id']; ?>]" value="">';
    html += '      <br />';
    <?php } ?>
    html += '    </td>';
    html += '    <td class="left"><select name="<?php echo $classname; ?>_module[' + module_row + '][name]">';
	html += '      <option value="off"><?php echo $text_off; ?></option>';
	html += '      <option value="optional"><?php echo $text_optional; ?></option>';
	html += '      <option value="required"><?php echo $text_required; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="<?php echo $classname; ?>_module[' + module_row + '][store_id]">';
	html += '    <option value="0"><?php echo $text_default; ?></option>';
	<?php foreach ($stores as $store) { ?>
	html += '      <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="<?php echo $classname; ?>_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="<?php echo $classname; ?>_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="<?php echo $classname; ?>_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="<?php echo $classname; ?>_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#module tfoot').before(html);

	module_row++;
}
//--></script>

<script type="text/javascript"><!--
$('.htabs a').tabs();
$('#button-col').attr('colspan', $('#module thead tr td').length-1);
$('#emails').load('index.php?route=module/newsletter/autocomplete&token=<?php echo $token;?>&filter=' + escape($('#filter').val()));
//--></script>
<?php echo $footer; ?>