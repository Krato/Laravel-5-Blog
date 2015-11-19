<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Controllers;

use {{$app_name}}Http\Requests;
use {{$app_name}}Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;

use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }};
use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($commentsTable) }};
use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($tagsTable) }};
use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }};
use {{$usersModel}};
use {{$locale_model}};

use {{$app_name}}{{$blog_path}}\Requests\{{ ucfirst($postsTable) }}Request;
use {{$app_name}}{{$blog_path}}\Requests\{{ ucfirst($postsTable) }}UpdateRequest;
use {{$app_name}}{{$blog_path}}\Requests\{{ ucfirst($commentsTable) }}Request;
use {{$app_name}}{{$blog_path}}\Requests\{{ ucfirst($tagsTable) }}Request;

use Datatables;
use Session;
use Lang;
use Image;

class {{ ucfirst($postsTable) }}Controller extends Controller {

	/**
     * @var {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }}
     */
    protected $post;


    /**
     * @var {{$usersModel}}
     */
    protected $user;


    /**
     * @var {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }}
     */
    protected $categories;

    /**
     * @var {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($tagsTable) }}
     */
    protected $tags;


    /**
     * Get Locale Model
     * @var {{$locale_model}}
     */
    protected $locales;


    /**
     * Holds the image file
     *
     * @var  object
     */
    protected $image;

    /**
     * Holds the post data
     *
     * @var object
     */
    protected $data;

    /**
     * Holds the Data Request Type
     * @var [type]
     */
    protected $type;


