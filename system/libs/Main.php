<?php 

class Main{
	public $url;
	public $controllerName = "index";//tên file
	public $methodName = "index";//tên phương thức 
	public $controllerPath = "app/controllers/";
	public $controller;// Sẽ được cho là class ở phía dưới

	public function __construct(){
		$this->getUrl();
		$this->loadController();
		$this->callMethod();
	}
	

	public function getUrl(){
		$this->url = isset($_GET['url']) ? $_GET['url'] : NULL;
			
		if($this->url!=NULL){
			$this->url = rtrim($this->url, '/');
			$this->url = explode('/', filter_var($this->url, FILTER_SANITIZE_URL));
		}else{
			unset($this->url);
		}
	}
	//tên file/ tên phương thức/chỉ số id
	//Hàm gọi file đồng thời gọi cả lớp của file php đó
	public function loadController(){
		if(!isset($this->url[0])){ 
			include $this->controllerPath.$this->controllerName.'.php';
			$this->controller = new $this->controllerName();
		}else{

			$this->controllerName = $this->url[0];
			//Giả sử đường dẫn tới file php đó 
			$fileName = $this->controllerPath.$this->controllerName.'.php';
			//Sau đó tiến hành kiểm tra
			if(file_exists($fileName)){
				include $fileName;
				//Vì tên class cũng chính là tên của 1 file chứa class đó
				if(class_exists($this->controllerName)){
					$this->controller = new $this->controllerName();
				}else{

				}

			}else{

			}
		}

	}
//tên class/ tên phương thức/chỉ số id
	public function callMethod(){
		if(isset($this->url[2])){
			$this->methodName = $this->url[1];//có tham số thì sẽ phải có phương thức trong class đó
			//tiếp tục kiểm tra trong class đó có phương thức này tồn tại không ?
			if(method_exists($this->controller, $this->methodName)){

				$this->controller->{$this->methodName}($this->url[2]);

			}
			//Nếu không tồn tại phương thức đó thì sẽ quay hẳn về trang index
			else{
				header("Location:".BASE_URL."/index/notfound");
			}
		}
		else{
			//không có id nhưng có phương thức trên URL
			if(isset($this->url[1])){
				//Gắn biến methodName chính là phương thức đó 
				$this->methodName = $this->url[1];
				//Tiếp tục là kiểm tra xem trong class đó có phương thức này không
				if(method_exists($this->controller, $this->methodName)){

				$this->controller->{$this->methodName}();
				//Phương thức trên url khác tên không giống bất kỳ 1 phương thức nào trong class
				}else{
					header("Location:".BASE_URL."/index/notfound");
				}
			}
			//Tình huống kế tiếp là không có phương thức nhập trên url
			else{
				//Tiếp tục kiểm tra xem trong class thì có tồn tại bất kì  phương thức nào không?
				if(method_exists($this->controller, $this->methodName)){

				$this->controller->{$this->methodName}();

				}else{
					header("Location:".BASE_URL."/index/notfound");
				}

			}
		}
	}

	
}

?>