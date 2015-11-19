<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Requests;

use {{$app_name}}Http\Requests\Request;
use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }};

class {{ ucfirst($postsTable) }}UpdateRequest extends Request
{

    /**
     * @param {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }} $post
     */
    protected $post;

    /**
     * @param {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }} $post
     */
    public function __construct({{ ucfirst($postsTable) }} $post)
    {
        $this->post = $post;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'categories'	=> 'required',
            'state'        => 'required',
            'publish_date'	=> 'required|date_format: d/m/Y',
            'image_changed' => 'required'
        ];
        
        return $rules;
    }

}
