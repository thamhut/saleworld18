<?php
        		        		
namespace App\Models;

use Phalcon\Mvc\Model as ModelApp;

class Slider extends ModelApp
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $url;

    /**
     *
     * @var integer
     */
    protected $stastus;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Method to set the value of field stastus
     *
     * @param integer $stastus
     * @return $this
     */
    public function setStastus($stastus)
    {
        $this->stastus = $stastus;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the value of field stastus
     *
     * @return integer
     */
    public function getStastus()
    {
        return $this->stastus;
    }

    public function getSource()
    {
        return 'slider';
    }

}
