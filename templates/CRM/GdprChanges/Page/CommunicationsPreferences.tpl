<div class='crm-gdprchanges-unsub-form-block'>
  <div class="label">{$form.enable_unsubscribe.label}</div>
  <div class="content">{$form.enable_unsubscribe.html}</div>
  <div class="clear"><br></div>
</div>

{literal}
<script type="text/javascript">
  CRM.$(function($) {
    $('#enable_groups').closest('.crm-section').find('div.label')
      .before($('div.crm-gdprchanges-unsub-form-block'));
  });
</script>
{/literal}
