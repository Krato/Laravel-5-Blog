<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Requests;

use {{$app_name}}Http\Requests\Request;
use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }};

class {{ ucfirst($categories) }}Request extends Request
{

    /**
     * @param {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }} $tag
     */
    protected $category;

    /**
     * @param {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }} $tag
     */
    public function __construct({{ ucfirst($categories) }} $category)
    {
        $this->category = $category;
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
        $rules = [];
        @foreach($locales as $local)

        $rules["name_{{$local}}"]   = 'required';
        @endforeach

        return $rules;
    }

}
