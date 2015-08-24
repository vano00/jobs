<?php
namespace Admin;

class Controller_Auth extends \Controller_Template
{

	public $template = 'template.twig';

	public function action_index()
		{
		    $data = array();

		    if (\Input::post())
		    {
		        $username = \Input::post('username');
		        $password = \Input::post('password');

		        if (\Auth::login($username,$password))
		        {

		        	// does the user want to be remembered?
			        if (\Input::post('remember_me'))
			        {
			            // create the remember-me cookie
			            \Auth::remember_me();
			        }
			        else
			        {
			            // delete the remember-me cookie if present
			            \Auth::dont_remember_me();
			        }

		            \Response::redirect_back('admin');
		        }
		        else
		        {
		            // Oops, no soup for you. Try to login again. Set some values to
		            // repopulate the username field and give some error text back to the view.
		            $data['username']    = $username;
		            \Session::set_flash('error', 'Wrong username/password combo. Try again');
		        }
		    }

		    // Show the login form.
		    $this->template->title = "Login";
			$this->template->content = \View::forge('login.twig',$data);
		}

		public function action_create_account()
		{

		    if (\Input::post())
		    {

		    	$user = \Input::post();
		    	$val = \Validation::forge();
		    	$val->add_field('fullname', 'fullname', 'required');
				$val->add_field('username', 'username', 'required');
				$val->add_field('password', 'password', 'required|min_length[3]|max_length[10]');
				$val->add_field('email', 'email', 'required|valid_email');

		        if ($val->run())
		        {
		            \Auth::create_user(
					    $user['username'],
					    $user['password'],
					    $user['email'],
					    1,
					    array(
					        'fullname' => $user['fullname'],
					    )
					);

					\Session::set_flash('success', 'The account has been successfully created');
					\Response::redirect('admin');

		        }
		        else
		        {
		            // repopulate the username field and give some error text back to the view.
		            $data['fullname'] = $user['fullname'];
		            $data['username'] = $user['username'];
		            $data['email'] = $user['email'];

		            \Session::set_flash('error', $val->error());
		        }
		    }

		    // Show the form.
		    $data['actions'] = [
			'back' => [
				'label' => 'Back',
				'url' => 'admin/login'
				]
			];
		    $this->template->title = "Create an account";
			$this->template->content = \View::forge('user/create.twig', $data);
		}

		public function action_logout()
		{
		    \Auth::logout();
		    
		    // delete the remember-me cookie if present
			\Auth::dont_remember_me();

		    \Response::redirect('/');
		}
}