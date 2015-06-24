<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Requests;

use {{$app_name}}Http\Requests\Request;
use {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($commentsTable) }};

class {{ ucfirst($commentsTable) }}Request extends Request
{

    /**
     * @param {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($commentsTable) }} $comment
     */
    protected $comment;

    /**
     * @param {{$app_name}}{{$blog_path}}\Models\{{ ucfirst($commentsTable) }} $comment
     */
    public function __construct({{ ucfirst($commentsTable) }} $comment)
    {
        $this->comment = $comment;
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
            'content'		=> 'required'
        ];
    }

}
