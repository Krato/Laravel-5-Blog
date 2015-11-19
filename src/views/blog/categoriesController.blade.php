<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Controllers;

use {{$app_name}}Http\Requests;
use {{$app_name}}Http\Controllers\Controller;

use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }};
use {{$locale_model}};

use Illuminate\Http\Request;
use {{$app_name}}{{$blog_path}}\Requests\{{ ucfirst($categories) }}Request;

class {{ ucfirst($categories) }}Controller extends Controller {

	/**
     * @var {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }}
     */
    protected $categories;


    /**
     * Get Locale Model
     * @var {{$locale_model}}
     */
    protected $locales;

    /**
     * Holds the post data
     *
     * @var object
     */
    protected $data;


	public function __construct({{ ucfirst($categories) }} $categories, Locale $locales){

		$this->middleware('auth');
		$this->middleware('admin');

    	$this->categories = $categories;
    	$this->locales = $locales;

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store({{ ucfirst($categories) }}Request $request)
	{
		$this->data = $request->except([
            '_token'
        ]);

        $category = $this->storeOrUpdateCategory();
        if($category != false){
			return json_encode($category);
		} else {
			header('HTTP/1.1 503 Service Unavailable');
		};
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return Response
	 */
	public function edit(Request $request)
	{
		$this->data  = $request->except([
            '_token'
        ]);
		$category = Categories::findOrFail($this->data["id"]);
		if($category){
			$languages = Locale::all();
			$datos = array();
			$datos["id"] = $category->id;
			foreach($languages as $lang){
				$datos["languages"][$lang->language]["name"] = $category->translate($lang->language)->name;
			}

			return $datos;
		} else {
			header('HTTP/1.1 503 Service Unavailable');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update({{ ucfirst($categories) }}Request $request)
	{
		$this->data = $request->except([
            '_token'
        ]);

        $category = $this->storeOrUpdateCategory();
        if($category != false){
			return json_encode($category);
		} else {
			header('HTTP/1.1 503 Service Unavailable');
		};
	}

	
	private function storeOrUpdateCategory(){
		if (! empty($this->data["catId"])) {
            $category = $this->categories->find($this->data["catId"]);
        } else {
            $category = new Categories;
        }
        $category->color = $this->data["color"];
		$languages = Locale::all();

		foreach ($languages as $lang) {
			$newLang = $category->translate($lang->language);
			$newLang->name = $this->data["name_".$lang->language];
		}
		
		if($category->save()){
			return $category;
		} else {
			return false;
		};
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param    int  $id
	 * @return  Response
	 */
	public function destroy(Request $request)
	{
		$this->data  = $request->except([
            '_token'
        ]);

		$category = $this->categories->findOrFail($this->data["id"]);
        if($category->delete()){
            return json_encode($category->id);
        } else {
            header('HTTP/1.1 503 Service Unavailable');
        }
	}

}
