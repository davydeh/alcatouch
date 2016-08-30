<?php

namespace Proyecto\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Proyecto\AppBundle\Entity\Manhole;
use Proyecto\AppBundle\Entity\Project;
use Proyecto\AppBundle\Entity\Conduits;
use Proyecto\AppBundle\Entity\OutFall;
use Proyecto\AppBundle\Utils\Enums\ETipoElemento;
use Proyecto\AppBundle\Entity\ShapeConduits;
use Proyecto\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class RequestController extends Controller {

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        return $this->render('AppBundle:Request:index.html.twig');
    }

    public function saveAction() {

        $em = $this->getEntityManager();
        // Obteniendo el objeto Project
        $projectData = json_decode($this->getRequest()->get('project'));
        $userData = json_decode($this->getRequest()->get('user'));

        // Si no contiene datos devuelve error!
        if (!$projectData || empty($projectData)) {
            return new Response(json_encode(array('success' => FALSE, 'error' => 'Failed to load project data.')));
        }

        $user = $em->getRepository('UserBundle:User')->findBy(array('username' => $userData->username, 'password' => $userData->password));

        if (!$user || empty($user)) {
            return new Response(json_encode(array('success' => FALSE, 'error' => 'Invalid user.')));
        }

        // Creando el Project
        $project = new Project();
        $project->setUser($user[0]);
        $project->setName($projectData->name);
        $project->setScale($projectData->scale);
        $project->setFecha(new \DateTime('now'));
        $project->setDx($projectData->dx);
        $project->setDy($projectData->dy);
        $project->setLimitMinX($projectData->limitMinX);
        $project->setLimitMaxX($projectData->limitMaxX);
        $project->setLimitMinY($projectData->limitMinY);
        $project->setLimitMaxY($projectData->limitMaxY);

        $em->persist($project);
        
        // recorrer el arreglo de elementos del proyecto
        // para cada elemento llamar a la funcion deserialize
        // y luego con el objeto que devuelve (Entidad de la BD)
        // agregarlo al proyecto
        // setearle el pryecto
        // y persistirlo
        foreach ($projectData->arrayElement as $element) {

            if ($element->typeElement == ETipoElemento::Conduit) {
                $tipoElemento = $this->getTipoElemento(ETipoElemento::Conduit);
                
                // Crear objeto de tipo conduit
                $conduit = new Conduits();

                $conduit->setName($element->name);
                $conduit->setColor($element->color);
                $conduit->setManning($element->manning);
                $conduit->setLength($element->length);
                $conduit->setDiameter($element->diameter);
                $conduit->setTipoElemento($tipoElemento);
                $conduit->setInvertElvStart($element->invertStart);
                $conduit->setInvertElvEnd($element->invertEnd);
                $conduit->setSameInvertAtStartNode($element->sameInvertAtStartNode);                
                $conduit->setSameInvertAtEndNode($element->sameInvertAtEndNode);
                
                $startNode = $this->deserialize($element->startNode);
                $em->persist($startNode);

                $endNode = $this->deserialize($element->endNode);
                $em->persist($endNode);
                
                $conduit->setStartNode($startNode);
                $conduit->setEndNode($endNode);

                $project->addElement($conduit);
                $conduit->setProject($project);

                $em->persist($conduit);
                
                $shapeConduits = new ShapeConduits();
                
                $shapeConduits->setDescription("CIRCULAR");
                $shapeConduits->setConduits($conduit);
                
                $em->persist($shapeConduits);        
            } else {
                $node = $this->deserialize($element);
                $node->setProject($project);
                $em->persist($node);
            }
        }

        // Guardando los cambios en BD
        $em->flush();

        return new Response(json_encode(array('success' => true)));
    }

    public function loadAction() {
        
        $em = $this->getEntityManager();

        $projectData = json_decode($this->getRequest()->get('project'));
        $userData = json_decode($this->getRequest()->get('user'));
        
        $project = $em->getRepository('AppBundle:Project')->findOneBy(array('id' => $projectData->id));

        if(!$project) {
            return new Response(json_encode(array('success' => FALSE, 'error' => 'The selected project does not exists.')));
            
        }
        
        $user = $em->getRepository('UserBundle:User')->findBy(array('username' => $userData->username, 'password' => $userData->password));

        if (!$user || empty($user)) {
            return new Response(json_encode(array('success' => FALSE, 'error' => 'Invalid user.')));
        }
        
        $elements = $project->getElements();

        if(!$elements) {
            return new Response(json_encode(array('success' => FALSE, 'error' => 'The selected project is empty.')));
        }

        $jsonResult = array(
            'name' => $project->getName(),
            'scale'=> $project->getScale(),
            'dx' => $project->getDx(),
            'dy' => $project->getDy(),
            'limitMinX' => $project->getLimitMinX(),
            'limitMaxX' => $project->getLimitMaxX(),
            'limitMinY' => $project->getLimitMinY(),
            'limitMaxY' => $project->getLimitMaxY(),
            'arrayElement' => array()
        );
        
        foreach ($elements as $element) {
            $jsonResult['arrayElement'][] = $this->serialize($element);
        }
        
        return new Response(json_encode(array('success' => TRUE,'project' => $jsonResult)));
    }
    
    /*
     * Action para listar los proyectos de un usuario
     * 
     * @parameteros JSON con formato { user : { username: xxxxx }}
     * @return JSON con formato 
     * { success : true, projects : { 0: { id : x, name : projname } } }
     */
    public function listProjectsAction() {
        
        $userData = json_decode($this->getRequest()->get('user'));
        
        $em = $this->getEntityManager();
        
        $user = $em->getRepository('UserBundle:User')->findOneBy(array('username' => $userData->username));
        
        if(!$user) {
            return new Response(json_encode(array('success' => FALSE, 'error' => 'Invalid username.')));
        }
        
        $projects = $user->getProjects();
        $projectArray = array();
        
        foreach ($projects as $project) {
            $projectArray[] = array('id' => $project->getId(), 'name' => $project->getName(), 'date' => $project->getFecha());
        }
        
        return new Response(json_encode(array('success' => TRUE, 'projects' => $projectArray)));
    }

    /*
     * Action para verificar la autenticidad del usuario
     * 
     * @parameteros JSON con formato { user : { username: xxxxx, password: xxxxx }}
     * @return JSON con formato { success : true, userId : x }
     */
    public function loginCheckAction() {
        
        $em = $this->getEntityManager();
        
        $userData = json_decode($this->getRequest()->get('user'));

        $user = $em->getRepository('UserBundle:User')
                ->findOneBy(array(
                    'username' => $userData->username,
                    'password' => $userData->password
                ));

        if ($user) {
            return new Response(json_encode(array('success' => TRUE, 'userId' => $user->getId())));
        }

        return new Response(json_encode(array('success' => FALSE, 'error' => 'Invalid username or password.')));
    }

    private function getEntityManager() {
        return $this->getDoctrine()->getManager();
    }

    private function getTipoElemento($idTipo) {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('AppBundle:NomTipoElemento')->find($idTipo);
    }
    
    
    /*
     * Serializa los objetos a Arrays para convertirlos en JSON
     * 
     * @parameteros Element $element
     * @return Array() o false si el tipo del elemento no existe
     */
    private function serialize($element) {
        
        
        switch ($element->getTipoElemento()->getId()) {
            case ETipoElemento::Manhole:
                $manhole = array(
                    'typeElement' => ETipoElemento::Manhole,
                    'nodeId' => $element->getNodeId(),
                    'name' => $element->getName(),
                    'color' => $element->getColor(),
                    'x' => $element->getX(),
                    'y' => $element->getY(),
                    'r' => $element->getR(),
                    'elevGround' => $element->getGroundElev(),
                    'elevInvert' => $element->getInvertElev(),
                    'inflow' => $element->getInflow()
                );
                return $manhole;
                break;
            case ETipoElemento::OutFall:
                $outfall = array(
                    'typeElement' => ETipoElemento::OutFall,
                    'nodeId' => $element->getNodeId(),
                    'name' => $element->getName(),
                    'color' => $element->getColor(),
                    'outfallType' => $element->getType(),
                    'x' => $element->getX(),
                    'y' => $element->getY(),
                    'r' => $element->getR(),
                    'elevInvert' => $element->getInvertElev()
                );
                return $outfall;
                break;
            case ETipoElemento::Conduit:
                $conduit = array(
                    'typeElement' => ETipoElemento::Conduit,
                    'name' => $element->getName(),
                    'color' => $element->getColor(),
                    'manning' => $element->getManning(),
                    'length' => $element->getLength(),
                    'diameter' => $element->getDiameter(),
                    'startNode' => $this->serialize($element->getStartNode()),
                    'endNode' => $this->serialize($element->getEndNode()),
                    'invertStart' => $element->getInvertElvStart(),
                    'invertEnd' => $element->getInvertElvEnd(),
                    'sameInvertAtStartNode' => $element->getSameInvertAtStartNode(),
                    'sameInvertAtEndNode' => $element->getSameInvertAtEndNode()
                );
                return $conduit;
                break;
            default:
                break;
        }
        
        return false;
    }

    /*
     * Deserializa los objetos Array para convertirlos en Entidades
     * 
     * @parameteros Array $element
     * @return Node o false si el tipo del elemento no existe
     */
    private function deserialize($element) {

        $em = $this->getDoctrine()->getManager();

        switch ($element->typeElement) {
            case ETipoElemento::Manhole:
                $tipoElemento = $this->getTipoElemento(ETipoElemento::Manhole);

                $manhole = new Manhole();

                $manhole->setColor($element->color);
                $manhole->setGroundElev($element->elevGround);
                $manhole->setInvertElev($element->elevInvert);
                $manhole->setInflow($element->inflow);
                $manhole->setName($element->name);
                $manhole->setNodeId($element->nodeId);
                $manhole->setR($element->r);
                $manhole->setTipoElemento($tipoElemento);
                $manhole->setX($element->x);
                $manhole->setY($element->y);
                                
                return $manhole;
            case ETipoElemento::OutFall:
                $tipoElemento = $this->getTipoElemento(ETipoElemento::OutFall);

                $outfall = new OutFall();

                $outfall->setColor($element->color);
                $outfall->setName($element->name);
                $outfall->setInvertElev($element->elevInvert);
                $outfall->setType($element->outfallType);
                $outfall->setNodeId($element->nodeId);
                $outfall->setTipoElemento($tipoElemento);
                $outfall->setR($element->r);
                $outfall->setX($element->x);
                $outfall->setY($element->y);

                return $outfall;
            default:
                break;
        }

        return false;
    }

    private function persist($element) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($element);
    }

}
