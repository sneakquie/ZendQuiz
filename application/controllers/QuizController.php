<?php
class QuizController extends Zend_Controller_Action
{
    /**
     * Contains session object
     * @type Object
     */
    protected $_session;

    /**
     * Init Controller
     * @return void
     */
    public function init()
    {
        // Init session
        $this->_session = new Zend_Session_Namespace('quiz');
        // There is some messages
        if($this->_helper->FlashMessenger->hasMessages()) {
            $this->view->message = $this->_helper->FlashMessenger->getMessages();
        }
        if(isset($this->_session->quiz_points)) {
            $this->view->quiz_points = $this->_session->quiz_points;
        } else {
            $this->view->quiz_points = 0;
        }
    }

    /**
     * Default action, show all questions
     * @return void
     */
    public function indexAction()
    {
        // Create mapper
        $question = new Application_Model_QuestionMapper();
        // Get all questions
        $this->view->entries = $question->fetchAll();
        // User already answered some questions
        if(isset($this->_session->done_questions)) {
            $this->view->done_questions = $this->_session->done_questions;
        }
    }

    /**
     * Add question
     * @return void
     */
    public function addAction()
    {
        // Get request, create a form
        $request = $this->getRequest();
        $form    = new Application_Form_Question();
 
        /*
         * Request is POST and valid
         */
        if($request->isPOST() && $form->isValid($request->getPOST())) {
            // Create model, mapper, save question, redirect
            $question = new Application_Model_Question($form->getValues());
            $mapper   = new Application_Model_QuestionMapper();
            $mapper->save($question);
            $this->_helper->flashMessenger->addMessage('Your question have been added!');
            return $this->_helper->redirector('index');
        }

        $this->view->form = $form;
    }

    /**
     * Show question, get answer
     * @return void
     */
    public function viewAction()
    {
        // Question ID from GET Request
        $question_id = intval($this->_getParam('question_id'));

        /*
         * ID is valid and question with this id exists
         */
        if( $question_id > 0
         && ($mapper = new Application_Model_QuestionMapper())
         && ($mapper->find($question_id, $question = new Application_Model_Question()) || 1)
         && $question->getId() > 0
        ) {
            /*
             * Question isn't already done by this user
             */
            if(!isset($this->_session->done_questions) || !in_array($question_id, $this->_session->done_questions)) {
                // Get request
                $request = $this->getRequest();
                // Create form, if user already posted right captcha once, then don't show it again
                $form    = new Application_Form_Answer(array(
                    'use_captcha' => !(isset($this->_session->captcha_success) && $this->_session->captcha_success),
                ));

                /*
                 * Request is POST and form is valid
                 */
                if($request->isPOST() && $form->isValid($request->getPOST())) {
                    // Set captcha success to true
                    $this->_session->captcha_success = true;

                    /*
                     * Get all questions answers variants, compare it to user answer
                     */
                    foreach($question->getAnswersArray() as $value) {
                        /*
                         * User have right answer, add points, set message, redirect
                         */
                        if(0 === strcasecmp($this->_getParam('answer'), trim($value))) {
                            // Add points to user
                            if(isset($this->_session->quiz_points)) {
                                $this->_session->quiz_points++;
                            } else {
                                $this->_session->quiz_points = 1;
                            }
                            // Set this question to DONE array
                            if(isset($this->_session->done_questions) && is_array($this->_session->done_questions)) {
                                $this->_session->done_questions[] = $question_id;
                            } else {
                                $this->_session->done_questions = array($question_id);
                            }

                            // Make message
                            $this->_helper->flashMessenger->addMessage('Your answer was right!');
                            // Redirect to question page
                            return $this->_helper->redirector->gotoRoute(array(
                                'question_id' => $question_id,
                            ), 'Quiz_View_Question');
                        }
                    }

                    // User posted data, but haven't right answer - create an error and mark it
                    $form->getElement('answer')->addError('You have wrong answer');
                    $form->markAsError();
                }
                $this->view->form = $form;
            } else {
                // User already answered this question
                $this->view->danger = 'You have already answered this question';
            }
            $this->view->question = $question;
        } else {
            // Question doesn't exists
            throw new Zend_Controller_Action_Exception('This page does not exist', 404);
        }
    }
}