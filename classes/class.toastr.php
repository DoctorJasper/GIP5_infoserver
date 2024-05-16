<?php

class toast {

	public function set($icon, $title, $small, $text, $type)
	{
		if(isset($_SESSION['toastr']))
		{
			$t=count($_SESSION['toastr']);
		} else {
			$t=0;
		}		
		$_SESSION['toastr'][$t]["title"] = $title;
    $_SESSION['toastr'][$t]["small"] = $small;
		$_SESSION['toastr'][$t]["text"] = $text;
		$_SESSION['toastr'][$t]["type"] = $type; // error, info, success, warning
    $_SESSION['toastr'][$t]["icon"] = $icon;
	}
}
