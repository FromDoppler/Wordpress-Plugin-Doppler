(function($){
  $(window).on('elementor:init', function() {
    var BaseIntegrationModule = null;

    // Elementor is constantly changing its internal JS API.
    // We try to find the base class in a few common locations to make the integration more robust.
    if (window.elementorPro) {
      // Modern path (versions 3.5+)
      if (elementorPro.modules.forms.classes && elementorPro.modules.forms.classes.Integration_Base) {
        BaseIntegrationModule = elementorPro.modules.forms.classes.Integration_Base;
      // Older, but common path (pre 3.5) by inferring from another integration
      } else if (elementorPro.modules.forms.mailchimp) {
        BaseIntegrationModule = elementorPro.modules.forms.mailchimp.__proto__.constructor;
      }
    }

    if (BaseIntegrationModule) {
      var Doppler = BaseIntegrationModule.extend({
        getName() {
          return 'doppler';
        },
        getUserFields() {
          var controlView = this.getEditorControlView('doppler_fields_map');
          if (controlView) {
            controlView.updateMap(dopplerFields);
          }
        },
        onSectionActive() {
          this.getUserFields();
        }
      });
      elementorPro.modules.forms.doppler = new Doppler('form');
    } else {
      console.error('Doppler Forms: Could not find a valid Elementor Pro form integration base class. The integration will not be available.');
    }
  });

  elementor.channels.editor.on('section:activated', function(sectionName) {
    if (sectionName === 'doppler_section') {
      if (elementorPro.modules.forms.doppler) {
        elementorPro.modules.forms.doppler.onSectionActive();
      }
    }
  });
})(jQuery);