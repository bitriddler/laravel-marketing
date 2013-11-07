<?php namespace Kareem3d\Marketing;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Kareem3d\Eloquent\Model;
use Kareem3d\Link\Link;
use Kareem3d\URL\URL;

class SEO extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ka_seo';

    /**
     * @param $value
     * @return mixed
     */
    public function setUrlAttribute( $value )
    {
        if(is_object($value) and
            get_class($value) == App::make('Kareem3d\Link\Link')->getClass())

            return $this->link()->save($value);

        return $this->Link()->associate(App::make('Kareem3d\Link\Link')->create(array('url' => $value)));
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return View::make('marketing::head', array('seo' => $this))->render();
    }

    /**
     * @param $inputs
     * @return SEO|null
     */
    public static function createOrUpdate( $inputs )
    {
        if(! isset($inputs['link_id'])) return null;

        if($seo = static::getByLinkId($inputs['link_id']))
        {
            $seo->update($inputs);

            return $seo;
        }
        else
        {
            return static::create($inputs);
        }
    }

    /**
     * @param \Kareem3d\Link\Link $link
     * @return SEO
     */
    public static function getByLink( Link $link )
    {
        return static::getByLinkId($link->id);
    }

    /**
     * @param $id
     * @return SEO
     */
    public static function getByLinkId( $id )
    {
        return static::where('link_id', $id)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function link()
    {
        return $this->belongsTo(App::make('Kareem3d\Link\Link')->getClass());
    }
}