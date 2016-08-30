<?php

namespace Proyecto\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Proyecto\AppBundle\Utils\Enums\ETipoSeccionINP;
use Proyecto\AppBundle\Entity\Manhole;
use Proyecto\AppBundle\Entity\Project;
use Proyecto\UserBundle\Entity\User;
use Proyecto\AppBundle\Entity\Conduits;
use Proyecto\AppBundle\Entity\ShapeConduits;
use Proyecto\AppBundle\Entity\OutFall;
use Proyecto\AppBundle\Utils\Enums\ETipoElemento;
use Proyecto\AppBundle\Entity\File as INPFile;
use Symfony\Component\HttpFoundation\Request;

class ExtensionManagerController extends Controller {

    public function saveFileAction(Request $request) {

        try {
            // Subiendo el archivo
            $uploadedFile = new INPFile();

            $form = $this->createFormBuilder($uploadedFile)
                    ->add('name')
                    ->add('file')
                    ->add('save', 'submit')
                    ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $uploadedFile->upload();

                // Declarando variables
                $em = $this->getEntityManager();
                $currentSection = ETipoSeccionINP::TITLE;

                $manhole = null;
                $conduit = null;
                $outfall = null;

                $elementsArray = array();
                $conduitsArray = array();

                // Preparing project and user
                $user = $em->getRepository('UserBundle:User')->findBy(array('id' => 1/* $userData->username */));

                $project = new Project();
                $project->setUser($user[0]);
                $project->setName($uploadedFile->name);

                // Get file .inp
                $filename = $uploadedFile->getWebPath();

                if (!$this->isTextFile($filename)) {
                    throw new \Symfony\Component\Config\Definition\Exception\Exception("You must load a .INP file. Please try again.");
                }

                $fichero = fopen($filename, 'rb');
                $contenido = fread($fichero, filesize($filename));
                
                fclose($fichero);

                $lineas = explode("\n", $contenido);

                // Recorriendo linea por linea el fichero
                foreach ($lineas as $linea) {
                    $attr = "";
                    $value = '0';

                    $noSpacesAtBeginning = preg_replace("/^\s+/", "", $linea);
                    
                    $replaced = preg_replace("/\s+/", ",", $noSpacesAtBeginning);
                    $exploded = explode(',', $replaced);

                    $firstValue = $exploded[0];

                    if (!$firstValue || $firstValue[0] == ";") {
                        // IGNORAR SI LA LINEA ESTA VACIA O ES COMENTARIO
                        continue;
                    } else if ($firstValue[0] == "[") {
                        // COMIENZA UNA NUEVA SECCION. SETEAR VALOR DE "currentSection"
                        switch ($firstValue) {

                            case '[OPTIONS]':
                                $currentSection = ETipoSeccionINP::OPTIONS;
                                break;

                            case '[CONDUITS]':
                                $currentSection = ETipoSeccionINP::CONDUITS;
                                break;

                            case '[XSECTIONS]':
                                $currentSection = ETipoSeccionINP::XSECTIONS;
                                break;

                            case '[JUNCTIONS]':
                                $em->persist($project);
                                $currentSection = ETipoSeccionINP::JUNCTIONS;
                                break;

                            case '[OUTFALLS]':
                                $currentSection = ETipoSeccionINP::OUTFALLS;
                                break;

                            case '[MAP]':
                                $currentSection = ETipoSeccionINP::MAP;
                                break;

                            case '[COORDINATES]':
                                $currentSection = ETipoSeccionINP::COORDINATES;
                                break;

                            default :
                                $currentSection = ETipoSeccionINP::NOT_ASSIGNED;
                                break;
                        }
                        continue;
                    } else {
                        // AQUI VIENEN LOS ATRIBUTOS DE LOS OBJETOS Y SE CREAN 
                        // DEPENDIENDO DE LA VARIABLE currentSection
                        switch ($currentSection) {

                            // SECCION DE CONFIGURACION DEL PROYECTO
                            case ETipoSeccionINP::OPTIONS:
                                $attr = $exploded[0];
                                $value = $exploded[1];

                                // Llamada a funcion que setea datos en el project
                                $project = $this->setProjectData($attr, $value, $project);
                                break;

                            // SECCION DE CREACION DE LOS NODES
                            case ETipoSeccionINP::JUNCTIONS:
                                $manhole = new Manhole();
                                // Llamada a funcion que setea datos dependiendo del tipo de nodo
                                $manhole = $this->setElementsInitialData(ETipoElemento::Manhole, $exploded, $manhole);

                                // Se guardan los elementos en un arreglo asociativos por el nombre
                                // para tener acceso a ellos mas adelante en la secion [COORDINATES]
                                // donde se establecen sus coordenadas.
                                $elementsArray[$exploded[0]] = $manhole;
                                break;

                            // SECCION DE CREACION DE OUTFALLS
                            case ETipoSeccionINP::OUTFALLS:
                                $outfall = new OutFall();
                                // Llamada a funcion que setea datos dependiendo del tipo de nodo
                                $outfall = $this->setElementsInitialData(ETipoElemento::OutFall, $exploded, $outfall);

                                // Se guardan los elementos en un arreglo asociativos por el nombre
                                // para tener acceso a ellos mas adelante en la secion [COORDINATES]
                                // donde se establecen sus coordenadas.
                                $elementsArray[$exploded[0]] = $outfall;
                                break;

                            // SECCION DE CREACION DE CONDUITS
                            case ETipoSeccionINP::CONDUITS:
                                $conduit = new Conduits();

                                $tipoElemento = $this->getTipoElemento(ETipoElemento::Conduit);
                                $conduit->setTipoElemento($tipoElemento);

                                $conduit->setName($exploded[0]);

                                $startNode = $elementsArray[$exploded[1]];
                                $conduit->setStartNode($startNode);

                                $endNode = $elementsArray[$exploded[2]];
                                $conduit->setEndNode($endNode);

                                $conduit->setLength($exploded[3]);
                                $conduit->setManning($exploded[4]);
                                $conduit->setInvertElvStart($exploded[5]);
                                $conduit->setInvertElvEnd($exploded[6]);

                                // Valores por defecto
                                $conduit->setSameInvertAtStartNode(FALSE);
                                $conduit->setSameInvertAtEndNode(FALSE);
                                $conduit->setColor('#000');

                                // El diametro y la forma se agregan en la seccion XSECTIONS
                                // Adicionar al arreglo asociativo por el nombre del conduit
                                $conduitsArray[$exploded[0]] = $conduit;
                                break;

                            // SECCION DE CONFIGURACION DE LA FORMA DE LOS CONDUITS
                            case ETipoSeccionINP::XSECTIONS:
                                // Aqui se le especifica el diametro a los conduits y demas parametros dependiendo de la forma
                                // Sacando el conduit del arreglo asociativo por el nombre
                                $conduit = $conduitsArray[$exploded[0]];
                                $shape = new ShapeConduits();

                                // Hacer un switch por el tipo de Shape para setear las Geometrias acorde a la forma
                                switch ($exploded[1]) {
                                    case 'CIRCULAR':
                                        $shape->setDescription('CIRCULAR');
                                        $shape->setConduits($conduit);

                                        $conduit->setDiameter($exploded[2]); // Geom1
                                        $conduit->addShapec($shape);
                                        break;

                                    default:
                                        break;
                                }
                                
                                // Adicionando el conduit al proyecto
                                $project->addElement($conduit);
                                $conduit->setProject($project);

                                // Guardando
                                $em->persist($shape);
                                $em->persist($conduit);
                             
                                break;

                            // SECCION DE CONFIGURACION DE LAS DIMENSIONES DEL MAPA (DEL PROYECTO)
                            case ETipoSeccionINP::MAP:
                                // SETEARLE AL PROYECTO LAS VARIABLES limitMinX/Y y limitMaxX/Y
                                if ($exploded[0] == 'DIMENSIONS') {
                                    $project->setLimitMinX($exploded[1]);
                                    $project->setLimitMinY($exploded[2]);
                                    $project->setLimitMaxX($exploded[3]);
                                    $project->setLimitMaxY($exploded[4]);
                                } 
                                break;

                            // SECCION DE COORDENADAS DE LOS NODES
                            case ETipoSeccionINP::COORDINATES:
                                // Setear las coordenadas de cada Node creado
                                if ($elementsArray[$exploded[0]]) {
                                    $elementsArray[$exploded[0]]->setX($exploded[1]);
                                    $elementsArray[$exploded[0]]->setY($exploded[2]);
                                    $elementsArray[$exploded[0]]->setProject($project);
                                    $project->addElement($elementsArray[$exploded[0]]);
                                    $em->persist($elementsArray[$exploded[0]]);
                                }
                                break;

                            // SECCION PARA CUANDO EMPIEZA UNA SECCION NO IMPLEMENTADA
                            // EJEMPLO: POLYGONS | INFILTRATION | SUBAREAS | ...
                            case ETipoSeccionINP::NOT_ASSIGNED:
                                break;

                            default :
                                break;
                        }
                    }// Fin else
                }// Fin foreach
                
                // Valores predeterminados del proyecto
                $project->setDx(0);
                $project->setDy(0);
                $project->setScale(28);

                // Guardando los cambios en BD
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                        'notice', 'The file was uploaded succesfully!'
                );

                return $this->redirect($this->generateUrl('app_homepage'));
            }

