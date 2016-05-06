<?php
        		        		
namespace App\Models;

use Phalcon\Mvc\Model as ModelApp;

class Mapcate extends ModelApp
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $idcate;

    /**
     *
     * @var string
     */
    protected $link;

    /**
     *
     * @var integer
     */
    protected $idweb;

    /**
     *
     * @var integer
     */
    protected $status;

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
     * Method to set the value of field idcate
     *
     * @param integer $idcate
     * @return $this
     */
    public function setIdcate($idcate)
    {
        $this->idcate = $idcate;

        return $this;
    }

    /**
     * Method to set the value of field link
     *
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Method to set the value of field idweb
     *
     * @param integer $idweb
     * @return $this
     */
    public function setIdweb($idweb)
    {
        $this->idweb = $idweb;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * Returns the value of field idcate
     *
     * @return integer
     */
    public function getIdcate()
    {
        return $this->idcate;
    }

    /**
     * Returns the value of field link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Returns the value of field idweb
     *
     * @return integer
     */
    public function getIdweb()
    {
        return $this->idweb;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getSource()
    {
        return 'mapcate';
    }

}
