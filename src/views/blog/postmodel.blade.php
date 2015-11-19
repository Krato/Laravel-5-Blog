<?php echo '<?php' ?>
 namespace {{$app_name}}{{$blog_path}}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Vinkla\Translator\Translatable;
use Vinkla\Translator\Contracts\Translatable as TranslatableContract;


class {{ ucfirst($postsTable) }} extends Model implements TranslatableContract {

    use Translatable, SoftDeletes;

    protected $table = '{{ $postsTable }}';

    protected $fillable = ['slug', 'title', 'content', 'featured_image', 'publish_date', 'state'];

    protected $translator = '{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($postsTable) }}Translation';

    protected $translatedAttributes = ['slug', 'title', 'content'];

	protected $dates = ['deleted_at'];

    /**
	 * Returns the slug
	 * @return text
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Returns the title
	 * @return text
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Returns the content
	 * @return text
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Post belongs to User
	 * @return Eloquent
	 */
	public function user()
    {
        return $this->belongsTo('{{$usersModel}}');
    }

	/**
	 * Get the comments for the post
	 * @return Eloquent
	 */
	public function comments(){
		return $this->hasMany('{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($commentsTable) }}');
	}

	/**
	 * Get all categories for the post
	 * @return Eloquent
	 */
	public function categories()
    {
        return $this->belongsToMany('{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($categories) }}', '{{$pivotCategoriesTable}}', '{{ $postsTable }}_id', '{{ $categories }}_id')->withTrashed();
    }
    
    /**
	 * Get all tags for the post
	 * @return Eloquent
	 */
	public function tag()
    {
        return $this->belongsToMany('{{$app_name}}{{$blog_path}}\Models\{{ ucfirst($tagsTable) }}', '{{$pivotTagsTable}}', '{{ $postsTable }}_id', '{{ $tagsTable }}_id')->withTrashed();
    }

	/**
	 * Generates Feature image for post
	 * @return  Eloquent
	 */
	public function getFeaturedImage()
    {
    	
    	if($this->featured_image){
    		$image =  asset('blog_assets/uploads/'.date('m-Y', strtotime($this->publish_date))."/1920/".$this->featured_image);
    	} else {
    		$image = asset('blog_assets/no-image.jpg');
    	}
        
        return $image;
    }
}
