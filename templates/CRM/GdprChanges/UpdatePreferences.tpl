{literal}
<script type="text/javascript">
  CRM.$(function($) {
    $('#gdprchnages_unsubscribe').change(function () {
      if ($(this).is(':checked')) {
        $('input[name^="group_"]').prop('checked', false);
        $('#enable_email').val('NO');
      }
    });
    $('#enable_email').change(function () {
      if ($(this).val() == 'YES') {
        $('#gdprchnages_unsubscribe').prop('checked', false);
      }
    });
    $('input[name^="group_"]').change(function () {
      if ($(this).is(':checked')) {
        $('#gdprchnages_unsubscribe').prop('checked', false);
      }
    });
  });
</script>
{/literal}
