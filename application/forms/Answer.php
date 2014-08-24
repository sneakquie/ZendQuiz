<?php
class Application_Form_Answer extends Zend_Form
{
    protected $_use_captcha = true;

    public function setUse_captcha($value = true)
    {
        $this->_use_captcha = (boolean) $value;
        return $this;
    }

    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
 
        // Add an email element
        $this->addElement('text', 'answer', array(
            'label'      => 'Your answer here:',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        // Add a captcha
        if($this->_use_captcha) {
            $this->addElement('captcha', 'captcha', array(
                'label'      => 'Please enter the 5 letters displayed below:',
                'required'   => true,
                'captcha'    => array(
                    'captcha' => 'Figlet',
                    'wordLen' => 5,
                    'timeout' => 300
                )
            ));
        }
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Answer',
        ));
 
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}