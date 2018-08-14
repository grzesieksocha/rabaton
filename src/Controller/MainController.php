<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Form\GeneratorType;
use App\Service\CodeGenerator;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function homeAction(Request $request)
    {
        $form = $this->createForm(GeneratorType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $codesGenerator = new CodeGenerator();
            $codesGenerator->setCodesQuantity($data['quantity']);
            $codesGenerator->setCodeLength($data['length']);

            $filename = '/tmp/codes_' . date('YmdHis') . '.txt';
            $file = fopen($filename, 'x');
            $generator = $codesGenerator->getGenerator();

            foreach ($generator() as $code) {
                fwrite($file, $code . PHP_EOL);
            }

            fclose($file);

            $response = new BinaryFileResponse($filename);
            $response->headers->set('Content-Type', 'text/plain');
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'codes.txt'
            );
            $response->deleteFileAfterSend();

            return $response;
        }

        return $this->render(
            'base.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}