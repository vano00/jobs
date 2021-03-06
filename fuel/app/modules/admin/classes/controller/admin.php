<?php
namespace Admin;

class Controller_Admin extends \Controller_Template
{

	public $template = 'template.twig';

	public function before()
    {
        parent::before(); // Without this line, templating won't work!

        if (\Auth::check())
		{

			// Check if the current user is an administrator
			if (!\Auth::member(100))
			{
				\Session::set_flash('error', 'You don\'t have the required access' );
			    \Response::redirect('auth');
			}
			
			# Set user info
			$this->template->set_global('auth', [
				'user' => [
					'screen_name'		 => \Auth::get_screen_name(),
					'group'				 => \Auth::group()->get_name()

				]
			], false);

		}
		else{
			\Response::redirect('auth');
		}

    }

}