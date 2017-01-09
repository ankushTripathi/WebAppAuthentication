


	public function register(Request $req,Response $res){
		$this->flash->addMessage('global','you have been registered');
		return $res->withStatus(302)->withHeader('location',$this->router->pathFor('home'));
	}