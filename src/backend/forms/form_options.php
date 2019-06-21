<?php
namespace pdima88\icms2bonuscode\backend\forms;

use cmsForm;
use fieldCheckbox;

class form_options extends cmsForm {

    public function init() {

        return array(

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldCheckbox('case_sensitive', array(
                        'title' => 'Регистр букв в бонус кодах имеет значение (A =/= a)',
                    )),

                )
            )

        );

    }

}
