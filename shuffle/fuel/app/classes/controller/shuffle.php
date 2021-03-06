<?php
use Shuffle\DataReader;
use Shuffle\DataEditor;
use Shuffle\RelationsScore;
use Fuel\Core\Presenter;
use Shuffle\Shuffle;
use Shuffle\ShuffleFunction;
use Shuffle\DataWriter;
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Shuffle extends Controller
{

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		return Response::forge(View::forge('shuffle/index'));
	}
	
	public function action_show_staff_data(){
	    $reader = new DataReader();
	    $reader->read();
	    $presenter = Presenter::forge('shuffle/staffdata');
	    $presenter->json_persons = $reader->getPersonsArray();
	    $presenter->json_positions = $reader->getPositionsArray();
	    
	    $form_presenter = Presenter::forge('shuffle/form');
	    $form_presenter->json_organization = $reader->getOrganizationArray();
	    $form_presenter->json_departments = $reader->getDepartmentsArray();
	    $form_presenter->json_positions = $reader->getPositionsArray();
	    $presenter->organization_form = $form_presenter;

	    $presenter->json_departments = $reader->getDepartmentsArray();
		$presenter->json_organization = $reader->getOrganizationArray();
	    
	    return Response::forge($presenter);
	}
	
	public function post_add_member(){
	    $editior = new DataEditor();
	    $editior->addMember();
	    return $this->action_show_staff_data();
	}
	
	public function post_update_data(){
	    $editor = new DataEditor();
		$editor->update(Input::post('company_status_array'));
        return $this->action_show_staff_data();
	}

	public function action_shuffle(){
	    $presenter = Presenter::forge('shuffle/result');
	    $reader = new DataReader();
	    $reader->read();
	    $presenter->persons_array = $reader->getPersonsArray();
	    $presenter->position_array = $reader->getPositionsArray();
	    $presenter->department_array = $reader->getDepartmentsArray();
	    
	    $shuffle_function = new ShuffleFunction();
	    $shuffle_function->solve();
	    $presenter->result = $shuffle_function->getResult();
	    return Response::forge($presenter);
	}
	

	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_hello()
	{
		return Response::forge(Presenter::forge('welcome/hello'));
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('welcome/404'), 404);
	}
}
