<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use HomeBundle\Entity\HelloWorld;

class DefaultController extends Controller
{
    /**
     * This method prints a "Hello World !" message to the screen
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a Message",
     *  requirements={
     *      {
     *          "name"="helloworld",
     *          "dataType"="string",
     *          "requirement"="[\w% ]+",
     *          "description"="message"
     *      }
     *  }
     * )
     *
     * @Route("/helloworld/{helloworld}", name="helloworld_route", requirements={"helloworld" = "[\w% ]+"}, defaults={"helloworld" = "Hello World !"})
     */
    public function helloWorldAction($helloworld)
    {
        return $this->render('HomeBundle:Default:index.html.twig', ['helloworld' => "Message to the world: " . $helloworld]);
    }

    /**
     * This method prints a "Hello World !" message to the screen and uses doctrine to save it in a database
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a Message",
     *  requirements={
     *      {
     *          "name"="helloworld",
     *          "dataType"="string",
     *          "requirement"="[\w% ]+",
     *          "description"="message"
     *      }
     *  }
     * )
     *
     * @Route("/helloworlddoctrine/{helloworld}", name="helloworlddoctrine_route", requirements={"helloworld" = "[\w% ]+"}, defaults={"helloworld" = "Hello World !"})
     */
    public function helloWorldDoctrineAction($helloworld)
    {
        $helloworldDoctrine = new HelloWorld();
        $helloworldDoctrine->setMessage($helloworld);

        $entitymanager = $this->getDoctrine()->getManager();
        // prepare object for database
        $entitymanager->persist($helloworldDoctrine);
        $entitymanager->flush();

        $helloworldRepository = $entitymanager->getRepository('HomeBundle:HelloWorld');
        $helloworldOutput = $helloworldRepository->findOneBy(array('message' => $helloworld));

        if(is_null($helloworldOutput)){
            throw $this->createNotFoundException('No Hello World Message found like: ' . $helloworld);
        }

        return $this->render('HomeBundle:Default:index.html.twig', ['helloworld' => "Message to the world: " . $helloworldOutput->getMessage()]);
    }
}
