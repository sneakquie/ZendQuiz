<?php
 
class Application_Model_QuestionMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Question');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Question $el)
    {
        $data = array(
            'question' => $el->getQuestion(),
            'answers'  => $el->getAnswers(),
            'created' => date('Y-m-d H:i:s'),
        );
 
        if (null === ($id = $el->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Question $el)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $el->setId($row->id)
           ->setQuestion($row->question)
           ->setAnswers($row->answers)
           ->setCreated($row->created);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Question();
            $entry->setId($row->id)
                  ->setQuestion($row->question)
                  ->setAnswers($row->answers)
                  ->setCreated($row->created);
            $entries[] = $entry;
        }
        return $entries;
    }
}