            return $this->render('AppBundle:ExtensionManager:upload.html.twig', array(
                        'form' => $form->createView()));
        } catch (Exception $exc) {
            return $this->render('AppBundle:ExtensionManager:upload.html.twig', array(
                        'form' => $form->createView()));
        }
    }

    public function exportProjectAction(Request $request) {

        $em = $this->getEntityManager();

        $form = $this->createFormBuilder()
                ->add('project', 'hidden', array('required' => true, 'attr' => array('class' => 'project')))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $projectId = $form->get('project')->getData();
            $project = $em->getRepository('AppBundle:Project')->findBy(array('id' => $projectId));

            // Enviando el proyecto para procesarlo al formato INP
            $projectContentINP = $this->convertProjectToINP($project[0]);

            // Obteniendo el nombre del proyecto para guardar
            $filename = $project[0]->getName();

            // Creando la Respuesta
            $response = new Response();

            // Estableciendo las cabeceras
            $response->headers->set('Content-Type', 'text/plain');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '.inp');

            // Estableciendo el contenido a la respuesta 
            // del metodo que convierte a INP
            $response->setContent($projectContentINP);

            // Devolviendo la respuesta
            return $response;
        }

        // Listando los proyectos para seleccionar
        $projects = $em->getRepository('AppBundle:Project')->findBy(array('user' => 1));
        $projectList = array();

        foreach ($projects as $p) {
            $projectList[] = array($p->getId(), $p->getName());
        }

        return $this->render('AppBundle:ExtensionManager:download.html.twig', array(
                    'form' => $form->createView(), 'projects' => $projectList));
    }

    private function setElementsInitialData($type, $valuesArray, $element) {

        switch ($type) {

            case ETipoElemento::Manhole:

                if ($element == null)
                    $element = new Manhole();

                $tipoElemento = $this->getTipoElemento(ETipoElemento::Manhole);

                $element->setName($valuesArray[0]);
                $element->setInvertElev($valuesArray[1]);
                $element->setGroundElev($valuesArray[1] + $valuesArray[2]);

                $element->setR(8);

                $element->setTipoElemento($tipoElemento);
                $element->setColor('#000');
                $element->setNodeId($valuesArray[0]);

                break;
            case ETipoElemento::Conduit:
                break;
            case ETipoElemento::OutFall:
                if ($element == null)
                    $element = new OutFall();

                $tipoElemento = $this->getTipoElemento(ETipoElemento::OutFall);

                $element->setName($valuesArray[0]);
                $element->setInvertElev($valuesArray[1]);
                $element->setType($valuesArray[2]);

                $element->setR(8);

                $element->setTipoElemento($tipoElemento);
                $element->setColor('#000');
                $element->setNodeId($valuesArray[0]);
                break;
            default:
                break;
        }

        return $element;
    }

    private function setProjectData($attr, $value, $project) {

        if ($project == null)
            $project = new Project();

        switch ($attr) {
            case 'START_DATE':
                $project->setFecha(new \DateTime($value));
                break;
            case 'END_DATE':
                break;
            case 'MIN_X':
                $project->setLimitMinX($value);
                break;
            case 'MIN_Y':
                $project->setLimitMinY($value);
                break;
            case 'MAX_X':
                $project->setLimitMaxX($value);
                break;
            case 'MAX_Y':
                $project->setLimitMaxY($value);
                break;
            case 'DX':
                $project->setDx($value);
                break;
            case 'DY':
                $project->setDy($value);
                break;

            default:
                break;
        }

        return $project;
    }

    private function getEntityManager() {
        return $this->getDoctrine()->getManager();
    }

    private function getTipoElemento($idTipo) {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('AppBundle:NomTipoElemento')->find($idTipo);
    }

    private function convertProjectToINP(Project $project = null) {

        if ($project == null)
            return "";

        $em = $this->getEntityManager();

        $tipoElementoManhole = $em->getRepository('AppBundle:NomTipoElemento')->findBy(array('id' => ETipoElemento::Manhole));
        $tipoElementoOutfall = $em->getRepository('AppBundle:NomTipoElemento')->findBy(array('id' => ETipoElemento::OutFall));
        $tipoElementoConduits = $em->getRepository('AppBundle:NomTipoElemento')->findBy(array('id' => ETipoElemento::Conduit));

        $tipoElementoManhole = $tipoElementoManhole[0];
        $tipoElementoOutfall = $tipoElementoOutfall[0];
        $tipoElementoConduits = $tipoElementoConduits[0];
        
        $existingNodes = array();

        // Asignando todas las secciones que dependen de los elementos del proyecto
        $mapSection = "[MAP]\n";
        $outfallSection = "[OUTFALLS]\n";
        $junctionSection = "[JUNCTIONS]\n";
        $conduitsSection = "[CONDUITS]\n";
        $xsectionsSection = "[XSECTIONS]\n";
        $coordinatesSection = "[COORDINATES]\n";

        $content = "[TITLE] \n\n[OPTIONS]\n";

        // [OPTIONS]
        $content .= "START_DATE           " . $project->getFecha()->format('m/d/Y') . "\nSTART_TIME           00:00:00";
        $content .= "\n";

        // Recorriendo una sola vez y armando
        // por cada tipo de elemento las secciones distintas
        // que tengan que ver con los nodos
        foreach ($project->getElements() as $element) {

            if ($element->getTipoElemento() == $tipoElementoManhole) {
                //Seccion [JUNCTION]
                //;;               Invert     Max.       Init.      Surcharge  Ponded    
                //;;Name           Elev.      Depth      Depth      Depth      Area 
                $junctionSection .= $element->getName() . "  " . $element->getInvertElev() . "  " . $element->getMaxDepth() . "  0   0   0";
                $junctionSection .= "\n"; //salto de linea

                //Seccion [COORDINATES]
                $coordinatesSection .= $element->getName() . "  " . $element->getX() . "  " . $element->getY();
                $coordinatesSection .= "\n"; //salto de linea
                
                $existingNodes[$element->getName()] = true;
            }

            if ($element->getTipoElemento() == $tipoElementoOutfall) {
                //Seccion [JUNCTION]
                //;;               Invert     Outfall    Stage/Table      Tide
                //;;Name           Elev.      Type       Time Series      Gate
                $outfallSection .= $element->getName() . "  " . $element->getInvertElev() . "  " . $element->getType() . "        NO";
                $outfallSection .= "\n"; //salto de linea

                //Seccion [COORDINATES]
                $coordinatesSection .= $element->getName() . "  " . $element->getX() . "  " . $element->getY();
                $coordinatesSection .= "\n"; //salto de linea
                
                $existingNodes[$element->getName()] = true;
            }

            if ($element->getTipoElemento() == $tipoElementoConduits) {
                
                //Seccion [CONDUITS]
                //;;               Inlet            Outlet                      Manning    Inlet      Outlet     Init.      Max.      
                //;;Name           Node             Node             Length     N          Offset     Offset     Flow       Flow     
                $conduitsSection .= $element->getName() . "  " . $element->getStartNode()->getName() . "  " . $element->getEndNode()->getName() . "  " . $element->getLength() . "  " . $element->getManning() . "  " . $element->getInvertElvStart() . "  " . $element->getInvertElvEnd() . "  0  0";
                $conduitsSection .= "\n"; //salto de linea

                //Seccion [XSECTIONS]
                //;;Link           Shape        Geom1            Geom2      Geom3      Geom4      Barrels   
                $shape = $element->getShapec();
                $xsectionsSection .= $element->getName() . "  " . $shape[0]->getDescription() . "  " . $element->getDiameter() . "   0   0   0   1";
                $xsectionsSection .= "\n"; //salto de linea

                // Si el nodo inicial no existe en el proyecto lo añadimos
                if (!array_key_exists($element->getStartNode()->getName(), $existingNodes)) {
                    $node = $element->getStartNode();

                    if ($node->getTipoElemento() == $tipoElementoOutfall) {
                        //Seccion [JUNCTION]
                        //;;               Invert     Outfall    Stage/Table      Tide
                        //;;Name           Elev.      Type       Time Series      Gate
                        $outfallSection .= $node->getName() . "  " . $node->getInvertElev() . "  " . $node->getType() . "        NO";
                        $outfallSection .= "\n"; //salto de linea
                        
                        //Seccion [COORDINATES]
                        $coordinatesSection .= $node->getName() . "  " . $node->getX() . "  " . $node->getY();
                        $coordinatesSection .= "\n"; //salto de linea

                        $existingNodes[$node->getName()] = true;
                    } else if ($node->getTipoElemento() == $tipoElementoManhole) {
                        //Seccion [JUNCTION]
                        //;;               Invert     Max.       Init.      Surcharge  Ponded    
                        //;;Name           Elev.      Depth      Depth      Depth      Area 
                        $junctionSection .= $node->getName() . "  " . $node->getInvertElev() . "  " . $node->getMaxDepth() . "  0   0   0";
                        $junctionSection .= "\n"; //salto de linea

                        //Seccion [COORDINATES]
                        $coordinatesSection .= $node->getName() . "  " . $node->getX() . "  " . $node->getY();
                        $coordinatesSection .= "\n"; //salto de linea

                        $existingNodes[$node->getName()] = true;
                    }
                }

                // Si el nodo final no existe en el proyecto lo añadimos
                if (!array_key_exists($element->getEndNode()->getName(), $existingNodes)) {
                    $node = $element->getEndNode();

                    if ($node->getTipoElemento() == $tipoElementoOutfall) {
                        //Seccion [JUNCTION]
                        //;;               Invert     Outfall    Stage/Table      Tide
                        //;;Name           Elev.      Type       Time Series      Gate
                        $outfallSection .= $node->getName() . "  " . $node->getInvertElev() . "  " . $node->getType() . "        NO";
                        $outfallSection .= "\n"; //salto de linea

                        //Seccion [COORDINATES]
                        $coordinatesSection .= $node->getName() . "  " . $node->getX() . "  " . $node->getY();
                        $coordinatesSection .= "\n"; //salto de linea

                        $existingNodes[$node->getName()] = true;
                    } else if ($node->getTipoElemento() == $tipoElementoManhole) {
                        //Seccion [JUNCTION]
                        //;;               Invert     Max.       Init.      Surcharge  Ponded    
                        //;;Name           Elev.      Depth      Depth      Depth      Area 
                        $junctionSection .= $node->getName() . "  " . $node->getInvertElev() . "  " . $node->getMaxDepth() . "  0   0   0";
                        $junctionSection .= "\n"; //salto de linea

                        //Seccion [COORDINATES]
                        $coordinatesSection .= $node->getName() . "  " . $node->getX() . "  " . $node->getY();
                        $coordinatesSection .= "\n"; //salto de linea

                        $existingNodes[$node->getName()] = true;
                    }
                }
            }
        }
        
        //Seccion [MAP]
        if($project->getLimitMinX() != 0 || $project->getLimitMinY() != 0 || $project->getLimitMaxX() != 0 || $project->getLimitMaxY() != 0){
            $mapSection .= "DIMENSIONS   " . $project->getLimitMinX() . "   " . $project->getLimitMinY() . "   " . $project->getLimitMaxX() . "   " . $project->getLimitMaxY();
            $mapSection .= "\n";
            $mapSection .= "Units      None\n";
        }

        $content .= "\n" . $junctionSection;
        $content .= "\n" . $outfallSection;
        $content .= "\n" . $conduitsSection;
        $content .= "\n" . $xsectionsSection;
        $content .= "\n" . $mapSection;
        $content .= "\n" . $coordinatesSection;

        return $content;
    }

    private function isTextFile($filename) {

        // Para usar esta funcionalidad debe estar activado el modulo php_fileinfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // devuelve el tipo mime de su extensión

        if (!$finfo) {
            return true;
        }

        if (finfo_file($finfo, $filename) != "text/plain") {
            finfo_close($finfo);
            return false;
        }

        finfo_close($finfo);
        return true;
    }

}