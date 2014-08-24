<?php
class Application_Form_Question extends Zend_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
 
        // Add an email element
        $this->addElement('textarea', 'question', array(
            'label'      => 'Your question:',
            'rows'       => 5,
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(1, 256))
            )
        ));
 
        $this->addElement('textarea', 'answers', array(
            'label'      => 'Answer variants (separated with comma):',
            'rows'       => 5,
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(1, 256))
            )
        ));
 
        // Add a captcha
        $this->addElement('captcha', 'captcha', array(
            'label'      => 'Please enter the 5 letters displayed below:',
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Add question',
        ));
 
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}
