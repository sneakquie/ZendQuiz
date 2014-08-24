<?php
class Application_Model_Question
{
    protected $_question;
    protected $_created;
    protected $_answers;
    protected $_id;

    public function __construct(array $options = null)
    {
        if(is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if(('mapper' == $name) || !method_exists($this, $method))
            throw new Exception('Invalid quiz property');
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if(('mapper' == $name) || !method_exists($this, $method))
            throw new Exception('Invalid quiz property');
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if(in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
    public function setQuestion($question)
    {
        $this->_question = (string) $question;
        return $this;
    }
    public function getQuestion()
    {
        return $this->_question;
    }
 
    public function setAnswers($answers)
    {
        $this->_answers = (string) $answers;
        return $this;
    }
    public function getAnswers()
    {
        return $this->_answers;
    }
    public function getAnswersArray()
    {
        return (is_string($this->_answers) && strlen($this->_answers) > 0)
                ? explode(',', $this->_answers)
                : array();
    }
 
    public function setCreated($ts)
    {
        $this->_created = $ts;
        return $this;
    }
    public function getCreated()
    {
        return $this->_created;
    }
 
    public function setId($id)
    {
        $this->_id = intval($id);
        return $this;
    }
    public function getId()
    {
        return $this->_id;
    }
}