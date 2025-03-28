<?php

class DPLR_Form_Shortcode{

    const FORM_CLASS = 'form_class';
	const FORM_ID = 'id';

    public function __construct() {

        add_shortcode('doppler-form', [$this,'dplr_shortcode']);
    }

    function dplr_shortcode($atts){
        $atts = shortcode_atts(array(
            self::FORM_ID => '',
            self::FORM_CLASS => '',
        ), $atts);

        $forms = DPLR_Form_Model::getAll();

        $found = false;
        for ($i=0; $i < count($forms); $i++) {   
            if ($forms[$i]->id == $atts[self::FORM_ID]) {
                $form = array('form' => DPLR_Form_Model::get($forms[$i]->id, true));
                $form['fields'] = DPLR_Field_Model::getBy(['form_id'=>$forms[$i]->id], ['sort_order'], true);
                $form['classes'] = explode(" ", $atts[self::FORM_CLASS]);
                ob_start();
                $formContainer = "<div> <h2 class='widget-title subheading heading-size-3'>" . $form['form']->title . "</h2>";
                $formContainer .= DPLR_Form_Helper::generate($form);
                $formContainer .= "</div>";
                echo $formContainer;
                
                $found = true;
            }
        }
        if ($found == true) {
            return ob_get_clean();
        } else {
            return "El id del form está mal " . $atts['id'] . (isset($atts['txt']) ? " " . $atts['txt'] : '');
        }
    }
}