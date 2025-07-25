(function($){
  $(window).on('elementor:init', function() {
    var BaseIntegrationModule = elementorPro.modules.forms.mailchimp.__proto__.constructor;

    var Doppler = BaseIntegrationModule.extend({
      getName() {
        return 'doppler';
      },
      getUserFields() {
        this.getEditorControlView('doppler_fields_map').updateMap(dopplerFields);
      },
      onSectionActive() {
        this.getUserFields();
      }
    });

    elementorPro.modules.forms.doppler = new Doppler('form');
  });

  elementor.channels.editor.on('section:activated', function(sectionName) {
    if (sectionName === 'doppler_section') {
      if (elementorPro.modules.forms.doppler) {
        elementorPro.modules.forms.doppler.onSectionActive();
      }
    }
  });
})(jQuery);