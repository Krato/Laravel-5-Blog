<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Requests;

use {{$app_name}}Http\Requests\Request;
use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($tagsTable) }};

class {{ ucfirst($tagsTable) }}Request extends Request
{

    /**
     * @param {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($tagsTable) }} $tag
     */
    protected $tag;

    /**
     * @param {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($tagsTable) }} $tag
     */
    public function __construct({{ ucfirst($tagsTable) }} $tag)
    {
        $this->tag = $tag;
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
        return [
            'name'		=> 'required'
        ];
    }

}
