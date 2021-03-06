<?php

class Controller_Base extends \Controller_Template
{

	public $template = 'template.twig';

	public function before()
    {
        parent::before(); // Without this line, templating won't work!

        if (\Auth::check())
		{
			# Set user info
			list(, $userid) = \Auth::get_user_id();
			$this->template->set_global('auth', [
				'user' => [
					'screen_name'		 => \Auth::get_screen_name(),
					'group'				 => \Auth::group()->get_name(),
				]
			], false);

		}

    }

}