    public function __construct(
			{{ ucfirst($postsTable) }} $post,
			User $user,
			{{ ucfirst($tagsTable) }} $tags,
			{{ ucfirst($categories) }} $categories,
			Locale $locales
		)
    {

		$this->middleware('auth');
		$this->middleware('admin');

    	$this->post = $post;
    	$this->user = $user;
    	$this->categories = $categories;
    	$this->tags = $tags;
    	$this->locales = $locales;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($trashed = false)
	{
		
        $all = $this->post->count();
        $published = $this->post->where('state', 1)->count();
        $trash = $this->post->onlyTrashed()->count();
        return view('blog::admin.index', compact('all', 'published', 'trash'));

	}

    public function index_front(){
        $posts = $this->post->where('state', 1)->paginate(12);
        $categories = $this->categories->get();
        return view('blog::front.home', compact('posts', 'categories'));
    }
	
	/**
	 * Get te list of post in Datatable view
	 * @return {Datatable}
	 */
	public function data_blog(Request $request)
	{
        $datos = $request->except([
            '_token'
        ]);

        switch ($datos["type"]) {
            case 'all':
                $data = $this->post->select(['id','featured_image', 'publish_date', 'state'])->get();
                break;
            case 'published':
                $data = $this->post->select(['id','featured_image', 'publish_date', 'state'])->where('state', 1)->get();
                break;
            case 'trash':
                $data = $this->post->select(['id','featured_image', 'publish_date', 'state'])->onlyTrashed()->get();
               
                break;
            default:
                $data = $this->post->select(['id','featured_image', 'publish_date', 'state'])->get();
                break;
        }
        $this->type = $datos["type"];   

        return Datatables::of($data)
        ->editColumn('photo', function($info) {
            $image = asset('blog_assets/uploads/'.date('m-Y', strtotime($info->publish_date))."/".Config::get('blog.thumbnail_folder').'/'.$info->featured_image);
            return '<img src="'.$image.'" class="img-thumbnail" alt="'.$info->getTitle().'">';
        })
        ->addColumn('title', '')
        ->editColumn('title', function($info) {
            $title = $info->getTitle();
            return $title;
        })
        ->addColumn('content', '')
        ->editColumn('content', function($info) {
            $description = $info->getContent();
            $description =  strip_tags(preg_replace("/<img[^>]+\>/i", "", $description)); 
            return $this->trim_text($description, 120, true, true);
        })
        ->editColumn('publish_date', function($info) {
            return "<div class='text-center'>".$info->publish_date."</div>";
        })
        ->editColumn('state', function($info) {
            if($info->state == 0){
                return trans('blog::blog.states.draft');
            } else {
                return trans('blog::blog.states.published');
            }
        })
        ->addColumn('opciones', '')
        ->editColumn('opciones', function($info) {
            if ($this->type == "trash"){
                $options = "<button type='button' class='btn btn-primary restorePost' data-id='".$info->id."' ><i class='fa fa fa-undo'></i></button>";
                $options .= "<button type='button' class='btn btn-danger removeForcePost' data-id='".$info->id."' data-name='".$info->getTitle()."' data-toggle='modal' data-target='#modalRemoveForcePost'><i class='fa fa-trash-o'></i></button>";
            } else {
                $options = "<a type='button' href='".str_replace("{id}", "", app('laravellocalization')->getURLFromRouteNameTranslated(app('config')->get('app.locale'),'routes.admin/blog/edit')).$info->id."' class='btn btn-success'><i class='fa fa-edit'></i></a>";
                $options .= "<button type='button' class='btn btn-danger removePost' data-id='".$info->id."' data-name='".$info->getTitle()."' data-toggle='modal' data-target='#modalRemovePost'><i class='fa fa-trash-o'></i></button>";
            }
            return $options;
        })
        ->setRowId('id')
        ->make(true);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('blog::admin.create', array('categories' => $this->categories->all()));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store({{ ucfirst($postsTable) }}Request $request)
	{
		$this->data = $request->except([
            '_token'
        ]);
        $this->image = $request->file('featured_image');
        $this->tags = $this->storeOrUpdateTags();
        $this->categories = explode(",", $this->data["categories"]);
        $post = $this->storeOrUpdatePost();
        if($post != false){
            Session::flash('success', Lang::get("blog::blog.database.post_saved"));
            return redirect(app('laravellocalization')->transRoute('routes.admin/blog'));
        } else {
            Session::flash('error', Lang::get("blog::blog.database.error"));
            return redirect()->back()->withInput();
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$trans = $this->post->translate(Config::get('app.locale'))->findBySlug($slug);
        $post = $this->post->find($trans->post_id);
        return view('blog::front.single', compact('post'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$post = $this->post->find($id);
        if($post){
            return view('blog::admin.edit', array('post' => $post, 'categories' => $this->categories->all()));
        } else {
            Session::flash('error', Lang::get("blog::blog.database.error"));
            return redirect()->back();
        }
	}

	/**
     * Update the specified resource in storage.
     *
     * @param    int  $id
     * @return  Response
     */
    public function update(PostUpdateRequest $request)
    {
        $this->data = $request->except([
            '_token'
        ]);
        $this->image = $request->file('featured_image');
        $this->tags = $this->storeOrUpdateTags();
        $this->categories = explode(",", $this->data["categories"]);
        $post = $this->storeOrUpdatePost();
        if($post != false){
            Session::flash('success', trans('blog::blog.database.post_updated') );
            return redirect(app('laravellocalization')->transRoute('routes.admin/blog'));
        } else {
            Session::flash('error', trans('blog::blog.database.error'));
            return redirect()->back()->withInput();
        }
    }

    
    /**
     * Save new post or update current
     * @return BOOL or Post->Id
     */
	private function storeOrUpdatePost()
    {
        $upload_image = true;

        if (!empty($this->data["postId"])) {
            $post = $this->post->find($this->data["postId"]);

            if($this->data["image_changed"] == 0){
                $upload_image = false;
            }
        } else {
            $post = new Post;
        }
        $languages = $this->locales->all();

        foreach ($languages as $key => $language ) {
            $newLang = $post->translate($language->language);
            $newLang->title = $this->data["title-".$language->language];
            $newLang->content = $this->data["content-".$language->language];
            if (!empty($this->data["postId"])) {
               $newLang->slug = $this->data["slug-".$language->language];
            }
        }

        if($upload_image){
            if($post->featured_image){
                if(!$this->removeImages($post->featured_image)){
                    Session::flash('error', Lang::get("blog::blog.database.error"));
                    return redirect()->back()->withInput();
                } 
            }

            $imagen = $this->uploadImage();
            $post->featured_image = $imagen;
        }

        $correctDate = str_replace('/', '-', $this->data["publish_date"]);

        $post->publish_date = date('Y-m-d', strtotime($correctDate));
        $post->state = $this->data["state"];

        if($post->save()){
            $post->tag()->sync($this->tags);
            $post->categories()->sync($this->categories);
            return $post;
        } else {
            return false;
        }
       
    }

    /**
     * Saves or updates tags
     * @return array
     */
    private function storeOrUpdateTags(){

        $tagArray = array();
        $tags = explode(",", $this->data["tags"]);
        foreach($tags as $addtag){
            $exists = {{ ucfirst($tagsTable) }}::where('name', '=', $addtag)->first();
            if(count($exists) > 0){
                $newTag = $exists;
            } else {
                $newTag = new {{ ucfirst($tagsTable) }};
            }
            $newTag->name = $addtag;
            $newTag->save();
            array_push($tagArray, $newTag->id);
        }
        return $tagArray;
    }


    /**
     * Function to handle image editor uploads
     * @param  Request $request file
     * @return JSON
     */
    public function upload_image_editor(Request $request){
        
        $this->image = $request->file('file');
        $this->data["publish_date"] = date("Y-m-d H:i:s");
        $file = $this->uploadImage(true);
        $folder = date('m-Y', strtotime($this->data["publish_date"]));
        $destination = url().'/blog_assets/uploads/'.$folder."/1920/";
        return json_encode( ['filelink'=> $destination. $file, 'name' => $file, 'folder' => $folder]);
    }

    /**
     * Uploads Images to folders with 
     * year and month folders
     * @param  boolean $redimension
     * @return string  Name of file
     */
    private function uploadImage($redimension = true){
        $file = $this->image;
        if(!isset($this->image)){
            return redirect()->back()->withInput();
        }
        $filename = pathinfo(str_replace(" ", "_",$file->getClientOriginalName()), PATHINFO_FILENAME)."_".str_random(5);
        $filenameWithExt = $filename.".". $file->getClientOriginalExtension();

        $correctDate = str_replace('/', '-', $this->data["publish_date"]);

        $destination_original = public_path().'/blog_assets/uploads/original/';
        $destination = public_path().'/blog_assets/uploads/'.date('m-Y', strtotime($correctDate))."/";

        //dd($destination);
        if (! file_exists($destination_original)) {
            mkdir($destination_original, 0777, true);
        }
        if (! file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $file->move($destination_original, $filenameWithExt);

    

        if($redimension){
            $img = Image::make($destination_original.$filenameWithExt);
            $dimensions = $img->width()."x".$img->height();
            $img->backup();
            $formats =  app('config')->get('blog.image_formats');
            $imagen_backup = false;
            foreach($formats as $name => $format){
                if(!$imagen_backup){
                    $new_image = $img->reset();
                } else {
                    $new_image = $imagen_backup->reset();
                }
                if (! file_exists($destination.$name."/")) {
                    mkdir($destination.$name."/", 0777, true);
                }

                $new_image = $img->reset();
                switch ($format['a']) {
                    case 0:
                        $new_image->resize(
                            $format['w'],$format['h'],function($constraint){
                                $constraint->aspectRatio();
                            }
                        );
                        break;
                    case 1:
                        $new_image->fit($format['w'],$format['h']);
                        break;
                }
                $new_image->save($destination.$name.'/'.$filenameWithExt);
                if($name == '1920'){
                    $imagen_backup = $new_image->backup();
                }
            }
            $img->destroy();
            //borramos la imagen original
            @unlink($destination_original.$filenameWithExt);
        }

        return $filenameWithExt;
    }

    /**
     * Remove Featured Image
     * @param  var $image image file
     * @return BOOL
     */
    private function removeImages($image){
        if (strpos($image,'http') !== false) {
            return true;
        }
        $correctDate = str_replace('/', '-', $this->data["publish_date"]);
        $time_folder = date('m-Y', strtotime($correctDate));
        $formats =  app('config')->get('blog.image_formats');
        $destination = public_path().'/blog_assets/uploads/'.$time_folder."/";
        $error = false;
        foreach($formats as $name => $format){
            if(unlink($destination.$name.'/'.$image) == false){
                $error = true;
            }
        }

        if(unlink($destination.$image) == false){
            $error = true;
        }

        if($error){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Function to handle image editor uploads
     * @param  Request $request file
     * @return JSON
     */
    public function upload_image_editor(Request $request){
        
        $this->image = $request->file('file');
        $this->data["publish_date"] = date("Y-m-d H:i:s");
        $file = $this->uploadImage(false);
        $folder = date('m-Y', strtotime($this->data["publish_date"]));
        $destination = url().'/blog_assets/uploads/'.$folder."/";

        return json_encode( ['filelink'=> $destination. $file, 'name' => $file, 'folder' => $folder]);
        return false;
    }

    /**
     * Remove editor image from folder
     * @param  Request $request
     * @return BOOL
     */
    public function remove_editor_images(Request $request){
        $name = $request["name"];
        $folder = $request["folder"];
        if(@unlink(public_path().'/blog_assets/uploads/'.$folder."/".$name)){
            return json_encode(true);
        } else {
            return json_encode(false);
        }
    }

	/**
     * trims text to a space then adds ellipses if desired
     * @param string $input text to trim
     * @param int $length in characters to trim to
     * @param bool $ellipses if ellipses (...) are to be added
     * @param bool $strip_html if html tags are to be stripped
     * @return string 
     */
    function trim_text($input, $length, $ellipses = true, $strip_html = true) {
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }
      
        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }
      
        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);
      
        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }
      
        return $trimmed_text;
    }


    /**
     * Restore posts
     *
     * @param    int  $id
     * @return  Response
     */
    public function restore(Request $request)
    {
        $this->data  = $request->except([
            '_token'
        ]);

        $post = $this->post->withTrashed()->find($this->data["id"]);
        if($post->restore()){
            Session::flash('message', Lang::get("blog::blog.database.post_restored"));
            return json_encode(true);
        } else {
            header('HTTP/1.1 503 Service Unavailable');
        }
    }

    /**
     * Send post to trash.
     *
     * @param    int  $id
     * @return  Response
     */
    public function destroy(Request $request)
    {
        $this->data  = $request->except([
            '_token'
        ]);
        $post = $this->post->find($this->data["id"]);
        if($post->delete()){
            Session::flash('message', Lang::get("blog::blog.database.post_trash"));
            return json_encode(true);
        } else {
            header('HTTP/1.1 503 Service Unavailable');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int  $id
     * @return  Response
     */
    public function forceDestroy(Request $request)
    {
        $this->data  = $request->except([
            '_token'
        ]);
        $post = $this->post->withTrashed()->find($this->data["id"]);
        $this->data = $post;
        $image = $post->featured_image;
        if($post->forceDelete()){
            $this->removeImages($image);
            Session::flash('message', Lang::get("blog::blog.database.post_trash"));
            return json_encode(true);
        } else {
            header('HTTP/1.1 503 Service Unavailable');
        }
    }

}
