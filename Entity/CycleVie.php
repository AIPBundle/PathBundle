<?php

namespace Aip\ProfilageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CycleVie
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CycleVie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Aip\ProfilageBundle\Entity\CycleDeVieCompetence",
     *     inversedBy="cycledevie"
     * )
     * @ORM\JoinColumn(name="cycle_id", onDelete="CASCADE", nullable=false)
     */
    protected $cycle;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return CycleVie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
    public function getCycle()
    {
    	return $this->cycle;
    }
    
    public function setCycle($cycle)
    {
    	$this->cycle= $cycle;
    }
}
